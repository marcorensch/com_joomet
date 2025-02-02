<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\View\LocalExtension;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use NXD\Component\Joomet\Administrator\Helper\NxdCustomToolbarButton;
use NXD\Component\Joomet\Administrator\Model\LocalExtensionModel;

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
		/** @var LocalExtensionModel $model */
		$model               = $this->getModel();
		$this->targetView    = $this->get('TargetView');
		$element             = Factory::getApplication()->input->get('element', '', 'string');
		$this->extension     = $model->getExtension($element);
		$this->languageFiles = array();

		if (!$this->extension)
		{
			Factory::getApplication()->enqueueMessage(Text::sprintf('COM_JOOMET_EXTENSION_NOT_FOUND_IN_DB', $element), 'error');

			return;
		}

		$this->languageFiles = $model->getLanguageFilesForExtension($this->extension);

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
		Factory::getApplication()->setUserState('com_joomet.context', "joomla");

		$user    = Factory::getApplication()->getIdentity();
		$toolbar = $this->getDocument()->getToolbar();

		$customBack = new NxdCustomToolbarButton(
			"COM_JOOMET_LIST_BTN_TXT",
			"/administrator/index.php?option=com_joomet&view=localextensions",
			"_self",
			"btn-primary",
			"fas fa-chevron-left"
		);

		$toolbar->appendButton('Custom', $customBack->getHtml(), Text::_('COM_JOOMET_LIST_BTN_TXT'));

		$dashboardBtn = new NxdCustomToolbarButton(
			"COM_JOOMET_DASHBOARD_BTN_TXT",
			"/administrator/index.php?option=com_joomet&view=dashboard",
			"_self",
			"btn-primary",
			"fas fa-grip-horizontal"
		);

		$toolbar->appendButton('Custom', $dashboardBtn->getHtml(), Text::_('COM_JOOMET_DASHBOARD_BTN_TXT'));

		$hasMSAutoSet = false;
		if ($user->authorise('core.admin', 'com_joomet') || $user->authorise('core.options', 'com_joomet'))
		{
			$toolbar->preferences('com_joomet');
			$hasMSAutoSet = true;
		}

		$alt        = Text::_('COM_JOOMET_SUPPORT_PROJECT_TXT');
		$classes    = (!$hasMSAutoSet ? 'ms-auto ' : '') . "btn-success nxd-support-btn";
		$supportBtn = new NxdCustomToolbarButton(
			"COM_JOOMET_SUPPORT_US_BTN_TXT",
			"/administrator/index.php?option=com_joomet&view=sponsor",
			"_self",
			$classes,
			"fas fa-heart"
		);
		$toolbar->appendButton('Custom', $supportBtn->getHtml(), $alt);

		$alt   = Text::_('COM_JOOMET_HELP_TXT');
		$dhtml = (new NxdCustomToolbarButton())->getHtml();
		$toolbar->appendButton('Custom', $dhtml, $alt);

		ToolbarHelper::title(Text::sprintf('COM_JOOMET_TOOLBAR_TITLE_LOCALEXTENSION', $this->extension->element), 'fas fa-language');


		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_joomet');
	}
}
