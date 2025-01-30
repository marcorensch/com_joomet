<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\Model;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseModel;
use NXD\Component\Joomet\Administrator\Helper\JoometHelper;
use NXD\Component\Joomet\Administrator\Helper\RowObject;
use NXD\Component\Joomet\Administrator\Helper\RowType;

class TranslationsModel extends BaseModel
{
	private array $errors;

	public function __construct($config = [])
	{
		$this->params = ComponentHelper::getParams('com_joomet');
		parent::__construct($config);
	}

	public function getFileContents(): array
	{
		$app               = Factory::getApplication();
		$pathToFileEncoded = $app->getUserState('com_joomet.file');


		echo '<pre>' . var_export($pathToFileEncoded, 1) . '</pre>';

		if (!$pathToFileEncoded)
		{
			$this->errors[] = Text::_("COM_JOOMET_MSG_SESSION_NO_FILE_SELECTED");

			return array("data" => [], "error" => "No file selected.");
		}

		$pathToFile = base64_decode($pathToFileEncoded);

		echo '<pre>' . var_export($pathToFile, 1) . '</pre>';


		if (!File::exists($pathToFile))
		{
			$this->errors[] = Text::_("COM_JOOMET_MSG_FILE_DOES_NOT_EXIST");

			return array("data" => [], "error" => "No file selected.");
		}

		$fileRows     = JoometHelper::getFileContents($pathToFile);
		$translations = array();
		foreach ($fileRows as $rowNum => $originalString)
		{
			$row = new RowObject($originalString, $rowNum + 1);
			if ($row->recognisedRowType === RowType::TRANSLATION)
			{
				$translations[] = $row;
			}
		}

		// Reset User State
		$app->setUserState('com_joomet.file', null);

		return array("data" => $translations, "error" => "");
	}
}