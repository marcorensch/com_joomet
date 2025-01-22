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

use InvalidArgumentException;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Session\Session;
use NXD\Component\Joomet\Administrator\Helper\JoometHelper;
use RuntimeException;

/**
 * Joomet Check controller class.
 *
 * @since  1.0.0
 */
class EditController extends BaseController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $text_prefix = 'COM_JOOMET_EDIT';

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The name of the model.
	 * @param   string  $prefix  The prefix for the PHP class name.
	 * @param   array   $config  Array of configuration parameters.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel
	 *
	 * @since   1.0.0
	 */
	public function getModel($name = 'Edit', $prefix = 'Administrator', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function apply():void
	{
		$app = Factory::getApplication();
		// Check Token
		if (!Session::checkToken())
		{
			$app->enqueueMessage(Text::_('JINVALID_TOKEN_NOTICE'), 403);
		}
		$data = $app->input->get('file_content', '', 'RAW');
		$fileName = $app->input->get('file_name', '', 'string');

		$result = $this->storeContentToFile($data, $fileName);
		if($result){
			$app->enqueueMessage(Text::_('COM_JOOMET_MSG_FILE_SAVED'), 'message');
			$app->redirect('index.php?option=com_joomet&view=edit');
		}else{
			$app->enqueueMessage(Text::_('COM_JOOMET_MSG_FILE_NOT_SAVED'), 'error');
		}
	}

	public function save():void
	{
		$app = Factory::getApplication();
		// Check Token
		if (!Session::checkToken())
		{
			$app->enqueueMessage(Text::_('JINVALID_TOKEN_NOTICE'), 403);
		}

		$data = $app->input->get('file_content', '', 'RAW');
		$fileName = $app->input->get('file_name', '', 'string');

		$result = $this->storeContentToFile($data, $fileName);
		if($result){
			$app->enqueueMessage(Text::_('COM_JOOMET_MSG_FILE_SAVED'), 'message');
			$app->redirect('index.php?option=com_joomet&view=uploaded');
		}else{
			$app->enqueueMessage(Text::_('COM_JOOMET_MSG_FILE_NOT_SAVED'), 'error');
		}
	}

	public function cancel():void
	{
		$app = Factory::getApplication();
		$app->setUserState('com_joomet.edit.file', null);
		$app->redirect('index.php?option=com_joomet&view=uploaded');
	}

	private function storeContentToFile(string $content, string $fileName):bool
	{
		if (empty($fileName)) {
			throw new InvalidArgumentException("Filename cannot be empty.");
		}

		$file = JoometHelper::processFileName($fileName);
		$path = $file['full_path'];

		if (!is_writable(dirname($path))) {
			Factory::getApplication()->enqueueMessage(Text::_('COM_JOOMET_MSG_FILE_NOT_WRITABLE', dirname($path) ), 'error');
			return false;
		}

		return file_put_contents($path, $content) !== false;
	}
}
