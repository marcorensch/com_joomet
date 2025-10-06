<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\Controller;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\Filesystem\Path;

/**
 * Joomet Check controller class.
 *
 * @since  1.0.0
 */
class UploadController extends AdminController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $text_prefix = 'COM_JOOMET_UPLOAD';

	protected string $destination = JPATH_ROOT . '/media/com_joomet/uploads/';

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
	public function getModel($name = 'Upload', $prefix = 'Administrator', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function handleFileUpload():void
	{
		$app = Factory::getApplication();
		// Get the uploaded file data from the input
		$input = $app->input;
		// Get the uploaded file data
		$files = $input->files->get('jform', array(), 'array');
		$targetView = $input->get('target_view', "", 'string');

		if(empty($files) || empty($files['upload_file'])){
			$app->enqueueMessage(Text::_("COM_JOOMET_MSG_NO_FILE_UPLOADED"), 'error');
		}

		$file = $files['upload_file'];
		$file['ext'] = pathinfo($file['name'], PATHINFO_EXTENSION);
		if($file['ext'] !== 'ini'){
			$app->enqueueMessage(Text::_("COM_JOOMET_MSG_FILE_EXTENSION_NOT_SUPPORTED"), 'error');
			$this->setRedirect('index.php?option=com_joomet&view=upload');
			return;
		}
		$file['storedName'] = time() . "." . $file['name'];

		if($pathToFile = $this->storeFile($file)){
			$app->enqueueMessage(
				Text::sprintf('COM_JOOMET_MSG_FILE_UPLOAD_SUCCESS', $file['name']),
				'message'
			);
		}else{
			$app->enqueueMessage(Text::_('COM_JOOMET_MSG_FILE_UPLOAD_FAILED'), 'error');
			$this->setRedirect('index.php?option=com_joomet');
			return;
		}

		Factory::getApplication()->setUserState('com_joomet.file', base64_encode($pathToFile));
		Factory::getApplication()->setUserState('com_joomet.context', "custom");

		// Weiterleitung an einen anderen Controller
		$this->setRedirect('index.php?option=com_joomet&view='.$targetView);

	}

	private function storeFile(array $file): false | string
	{
		// Überprüfen, ob das Zielverzeichnis existiert, andernfalls erstellen
		if (!Folder::exists($this->destination)) {
			if (!Folder::create($this->destination)) {
				Factory::getApplication()->enqueueMessage(Text::_('COM_JOOMET_MSG_COULD_NOT_CREATE_DESTINATION_PATH'), 'error');
				return false;
			}
		}

		// Bestimme den Zielpfad für die Datei
		$targetPath = Path::clean($this->destination . $file['storedName']);

		// Datei verschieben
		if (!File::upload($file['tmp_name'], $targetPath)) {
			Factory::getApplication()->enqueueMessage(Text::_('COM_JOOMET_MSG_FILE_UPLOAD_FAILED'), 'error');
			return false;
		}

		// Datei erfolgreich gespeichert
		return $targetPath;
	}

	private function storeFileOld(array $file): bool
	{
		// store the file locally and return path to file
		if (!is_dir($this->destination)) {
			if(!mkdir($this->destination, 0755, true))
			{
				Factory::getApplication()->enqueueMessage(Text::_('COM_JOOMET_MSG_COULD_NOT_CREATE_DESTINATION_PATH'), 'error');
				return false;
			}
		}

		$targetPath = $this->destination . $file['storedName'];

		if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
			Factory::getApplication()->enqueueMessage(Text::_('COM_JOOMET_MSG_FILE_UPLOAD_FAILED'), 'error');
			return false;
		}

		return true;
	}
}
