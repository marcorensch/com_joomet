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
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\User\CurrentUserInterface;



class LocalextensionController extends BaseController {

	protected $text_prefix = 'COM_JOOMET_LOCALEXTENSION';

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The name of the model.
	 * @param   string  $prefix  The prefix for the PHP class name.
	 * @param   array   $config  Array of configuration parameters.
	 *
	 * @return  bool|BaseDatabaseModel|CurrentUserInterface
	 *
	 * @since   1.0.0
	 */
	public function getModel($name = 'Localextension', $prefix = 'Administrator', $config = ['ignore_request' => true]): bool|BaseDatabaseModel|CurrentUserInterface
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function handleCheckFileClicked(): void
	{
		$encodedPath = Factory::getApplication()->input->get('file', "", 'string');
		Factory::getApplication()->setUserState('com_joomet.file', $encodedPath);
		$this->setRedirect('index.php?option=com_joomet&view=check');
	}

	public function handleTranslateFileClicked(): void
	{
		$encodedPath = Factory::getApplication()->input->get('file', "", 'string');
		Factory::getApplication()->setUserState('com_joomet.file', $encodedPath);
		$this->setRedirect('index.php?option=com_joomet&view=translations');
	}
}