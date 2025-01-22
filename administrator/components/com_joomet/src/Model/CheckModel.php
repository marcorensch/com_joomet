<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\Model;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Registry\Registry;
use NXD\Component\Joomet\Administrator\Helper\CheckerMessage;
use NXD\Component\Joomet\Administrator\Helper\JoometErrorType;
use NXD\Component\Joomet\Administrator\Helper\JoometMessageSource;
use NXD\Component\Joomet\Administrator\Helper\JoometHelper;
use NXD\Component\Joomet\Administrator\Helper\JoometMessageType;
use NXD\Component\Joomet\Administrator\Helper\RowObject;
use NXD\Component\Joomet\Administrator\Helper\RowType;

/**
 * Methods supporting a list of joomet records.
 *
 * @since  1.0
 */
class CheckModel extends ListModel
{
	private Registry $params;
	private array $errors;
	private array $warnings;
	private array $infos;

	private array $statistics;

	private array $constants = [];

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @throws Exception
	 * @since   1.0
	 * @see     \JControllerLegacy
	 */

	public function __construct($config = [])
	{
		$this->errors = array();
		$this->params = ComponentHelper::getParams('com_joomet');
		parent::__construct($config);
	}

	public function processFile(): array
	{
		$app      = Factory::getApplication();
		$fileName = $app->getUserState('com_joomet.upload.file');

		if (!$fileName)
		{
			$this->errors[] = Text::_("COM_JOOMET_MSG_SESSION_NO_FILE_SELECTED");

			return array("statistics" => [], "data" => [], "filenameChecks" => array(), "error" => "No file selected.");
		}

		if (!$path = JoometHelper::checkIfLocalFileExists($fileName))
		{
			$this->errors[] = Text::_("COM_JOOMET_MSG_SESSION_ERROR_FILE_NOT_FOUND");

			return array("statistics" => [], "data" => [], "filenameChecks" => array(), "error" => "File not found.");
		}

		$fileRows    = JoometHelper::getFileContents($path);
		$checkedRows = array();
		$rowNum      = 1;

		$fileNameArr = JoometHelper::processFileName($fileName);
		$this->statistics['uploaded'] = $fileNameArr['uploaded'];
		$this->statistics['file_name'] = $fileNameArr['name'];
		$this->statistics['total']       = count($fileRows);
		$this->statistics['empty']       = 0;
		$this->statistics['translation'] = 0;
		$this->statistics['comment']     = 0;
		$this->statistics['valid']       = 0;

		$this->statistics['invalid_rows'] = array();

		// Increase Max Execution Time based on the params
		$previousMaxExecutionTime = ini_get('max_execution_time'); // Time in seconds ("30")
		$maxExecutionTime         = $this->params->get('max_execution_time', 90);
		ini_set('max_execution_time', $maxExecutionTime);

		$filenameChecks              = [];
		$filenameChecks['prefixed']  = $this->filenamePrefixed($this->statistics['file_name']);
		$filenameChecks['structure'] = $this->filenameHasCorrectStructure($this->statistics['file_name']);

		foreach ($fileRows as $originalString)
		{
			$row = new RowObject($originalString, $rowNum++);

			switch ($row->recognisedRowType)
			{
				case RowType::EMPTY:
				default:
					$this->statistics['empty']++;
					break;
				case RowType::COMMENT:
					$this->statistics['comment']++;
					break;
				case RowType::TRANSLATION:
					$this->statistics['translation']++;
					break;
			}

			if ($row->recognisedRowType === RowType::TRANSLATION)
			{
				// Do the checks
				if ($row->constant)
				{
					$this->doConstantChecks($row);
				}

				if ($row->content)
				{
					$this->doTranslationChecks($row);
				}
			}

			$checkedRows[] = $row;
		}

		// Reset Max Execution Time
		ini_set('max_execution_time', $previousMaxExecutionTime);

		// Prepare Report Data
		$this->prepareReportData($checkedRows, $this->statistics, $filenameChecks);

		return array("statistics" => $this->statistics, "data" => $checkedRows, "filenameChecks" => $filenameChecks);
	}

	private function prepareReportData($rows, $statistics, $fileNameChecks):void
	{
		$app = Factory::getApplication();

		// Session speichern
		$app->getSession()->set('rowsReportData', $rows);
		$app->getSession()->set('fileStatsData', $statistics);
		$app->getSession()->set('fileNameChecksData', $fileNameChecks);
	}

	public function getErrors(): array
	{
		return $this->errors;
	}

	// Checks
	// Language Constant
	private function hasLowercaseCharacters(string $string): bool
	{
		return strtoupper($string) !== $string;
	}

	/*
	 * Checks for invalid Characters
	 * Note: check is case-insensitive (all uppercase is different check)
	 *
	 * returns true if invalid characters has been found
	 *
	 */
	private function hasInvalidCharacters(string $string): bool|array
	{
		if (preg_match_all('/[^A-Z0-9_]/iu', $string, $matches))
		{
			// Gibt das erste gefundene ungültige Zeichen zurück
			return $matches[0];
		}

		return false;
	}

	private function constantIsAlreadyDefined(string $constant, int $rowNum): bool|int
	{
		if (in_array($constant, $this->constants))
		{
			// return the index (rowNum) of the constant
			return array_search($constant, $this->constants);
		}

		$this->constants[$rowNum] = $constant;

		return false;
	}

	private function doConstantChecks(RowObject $row): void
	{
		if ($this->hasLowercaseCharacters($row->constant))
		{
			$row->errors[]                      = new CheckerMessage(
				Text::_('COM_JOOMET_MSG_CONSTANT_MUST_BE_UPPERCASE'),
				JoometMessageSource::CONSTANT,
				JoometErrorType::CONSTANT_MUST_BE_UPPERCASE,
				JoometMessageType::ERROR
			);
			$this->statistics['invalid_rows'][] = $row->rowNum;
		}
		if ($chars = $this->hasInvalidCharacters($row->constant))
		{
			foreach ($chars as $char)
			{
				$row->errors[] = new CheckerMessage(
					Text::sprintf('COM_JOOMET_MSG_CONSTANT_CONTAINS_INVALID_CHAR', $char),
					JoometMessageSource::CONSTANT,
					JoometErrorType::CONSTANT_CONTAINS_INVALID_CHAR,
					JoometMessageType::ERROR
				);
			}
			$this->statistics['invalid_rows'][] = $row->rowNum;
		}
		if ($alreadyDefinedRow = $this->constantIsAlreadyDefined($row->constant, $row->rowNum))
		{
			$row->errors[]                      = new CheckerMessage(
				Text::sprintf('COM_JOOMET_MSG_CONSTANT_ALREADY_DEFINED', $alreadyDefinedRow),
				JoometMessageSource::CONSTANT,
				JoometErrorType::CONSTANT_ALREADY_DEFINED,
				JoometMessageType::ERROR
			);
			$this->statistics['invalid_rows'][] = $row->rowNum;
		}
	}

	private function doTranslationChecks(RowObject $row): void
	{
		if ($this->translationStringIsNotEncapsulated($row->content))
		{
			$row->errors[]                      = new CheckerMessage(
				Text::_('COM_JOOMET_MSG_TRANSLATION_MUST_BE_ENCAPSULATED'),
				JoometMessageSource::CONTENT,
				JoometErrorType::TRANSLATION_NOT_ENCAPSULATED,
				JoometMessageType::ERROR,
			);
			$this->statistics['invalid_rows'][] = $row->rowNum;
		}

		if ($this->lastCharacterInStringIsBackslash($row->content))
		{
			$row->errors[]                      = new CheckerMessage(
				Text::_('COM_JOOMET_MSG_TRANSLATION_MUST_NOT_END_WITH_BACKSLASH'),
				JoometMessageSource::CONTENT,
				JoometErrorType::TRANSLATION_LAST_CHAR_IS_BACKSLASH,
				JoometMessageType::ERROR,
			);
			$this->statistics['invalid_rows'][] = $row->rowNum;
		}

		if ($dquotes = $this->doubleQuotesNotEscaped($row->content))
		{
			foreach ($dquotes as $dquote)
			{
				$position      = $dquote[1] + 1; // Position +1 do identify the double quote itself
				$row->errors[] = new CheckerMessage(
					Text::sprintf('COM_JOOMET_MSG_TRANSLATION_DOUBLE_QUOTES_NOT_ESCAPED', $position),
					JoometMessageSource::CONTENT,
					JoometErrorType::TRANSLATION_DOUBLE_QUOTES_NOT_ESCAPED,
					JoometMessageType::ERROR,
				);
			}
			$this->statistics['invalid_rows'][] = $row->rowNum;
		}
		if ($hasHtml = $this->containsHtml($row->content))
		{
			$row->infos[] = new CheckerMessage(
				Text::_('COM_JOOMET_MSG_TRANSLATION_CONTAINS_HTML'),
				JoometMessageSource::CONTENT,
				JoometErrorType::TRANSLATION_CONTAINS_HTML,
				JoometMessageType::INFO
			);
		}
		if ($hasHtml)
		{
			if ($tags = $this->hasUnbalancedBrackets($row->content))
			{
				foreach ($tags as $tag)
				{
					$row->warnings[] = new CheckerMessage(
						Text::sprintf('COM_JOOMET_MSG_TRANSLATION_UNBALANCED_TAGS', $tag['tag'], $tag['position']),
						JoometMessageSource::CONTENT,
						JoometErrorType::TRANSLATION_UNBALANCED_TAGS,
						JoometMessageType::WARNING
					);
				}
			}
		}
	}

	private function translationStringIsNotEncapsulated(string $string): bool
	{
		$string = trim($string);

		return (!str_starts_with($string, '"') || !str_ends_with($string, '"'));
	}

	private function lastCharacterInStringIsBackslash(string $string): bool
	{
		$string = trim($string);

		return (str_ends_with($string, '\\"'));
	}

	private function doubleQuotesNotEscaped(string $string): false|array
	{
		$string = trim($string);
		// remove first and last appearance of double quotes if they are at the start or end of the string
		if (str_starts_with($string, '"'))
		{
			$string = substr($string, 1);
		}
		if (str_ends_with($string, '"'))
		{
			$string = substr($string, 0, -1);
		}
		// do the check if there are unescaped double quotes left in the string
		if (preg_match_all('/(?<!\\\\)"/', $string, $matches, PREG_OFFSET_CAPTURE))
		{
			return $matches[0];
		}

		return false;
	}

	private function containsHtml(string $string): bool
	{
		return (preg_match('/<[^>]+>/', $string) > 0);
	}

	private function hasUnbalancedBrackets(string $string): array
	{
		$stack = array();

		$unbalancedTags = array();
		$string         = trim($string);
		preg_match_all('/<\/?([a-zA-Z]+\d?)([^>]*)>/', $string, $matches, PREG_OFFSET_CAPTURE);

		foreach ($matches[0] as $index => $match)
		{
			$fullTag  = $match[0]; // Whole Tag ("<div>", "</div>")
			$tagName  = $matches[1][$index][0]; // Tag-Name ("div")
			$position = $match[1]; // Start position of tag in string

			// Check if it is a closing tag
			if (str_starts_with($fullTag, '</'))
			{
				// check stack for opening tag
				if (empty($stack) || $stack[count($stack) - 1]['tag'] !== $tagName)
				{
					// no opening tag found > add to unbalancedTags
					$unbalancedTags[] = [
						'tag'      => $tagName,
						'position' => $position
					];
				}
				else
				{
					// Tag found - remove from stack
					array_pop($stack);
				}
			}
			// Check for self-closing tag (<br />, <img />)
			elseif (!preg_match('/\/>$|<(?:br|img|hr|input|source|link|meta|area|base|col|embed|keygen|param|track|wbr)>$/i', $fullTag))
			{
				// add opening tag to stack
				$stack[] = ['tag' => $tagName, 'position' => $position];
			}
		}

		// Every element in the stack is an unbalanced tag
		while (!empty($stack))
		{
			$unmatchedTag     = array_pop($stack);
			$unbalancedTags[] = [
				'tag'      => $unmatchedTag['tag'],
				'position' => $unmatchedTag['position'],
			];
		}

		return $unbalancedTags;
	}

	/**
	 * Tests if the given filename starts with mod | com | plg | tpl
	 * https://regex101.com/r/UarFwb/1
	 *
	 * @param   string  $fileName
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	private function filenamePrefixed(string $fileName): bool
	{
//		$acceptable = array('mod','com','plg','tpl');
//		$parts = explode('_', $fileName);
//		$prefix = $parts[0];
//		return in_array($prefix, $acceptable);
		return preg_match('/^(mod|com|plg|tpl)_/', $fileName);
	}

	/**
	 * Checks if a given filename has a valid semantic like [mod,com,plg,tpl](_[a-z])* and ends with .sys.ini or .ini
	 * https://regex101.com/r/8Q73Qc/1
	 *
	 * @param   string  $fileName
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	private function filenameHasCorrectStructure(string $fileName): bool
	{
		return preg_match('/^(mod|com|plg|tpl)(_[a-z]+)+(\.sys)?\.ini$/', $fileName);
	}
}
