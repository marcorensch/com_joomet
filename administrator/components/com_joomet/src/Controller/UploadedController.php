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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Session\Session;
use Joomla\CMS\User\CurrentUserInterface;

/**
 * Joomet Check controller class.
 *
 * @since  1.0.0
 */
class UploadedController extends BaseController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $text_prefix = 'COM_JOOMET_UPLOADED';

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
	public function getModel($name = 'Uploaded', $prefix = 'Administrator', $config = ['ignore_request' => true]): bool|BaseDatabaseModel|CurrentUserInterface
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function handleCheckFileClicked(): void
	{
		$fileName = Factory::getApplication()->input->get('file', "", 'string');
		Factory::getApplication()->setUserState('com_joomet.upload.file', $fileName);
		$this->setRedirect('index.php?option=com_joomet&view=check');
	}

	public function handleTranslateFileClicked(): void
	{
		$fileName = Factory::getApplication()->input->get('file', "", 'string');
		Factory::getApplication()->setUserState('com_joomet.upload.file', $fileName);
		$this->setRedirect('index.php?option=com_joomet&view=translate');
	}

	/**
	 * @throws \Exception
	 *
	 * @since 1.0.0
	 */
	public function handleTrashClicked(): void
	{
		if (!Session::checkToken())
		{
			throw new \Exception(Text::_('JINVALID_TOKEN_NOTICE'), 403);
		}

		$app   = Factory::getApplication();
		$input = $app->input;

		// Standardmäßig verwendet Joomla 'cid' als Name für Checkbox-Werte
		$files = $input->get('cid', [], 'array');

		foreach ($files as $fileName)
		{
			$pathToFile = $this->getFullPath($fileName);
			if (file_exists($pathToFile))
			{
				if(!unlink($pathToFile))
				{
					$app->enqueueMessage(Text::sprintf('COM_JOOMET_FILE_NOT_DELETED', $fileName), 'error');
				}
			}
		}

		$this->setRedirect('index.php?option=com_joomet&view=uploaded');
	}

	/**
	 * @throws \Exception
	 *
	 * @since 1.0.0
	 */
	public function handleDeleteFileClicked(): void
	{
		error_log(var_export($_GET, true));
		if (!Session::checkToken())
		{
			throw new \Exception(Text::_('JINVALID_TOKEN_NOTICE'), 403);
		}

		$app   = Factory::getApplication();
		$input = $app->input;

		$fileName = $input->get('file', "", 'string');

		if($fileName){
			$pathToFile = $this->getFullPath($fileName);
			if (file_exists($pathToFile))
			{
				if(!unlink($pathToFile))
				{
					$app->enqueueMessage(Text::sprintf('COM_JOOMET_FILE_NOT_DELETED', $fileName), 'error');
				}
			}
		}

		$this->setRedirect('index.php?option=com_joomet&view=uploaded');

	}

	public function handleEditFileClicked():void
	{
		if (!Session::checkToken())
		{
			throw new \Exception(Text::_('JINVALID_TOKEN_NOTICE'), 403);
		}

		$fileName = Factory::getApplication()->input->get('file', "", 'string');
		Factory::getApplication()->setUserState('com_joomet.edit.file', $fileName);
		$this->setRedirect('index.php?option=com_joomet&view=edit');
	}

	private function getFullPath(string $fileName):string {
		return JPATH_ROOT . '/media/com_joomet/uploads/' . $fileName;
	}
}
