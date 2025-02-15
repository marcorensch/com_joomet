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

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;

class UploadModel extends AdminModel
{
	public $typeAlias = 'com_joomet.upload';


	public function getForm($data = [], $loadData = true): false | Form
	{
		$form = $this->loadForm($this->typeAlias, 'upload', ['control' => 'jform', 'load_data' => $loadData]);

		if(empty($form)){
			return false;
		}

		return $form;
	}

	public function getTargetView():string
	{
		// Get the URL Parameter for the task
		$vt = trim(Factory::getApplication()->input->get('target', '', 'string'));
		if(empty($vt)){
			$vt = 'check';
		}
		return $vt;
	}

}