<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\View\Uploaded;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use NXD\Component\Joomet\Administrator\Model\CheckModel;
use NXD\Component\Joomet\Administrator\Helper\NxdCustomToolbarButton;
use NXD\Component\Joomet\Administrator\Model\UploadedModel;

/**
 * View class Joomet Check.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
	protected $form;
	protected string $task;

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
		/** @var UploadedModel $model */
		$model            = $this->getModel();
		$this->form       = $this->get('Form');
		$this->targetView = $this->get('TargetView');
		$errors           = $this->get('Errors');
		$this->items      = $model->getItems();

		if (count($errors))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
		$wa->useStyle('com_joomet.admin.css');

		$this->addToolbar();

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
		Factory::getApplication()->input->set('hidemainmenu', false);

		$user    = Factory::getApplication()->getIdentity();
		$toolbar = $this->getDocument()->getToolbar();

		$dashboardBtn = new NxdCustomToolbarButton(
			"COM_JOOMET_DASHBOARD_BTN_TXT",
			"/administrator/index.php?option=com_joomet&view=dashboard",
			"_self",
			"btn-primary",
			"fas fa-grip-horizontal"
		);

		$toolbar->appendButton('Custom', $dashboardBtn->getHtml(), Text::_('COM_JOOMET_DASHBOARD_BTN_TXT'));

		if($user->authorise("core.create", "com_joomet"))
		{
			$reUploadBtnHtml = new NxdCustomToolbarButton(
				"COM_JOOMET_UPLOAD_BTN_TXT",
				"/administrator/index.php?option=com_joomet&view=upload&target=uploaded",
				"_self",
				"btn-primary",
				"fas fa-file-upload"
			);
			$toolbar->appendButton('Custom', $reUploadBtnHtml->getHtml(), 'upload');
		}

		if($user->authorise("core.delete", "com_joomet"))
		{
			ToolbarHelper::deleteList(Text::_('COM_JOOMET_DELETE_ITEMS_TXT'), 'uploaded.handleTrashClicked', 'JTOOLBAR_DELETE');
		}

		$hasMSAutoSet = false;
		if ($user->authorise('core.admin', 'com_joomet') || $user->authorise('core.options', 'com_joomet'))
		{
			$toolbar->preferences('com_joomet');
			$hasMSAutoSet = true;
		}

		$alt        = "Support Joomet";
		$classes    = (!$hasMSAutoSet ? 'ms-auto ' : '') . "btn-success nxd-support-btn";
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

		ToolbarHelper::title(Text::_('COM_JOOMET_TOOLBAR_TITLE_UPLOADED'), 'fas fa-language');


		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_joomet');
	}
}
