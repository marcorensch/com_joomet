<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\View\Localextensions;

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
use NXD\Component\Joomet\Administrator\Model\LocalextensionsModel;
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
		/** @var LocalextensionsModel $model */
		$model               = $this->getModel();
		$this->targetView    = $this->get('TargetView');
		$this->pagination    = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->extensions    = $this->get("Items");
		$this->state         = $this->get('State');

		$input        = Factory::getApplication()->input;
		$this->target = $input->get('target', '', 'string');

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

		ToolbarHelper::title(Text::_('COM_JOOMET_TOOLBAR_TITLE_LOCALEXTENSIONS'), 'fas fa-language');


		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_joomet');
	}
}
