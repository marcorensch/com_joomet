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

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Model\BaseModel;

/**
 * Joomet Check controller class.
 *
 * @since  1.0.0
 */
class TranslationsController extends BaseController
{
	public function getModel($name = 'Translations', $prefix = 'Administrator', $config = ['ignore_request' => true]): BaseModel
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function generateTranslatedFile()
	{
		$app   = Factory::getApplication();
		$input = $app->input;
		$data  = $input->post->get('jform', array(), 'array');

		$data = $this->cleanupTranslationData($data);
		$rows = $this->buildRows($data);

		error_log(var_export($data,1));

		$fileName    = "test.ini";
		$fileContent = "";
		foreach ($rows as $row){
			error_log($row);
			$fileContent .= $row . "\n";
		}
		// Create an ini file
		$this->createFile($fileName, $fileContent);

		// Skript beenden, um weitere Joomla-Verarbeitungen zu vermeiden
		$app->close();
	}

	private function createFile($fileName, $fileContent)
	{
		// Headers setzen, um die Datei als Download bereitzustellen
		$app = Factory::getApplication();
		$app->allowCache(false); // Caching deaktivieren, falls nötig

		// Clean Output-Buffer
		ob_clean();
		ob_start();

		// PHP-Header senden
		header('Content-Type: text/ini'); // MIME-Typ setzen
		header('Content-Disposition: attachment; filename="' . $fileName . '"'); // Download-Fenster
		header('Content-Length: ' . strlen($fileContent)); // Länge der Datei

		// Dateiinhalt ausgeben
		echo $fileContent;
	}

	/**
	 * Removes skip_toggler values from the array
	 *
	 * @param   array  $data
	 *
	 * @return array
	 *
	 * @since version
	 */
	private function cleanupTranslationData(array $data): array
	{
		// remove skip_all_toggler from array
		if (array_key_exists('skip_all_toggler', $data))
		{
			unset($data['skip_all_toggler']);
		}

		// remove all skip togglers "skip_element_%n" from array
		foreach ($data as $key => $value)
		{
			if (str_starts_with($key, 'skip_element_'))
			{
				unset($data[$key]);
			}
		}
		return $data;
	}

	private function buildRows(array $data):array
	{
		$rows = [];
		foreach ($data as $key => $value)
		{
			if(str_starts_with($key, 'translation_constant_')){
				// get the number from the $value by removing translation_constant_
				$rowNumber = intval(substr($key, 21));
				$rows[$rowNumber] = $value . "=" . $data['translation_editor_' . $rowNumber] ?? "";
			}
		}
		return $rows;

	}
}