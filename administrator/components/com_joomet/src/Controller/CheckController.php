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

use Exception;
use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use NXD\Component\Joomet\Administrator\Helper\LanguageFileItem;

/**
 * Joomet Check controller class.
 *
 * @since  1.0.0
 */
class CheckController extends AdminController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The name of the model.
	 * @param   string  $prefix  The prefix for the PHP class name.
	 * @param   array   $config  Array of configuration parameters.
	 *
	 * @return  BaseModel
	 *
	 * @since   1.0.0
	 */
	public function getModel($name = 'Check', $prefix = 'Administrator', $config = ['ignore_request' => true]): BaseModel
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function handleEditFileClicked(): void
	{
		try{
			$app = Factory::getApplication();
		}catch (Exception $e) {
			error_log('Error retrieving application data: ' . $e->getMessage());
			return;
		}

		$file = $app->getUserState('com_joomet.file');
		$user = $app->getIdentity();

		if(!$user->authorise("core.edit", "com_joomet")){
			$app->enqueueMessage(Text::_('COM_JOOMET_CHECKER_EDIT_NOT_ALLOWED_BY_YOU_MSG'), "error");
			return;
		}

		if($app->getUserState('com_joomet.context') !== "custom"){
			$app->enqueueMessage(Text::_('COM_JOOMET_CHECKER_EDIT_NOT_ALLOWED_INSTALLED_MSG'), "error");
			return;
		}

		if(!$file){
			$app->enqueueMessage(Text::_('COM_JOOMET_CHECKER_EDIT_NOK_FILE_UNDEFINED'), "error");
			return;
		}

		$pathToFile = base64_decode($file);
		if(!File::exists($pathToFile)){
			$app->enqueueMessage(Text::sprintf('COM_JOOMET_CHECKER_EDIT_NOK_FILE_NOT_FOUND', $pathToFile), "error");
			return;
		}

		$this->setRedirect('index.php?option=com_joomet&view=edit&'.Session::getFormToken().'=1&file='.$file);
	}

	public function handleDownloadFileClicked():void
	{
		try{
			$app = Factory::getApplication();
		}catch (Exception $e) {
			error_log('Error retrieving application data: ' . $e->getMessage());
			return;
		}

		$file = $app->getUserState('com_joomet.file');
		if(!$file){
			$app->enqueueMessage("File Download could not been initiated.", "error");
			$this->setRedirect('index.php?option=com_joomet&view=check');
			return;
		}

		$pathToFile = base64_decode($file);

		if (!File::exists($pathToFile) || !is_readable($pathToFile)) {
			$app->enqueueMessage("File does not exist or is not readable.", "error");
			$this->setRedirect('index.php?option=com_joomet&view=check');
			return;
		}


		try {
			// Datei-Informationen ermitteln
			$joometFile = new LanguageFileItem($pathToFile, '');

			// HTTP-Header setzen
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $joometFile->label . '"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . $joometFile->size);
			header('Cache-Control: must-revalidate');
			header('Pragma: public');

			// Ausgabe puffern und Datei ausgeben
			ob_clean();
			flush();
			readfile($pathToFile);

			// Beenden, damit Joomla keine zusätzlichen Inhalte ausgibt
			$app->close();
		} catch (Exception $e) {
			$app->enqueueMessage("An error occurred while trying to download the file: " . $e->getMessage(), "error");
			$this->setRedirect('index.php?option=com_joomet&view=check');
		}
	}

	public function handleDownloadReportClicked():void
	{
		// Security check: Allow access only for logged-in users
		try{
			$app = Factory::getApplication();
		}catch (Exception $e) {
			error_log('Error retrieving application data: ' . $e->getMessage());
			return;
		}

		$user = $app->getIdentity();

		if(!$user->authorise("download_report", "com_joomet")){
			$app->enqueueMessage("You are not allowed to download the report.", "error");
		}


		$rows = $app->getSession()->get('rowsReportData', []);
		$statistics = $app->getSession()->get('fileStatsData', []);
		$filenameChecks = $app->getSession()->get('fileNameChecksData', []);

		if (empty($rows)) {
			$app->enqueueMessage(Text::_("COM_JOOMET_NO_DATA_FOR_REPORT"), 'error');
			$app->redirect(Route::_('index.php?option=com_joomet', false));
			return;
		}

		$html = $this->buildReportHtml($rows, $statistics, $filenameChecks);

		// HTML als Download anbieten
		$fileName = 'joomet_checker_report.html';
		// HTTP-Header für Datei-Download setzen
		header('Content-Type: application/octet-stream'); // Alternativ: 'text/html'
		header('Content-Disposition: attachment; filename=' . $fileName);
		header('Content-Length: ' . strlen($html));
		header('Cache-Control: private'); // Optionale Sicherheitsmaßnahme

		// HTML-Inhalt ausgeben
		echo $html;

		// Abort to stop running Joomla process
		$app->close();

		// Redirect
		$app->redirect(Route::_('index.php?option=com_joomet&view=check', false));

	}

	private function buildReportHtml($rows, $statistics, $filenameChecks):string
	{
		// Generieren des HTML-Reports
		$html = '<html><head><title>Joomet Checker Report</title><style>*{font-family: Arial, sans-serif;}</style></head><body>';
		$html .= "<div style='margin-bottom: 20px; padding: 4px;'><b>This Report was generated by Joomet - Joomla! Language File Checker and Translator.</b><br></div>";
		$html .= "<div style='border:1px solid grey; padding: 4px;'><b>Statistics:</b><br>";
		$html .= "File Name: " . $statistics['file_name'] . "<br>";
		if ($statistics['uploaded']){
			$html .= "Uploaded: " . HTMLHelper::date($statistics['uploaded'], "DATE_FORMAT_LC5") . "<br>";
		}
		$html .= "Total Rows: " . $statistics['total'] . "<br>";
		$html .= "</div>";
		foreach ($rows as $row) {
			if( $row->errors || $row->warnings || $row->infos)
			{
				$html .= "<div style='margin-top: 20px;'>";
				$html .= "<div>#########################################################</div>";
				$html .= "<div>Row: " . $row->rowNum . "</div>";
				$html .= "<div>Content: " . htmlspecialchars($row->original, false, "UTF-8") . "</div>";
				$html .= "<div style='margin-top: 10px;'><b>Information:</b><br>";
				$html .= "<ol>";
				foreach ($row->infos as $info)
				{
					$html .= "<li>";
					$html .= $info->message . "<br>";
					if ($info->link)
					{
						$html .= "Learn more: <a target='_blank' href='" . $info->link . "'>here</a><br>";
					}
					$html .= "</li>";
				}
				$html .= "</ol>";
				$html .= "</div>";
				$html .= "<div><b>Warnings:</b><br>";
				$html .= "<ol>";
				foreach ($row->warnings as $warn)
				{
					$html .= "<li>";
					$html .= $warn->message . "<br>";
					if ($warn->link)
					{
						$html .= "Learn more: <a target='_blank' href='" . $warn->link . "'>here</a><br>";
					}
					$html .= "</li>";
				}
				$html .= "</ol>";
				$html .= "</div>";
				$html .= "<div><b>Errors:</b><br>";
				$html .= "<ol>";
				foreach ($row->errors as $err)
				{
					$html .= "<li>";
					$html .= $err->message . "<br>";
					if ($err->link)
					{
						$html .= "Learn more: <a target='_blank' href='" . $err->link . "'>here</a><br>";
					}
					$html .= "</li>";
				}
				$html .= "</ol>";
				$html .= "</div>";

				$html .= "</div>";
			}
		}

		$html .= '</body></html>';

		return $html;
	}
}
