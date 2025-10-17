<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\Model;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\CMS\Session\Session;
use Joomla\Filesystem\File;
use NXD\Component\Joomet\Administrator\Helper\LanguageFileItem;

class EditModel extends BaseModel
{
	public array $errors = array();
	public Registry $params;
	public File $file;
	public string $typeAlias = 'com_joomet.edit';

	public function __construct($config = [])
	{
		$this->errors = array();
		$this->params = ComponentHelper::getParams('com_joomet');
		parent::__construct($config);
	}

	public function getErrors(): array
	{
		return $this->errors;
	}

	/**
	 * @throws \Exception
	 * @since 1.1.0
	 *
	 */
	public function getFile(): false | LanguageFileItem
	{
		if (!Session::checkToken('get'))
		{
			throw new \Exception(Text::_('JINVALID_TOKEN_NOTICE'), 403);
		}

		$input = Factory::getApplication()->input;
		$encodedFilePath = $input->get('file', '', 'string');

		if(!$encodedFilePath){
			Factory::getApplication()->enqueueMessage(Text::_("COM_JOOMET_MSG_SESSION_NO_FILE_SELECTED"), "error");
			return false;
		}
		$path = base64_decode($encodedFilePath);
		return new LanguageFileItem($path, "");
	}

}