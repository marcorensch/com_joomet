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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseModel;
use NXD\Component\Joomet\Administrator\Helper\JoometHelper;
use NXD\Component\Joomet\Administrator\Helper\RowObject;
use NXD\Component\Joomet\Administrator\Helper\RowType;

class TranslationsModel extends BaseModel
{
	public function __construct($config = [])
	{
		$this->params = ComponentHelper::getParams('com_joomet');
		parent::__construct($config);
	}

	public function getFileContents():array
	{
		$app              = Factory::getApplication();
		$uploadedFileName = $app->getUserState('com_joomet.upload.file');
		$localFileData    = $app->getUserState('com_joomet.local.file');

		if (!$uploadedFileName && !$localFileData)
		{
			$this->errors[] = Text::_("COM_JOOMET_MSG_SESSION_NO_FILE_SELECTED");

			return array("data" => [], "error" => "No file selected.");
		}

		if ($uploadedFileName)
		{

			$fileNameArr = JoometHelper::processFileName($uploadedFileName);
			$path = $fileNameArr['full_path'];

			if (!$path = JoometHelper::checkIfLocalFileExists($uploadedFileName))
			{
				$this->errors[] = Text::_("COM_JOOMET_MSG_SESSION_ERROR_FILE_NOT_FOUND");
				return array("data" => [], "error" => "File not found.");
			}
		}
		elseif ($localFileData)
		{
			$data = unserialize(base64_decode($localFileData));
			$path = $data->path;
		}
		else
		{
			error_log("No file selected");
			$this->errors[] = Text::_("COM_JOOMET_MSG_SESSION_NO_FILE_SELECTED");

			return array("data" => [], "error" => "No file selected.");
		}

		$fileRows    = JoometHelper::getFileContents($path);
		$translations = array();
		foreach ($fileRows as $rowNum => $originalString)
		{
			$row = new RowObject($originalString, $rowNum+1);
			if ($row->recognisedRowType === RowType::TRANSLATION)
			{
				$translations[] = $row;
			}
		}

		// Reset User State
		$app->setUserState('com_joomet.upload.file', null);
		$app->setUserState('com_joomet.local.file', null);

		return array("data" => $translations, "error" => "");
	}
}