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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use NXD\Component\Joomet\Administrator\Helper\JoometHelper;
use NXD\Component\Joomet\Administrator\Helper\LanguageFileItem;
use NXD\Component\Joomet\Administrator\Helper\RowObject;
use NXD\Component\Joomet\Administrator\Helper\RowType;

class TranslationsModel extends AdminModel
{
	public $typeAlias = 'com_joomet.translations';

	private array $errors;

	public function __construct($config = [])
	{
		$this->params = ComponentHelper::getParams('com_joomet');
		parent::__construct($config);
	}

	public function getForm($data = [], $loadData = true): false | Form
	{
		$form = $this->loadForm($this->typeAlias, 'translation', ['control' => 'jform', 'load_data' => $loadData]);

		if(empty($form)){
			return false;
		}

		return $form;
	}

	public function getFileContents(): array
	{
		$app               = Factory::getApplication();
		$pathToFileEncoded = $app->getUserState('com_joomet.file');

		if (!$pathToFileEncoded)
		{
			$this->errors[] = Text::_("COM_JOOMET_MSG_SESSION_NO_FILE_SELECTED");

			return array("data" => [], "error" => "No file selected.");
		}

		$pathToFile = base64_decode($pathToFileEncoded);

		if (!File::exists($pathToFile))
		{
			$this->errors[] = Text::_("COM_JOOMET_MSG_FILE_DOES_NOT_EXIST");

			return array("data" => [], "error" => "No file selected.");
		}

		$fileRows     = JoometHelper::getFileContents($pathToFile);
		$rowsToTranslate = array();
		$rowsToSkip = array();
		foreach ($fileRows as $rowNum => $originalString)
		{
			$row = new RowObject($originalString, $rowNum + 1);
			if($this->params->get('ignore_empty_rows', 1) && $row->recognisedRowType === RowType::EMPTY){
				$rowsToSkip[] = $row;
				continue;
			}

			if($this->params->get('ignore_comments', 0) && $row->recognisedRowType === RowType::COMMENT){
				$rowsToSkip[] = $row;
				continue;
			}

			$rowsToTranslate[] = $row;
		}

		// Reset User State
		//$app->setUserState('com_joomet.file', null);

		return array("data" => array("rowsToTranslate" => $rowsToTranslate, "rowsToSkip" => $rowsToSkip), "error" => "");
	}

	public function getFileName()
	{
		$app = Factory::getApplication();
		$file = $app->getUserState('com_joomet.file');
		$file_path_decoded = base64_decode($file);
		$joometFile = new LanguageFileItem($file_path_decoded, "");
		return $joometFile->label;
	}

	public function checkComponentConfig()
	{
		$status = true;
		$params = ComponentHelper::getParams('com_joomet');
		if(!$params->get('api_key_deepl', '')){
			Factory::getApplication()->enqueueMessage(Text::_('COM_JOOMET_MSG_DEEPL_API_KEY_MISSING'), 'error');
			$status = false;
		}
		if(!$params->get('deepl_language_cache', '')){
			Factory::getApplication()->enqueueMessage(Text::_('COM_JOOMET_MSG_DEEPL_LANGUAGE_CACHE_EMPTY'), 'error');
			$status = false;
		}
		return $status;
	}
}