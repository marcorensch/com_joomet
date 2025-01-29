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
use NXD\Component\Joomet\Administrator\Helper\LocalExtensionLanguageFileItem;

class EditModel extends BaseModel
{
	public function __construct($config = [])
	{
		$this->errors = array();
		$this->params = ComponentHelper::getParams('com_joomet');
		parent::__construct($config);
	}

	public function getFile(): false | LocalExtensionLanguageFileItem
	{
		$encodedFilePath = Factory::getApplication()->getUserState('com_joomet.edit.file');
		if(!$encodedFilePath){
			Factory::getApplication()->enqueueMessage(Text::_("COM_JOOMET_MSG_SESSION_NO_FILE_SELECTED"), "error");
			return false;
		}
		$path = base64_decode($encodedFilePath);
		return new LocalExtensionLanguageFileItem($path, "");
	}

}