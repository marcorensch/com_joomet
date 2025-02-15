<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\View\Source;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use NXD\Component\Joomet\Administrator\Model\DashboardModel;
use NXD\Component\Joomet\Administrator\Helper\NxdCustomToolbarButton;
use NXD\Component\Joomet\Administrator\Model\SourceModel;

/**
 * View class Joomet Dashboard.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{

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
		/** @var SourceModel $model */
		$model         = $this->getModel();
		$this->sources = $model->getSources();
		$this->target  = $model->getTargetView();

		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
		$wa->useStyle('com_joomet.admin.css');

		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', false);

		$languageString = Text::_('COM_JOOMET_TOOLBAR_TITLE_SOURCE');
		if ($this->target === "check")
		{
			$languageString = Text::_('COM_JOOMET_TOOLBAR_TITLE_SOURCE_FOR_CHECKER');
			$switchButton   = new NxdCustomToolbarButton(
				"COM_JOOMET_SOURCE_SWITCH_TO_TRANSLATION",
				"/administrator/index.php?option=com_joomet&view=source&target=translations",
				"_self",
				"btn-primary",
				"fas fa-shuffle"
			);
			$switchAlt      = Text::_('COM_JOOMET_TOOLBAR_TITLE_SOURCE_FOR_TRANSLATION');
		}
		elseif ($this->target === "translations")
		{
			$languageString = Text::_('COM_JOOMET_TOOLBAR_TITLE_SOURCE_FOR_TRANSLATION');
			$switchButton   = new NxdCustomToolbarButton(
				"COM_JOOMET_SOURCE_SWITCH_TO_CHECKER",
				"/administrator/index.php?option=com_joomet&view=source&target=check",
				"_self",
				"btn-primary",
				"fas fa-shuffle"
			);
			$switchAlt      = Text::_('COM_JOOMET_TOOLBAR_TITLE_SOURCE_FOR_CHECKER');
		}

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
		$toolbar->appendButton('Custom', $switchButton->getHtml(), $switchAlt);

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

		ToolbarHelper::help('', false, "https://manuals.nx-designs.com/docs/com_joomet/source_selection");

		ToolbarHelper::title($languageString, 'fas fa-language');
	}
}
