<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\View\Edit;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use NXD\Component\Joomet\Administrator\Helper\NxdCustomToolbarButton;
use NXD\Component\Joomet\Administrator\Model\EditModel;
use NXD\Component\Joomet\Administrator\Model\SponsorModel;

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
		/** @var EditModel $model */
		$model      = $this->getModel();
		$errors     = $this->get('Errors');
		$this->file = $this->get('File');

		if (count($errors))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		if (!is_readable($this->file->path))
		{
			throw new Exception('Die Datei ist nicht lesbar: ' . $this->file->path);
		}

		// Dateiinhalt sicher laden
		$this->fileContent = @file_get_contents($this->file->path);

		if ($this->fileContent === false)
		{
			throw new Exception(Text::sprintf('COM_JOOMET_FILE_NOT_READABLE', $this->file->path));
		}

		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
		$wa->useStyle('com_joomet.admin.css');
		$wa->useScript('com_joomet.admin.editor.util');

		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', false);

		$user    = Factory::getApplication()->getIdentity();
		$toolbar = $this->getDocument()->getToolbar();

		ToolbarHelper::back();

		// Spacer
		ToolbarHelper::divider();

		// Save
		ToolbarHelper::apply("edit.apply");
		ToolbarHelper::save("edit.save");
		ToolbarHelper::cancel("edit.cancel");

		$hasMSAutoSet = false;

		if ($user->authorise('core.admin', 'com_joomet') || $user->authorise('core.options', 'com_joomet'))
		{
			$toolbar->preferences('com_joomet');
			$hasMSAutoSet = true;
		}

		$alt        = "Support Joomet";
		$classes = ($hasMSAutoSet ? 'ms-auto ' : '') . "btn-success nxd-support-btn";
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

		ToolbarHelper::title(Text::_('COM_JOOMET_TOOLBAR_TITLE_EDIT'), 'fas fa-file-edit');


	}
}
