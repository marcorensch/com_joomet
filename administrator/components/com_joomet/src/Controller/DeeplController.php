<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\Controller;

defined('_JEXEC') or die;

use DeepL\DeepLClient;
use DeepL\DeepLException;
use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Session\Session;
use NXD\Component\Joomet\Administrator\Helper\JoometHelper;

require_once JPATH_ADMINISTRATOR . '/components/com_joomet/vendor/autoload.php';

class DeeplController extends BaseController
{
	/**
	 * Method to get the Source and Target Languages from the DeepL API
	 * Requires a DeepL API key stored in the component configuration
	 *
	 * @throws DeepLException
	 *
	 * @since 1.0
	 */
	public function getLanguagesFromDeepl()
	{
		Session::checkToken('get') or die('Invalid Session Token');

		// get Params Settings
		$apiKey = JoometHelper::getDeeplApiKey();
		if (!$apiKey)
		{
			echo json_encode(['success' => false, 'message' => 'No API Key set']);
			Factory::getApplication()->close();
		}

		$deeplClient = new DeepLClient($apiKey);
		$languages = [];
		$languages['source'] = $deeplClient->getSourceLanguages();
		$languages['target'] = $deeplClient->getTargetLanguages();

		echo json_encode(['success' => true, 'languages' => $languages]);

		Factory::getApplication()->close();
	}

	/**
	 * Ajax Method to get a Translation from DeepL
	 * Data will be transfered by Factory::getInput()
	 *
	 * @throws DeepLException
	 *
	 * @since 1.0.0
	 */
	public function doTranslation():void
	{
		Session::checkToken('post') or die('Invalid Session Token');

		// get Params Settings
		$apiKey = JoometHelper::getDeeplApiKey();
		if (!$apiKey)
		{
			echo json_encode(['success' => false, 'message' => 'No API Key set']);
			Factory::getApplication()->close();
		}

		$input = Factory::getApplication()->input;
		$rowData = $input->post->get('rowData', "", 'raw'); // RAW required for HTML Support

		if( empty( $rowData )){
			echo json_encode(['success' => false, 'message' => 'No Data to Translate']);
			Factory::getApplication()->close();
		}

		try{
			// '{"sourceLanguage":"auto","targetLanguage":"BG","formality":false,"content":"\"foo content\"","rowNum":1}',
			$rowData = json_decode($rowData, false);

			if (!is_object($rowData)) {
				throw new \InvalidArgumentException("Invalid data format. Expected an object.");
			}

			$requiredFields = ['sourceLanguage', 'targetLanguage', 'content', 'rowNum'];
			foreach ($requiredFields as $field) {
				if (empty($rowData->{$field})) {
					throw new Exception("Missing required field: $field");
				}
			}
		}catch (Exception $e){
			echo json_encode(['success' => false, 'message' => $e->getMessage()]);
			Factory::getApplication()->close();
		}

		$deeplClient = new DeepLClient($apiKey);
		$srcLang = $rowData->sourceLanguage === 'auto' ? null : $rowData->sourceLanguage;
		$options = array();
		$options['preserve_formatting'] = true;
		$options['tag_handling'] = "html";
		$options['split_sentences'] = 'nonewlines';
		if($rowData->formality)
		{
			$options['formality'] = 'more';
		}

		$translationResult = $deeplClient->translateText(
			$rowData->content,
			$srcLang,
			$rowData->targetLanguage,
			$options
		);

		// Bugfixing for misplaced double-quotes after translation
		list($translationResult->text, $changesMade) = $this->fixTranslationValidity($rowData->content, $translationResult->text);

		echo json_encode(['success' => true, 'translation' => $translationResult->text, 'changesMade' => $changesMade]);

		Factory::getApplication()->close();
	}

	private function fixTranslationValidity(string $original, string $translation):array
	{
		list($translation, $changesMade) = $this->fixMisplacedDoubleQuotes($original, $translation);
		list($translation, $changesMade) = $this->fixUnescapedDoubleQuotes($translation);
		return [$translation, $changesMade];
	}

	private function fixMisplacedDoubleQuotes(string $original, string $translation):array
	{
		$changesMade = false;
		if(str_starts_with($original, '"') && !str_starts_with($translation, '"')){
			$changesMade = true;
			$translation = '"' . $translation;
		}
		return [$translation, $changesMade];
	}

	private function fixUnescapedDoubleQuotes(string $translation):array
	{
		// Check whether the string is less than two characters long, as escaping would not be necessary
		if (strlen($translation) <= 2) {
			return [$translation, false];
		}

		$changesMade = false;

		// Use a regular expression to find all inverted commas that are NOT at the beginning or end
		$result = preg_replace_callback(
			'/(?<!\\\\)(?<!^)"(?!$)/',
			function ($matches) use (&$changesMade) {
				$changesMade = true;
				return '\\"'; // Escaping the inverted commas found
			},
			$translation
		);

		return [$result, $changesMade];
	}
}