<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\View\Check;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Registry\Registry;
use NXD\Component\Joomet\Administrator\Model\CheckModel;
use NXD\Component\Joomet\Administrator\Helper\NxdCustomToolbarButton;

/**
 * View class Joomet Check.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * An array of row items
	 *
	 * @var    array
	 * @since  1.0.0
	 */
	protected $rows = [];

	public Registry $params;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 * @since   1.0.0
	 */
	public function display($tpl = null): void
	{
		/** @var CheckModel $model */
		$model                = $this->getModel();
		$processed            = $model->processFile();
		$this->params         = ComponentHelper::getParams('com_joomet');
		$this->rows           = $processed['data'];
		$this->statistics     = $processed['statistics'];
		$this->filenameChecks = $processed['filenameChecks'];
		$errors               = $this->get('Errors');
		$this->context        = Factory::getApplication()->getUserState('com_joomet.context');


		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
		$wa->useStyle('com_joomet.admin.css');

		$this->addToolbar();

		if (count($errors))
		{
			foreach ($errors as $error)
			{
				Factory::getApplication()->enqueueMessage($error, 'error');
			}
		}


		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 * @since   1.0.0
	 */
	protected function addToolbar()
	{
		$user    = Factory::getApplication()->getIdentity();
		$toolbar = $this->getDocument()->getToolbar();

		ToolbarHelper::back();

		// Dashboard Button
		$dashboardBtn = new NxdCustomToolbarButton(
			"COM_JOOMET_DASHBOARD_BTN_TXT",
			"/administrator/index.php?option=com_joomet&view=dashboard",
			"_self",
			"btn btn-primary",
			"fas fa-grip-horizontal"
		);
		$toolbar->appendButton('Custom', $dashboardBtn->getHtml(), Text::_('COM_JOOMET_DASHBOARD_BTN_TXT'));

		// Upload Button
		$reUploadBtnHtml = new NxdCustomToolbarButton(
			"COM_JOOMET_UPLOAD_BTN_TXT",
			"/administrator/index.php?option=com_joomet&view=upload&target=check",
			"_self",
			"btn btn-primary",
			"fas fa-file-upload"
		);
		$toolbar->appendButton('Custom', $reUploadBtnHtml->getHtml(), 'upload');

		// Download Report Button
		if ($user->authorise("com_joomet.export_report", "com_joomet"))
		{
			$exportBtn = new NxdCustomToolbarButton(
				"COM_JOOMET_DOWNLOAD_REPORT_BTN_TXT",
				"/administrator/index.php?option=com_joomet&task=check.handleDownloadReportClicked",
				"_self",
				"btn btn-primary",
				"fas fa-file-lines"
			);
			$toolbar->appendButton('Custom', $exportBtn->getHtml(), Text::_('COM_JOOMET_DASHBOARD_BTN_TXT'));
		}

		// Refresh Button
		$refreshBtn = new NxdCustomToolbarButton(
			"COM_JOOMET_REFRESH_BTN_TXT",
			"/administrator/index.php?option=com_joomet&view=check",
			"_self",
			"btn btn-primary",
			"icon-refresh",
			"location.reload();"
		);
		$toolbar->appendButton('Custom', $refreshBtn->getHtml(), Text::_('COM_JOOMET_REFRESH_BTN_TXT'));

		// Edit Button if in correct context (custom)
		if($this->context === "custom"){
			$refreshBtn = new NxdCustomToolbarButton(
				"COM_JOOMET_EDIT_BTN_TXT",
				"/administrator/index.php?option=com_joomet&task=check.handleEditFileClicked",
				"_blank",
				"btn btn-primary nxd-ext-btn",
				"icon-edit",
				"location.reload();"
			);
			$toolbar->appendButton('Custom', $refreshBtn->getHtml(), Text::_('COM_JOOMET_EDIT_BTN_TXT'));
		}
		$downloadBtn = new NxdCustomToolbarButton(
			"COM_JOOMET_DOWNLOAD_FILE_BTN_TXT",
			"/administrator/index.php?option=com_joomet&task=check.handleDownloadFileClicked",
			"_self",
			"btn btn-primary",
			"icon-download",
			""
		);
		$toolbar->appendButton('Custom', $downloadBtn->getHtml(), Text::_('COM_JOOMET_DOWNLOAD_FILE_BTN_TXT'));

		$hasMSAutoSet = false;

		if ($user->authorise('core.admin', 'com_joomet') || $user->authorise('core.options', 'com_joomet'))
		{
			$toolbar->preferences('com_joomet');
			$hasMSAutoSet = true;
		}

		$alt        = "Support Joomet";
		$classes    = (!$hasMSAutoSet ? 'ms-auto ' : '') . "btn btn-success nxd-support-btn";
		$supportBtn = new NxdCustomToolbarButton(
			"COM_JOOMET_SUPPORT_US_BTN_TXT",
			"/administrator/index.php?option=com_joomet&view=sponsor",
			"_self",
			$classes,
			"fas fa-heart"
		);

		$toolbar->appendButton('Custom', $supportBtn->getHtml(), $alt);

		$alt   = "Joomet Help";
		$dhtml = (new NxdCustomToolbarButton())->getHtml();
		$toolbar->appendButton('Custom', $dhtml, $alt);

		ToolbarHelper::title(Text::_('COM_JOOMET_TOOLBAR_TITLE_CHECKER'), 'fas fa-language');

	}
}
