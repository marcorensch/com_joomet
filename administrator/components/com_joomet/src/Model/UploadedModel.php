<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;
use NXD\Component\Joomet\Administrator\Helper\JoometHelper;
use Joomla\CMS\Filesystem\Folder;
use NXD\Component\Joomet\Administrator\Helper\LocalExtensionLanguageFileItem;

//@ToDo Compatibility 6.0


class UploadedModel extends AdminModel
{
	public $typeAlias = 'com_joomet.uploaded';

	protected string $sourceFolder = JPATH_ROOT . '/media/com_joomet/uploads/';

	public function __construct()
	{
		// Check if the $sourceFolder exists create if not
		if(!Folder::exists($this->sourceFolder)){
			Folder::create($this->sourceFolder, 0755);
		}
		parent::__construct();
	}


	public function getForm($data = [], $loadData = true): false | Form
	{
		$form = $this->loadForm($this->typeAlias, 'uploaded', ['control' => 'jform', 'load_data' => $loadData]);

		if(empty($form)){
			return false;
		}

		return $form;
	}

	public function getTargetView():string
	{
		// Get the URL Parameter for the task
		$vt = trim(Factory::getApplication()->input->get('target', '', 'string'));
		return $vt;
	}

	public function getItems():array
	{
		$items = array();
		$files = scandir($this->sourceFolder);
		foreach($files as $file){
			$path = $this->sourceFolder . $file;
			if(is_file($path)){
				$items[] = new LocalExtensionLanguageFileItem($path, 'uploaded');
			}
		}
		return $items;
	}
}