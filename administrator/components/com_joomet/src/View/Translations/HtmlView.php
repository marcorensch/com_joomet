<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\View\Translations;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use NXD\Component\Joomet\Administrator\Model\TranslationsModel;

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
		/** @var TranslationsModel $model */
		$model          = $this->getModel();
		$this->form     = $model->getForm();
		$errors         = $model->getErrors();
		$this->fileData = $model->getFileContents();
		$this->fileName = $model->getFileName();
		$configValid    = $model->checkComponentConfig();

		if (count($errors))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
		$wa->useStyle('com_joomet.admin.css');
		$wa->useScript('com_joomet.admin.translation');

		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', false);

		ToolbarHelper::back();

		ToolbarHelper::inlinehelp();
		ToolbarHelper::help('', false, "https://manuals.nx-designs.com/docs/com_joomet/translator/do_translation");

		ToolbarHelper::title(Text::_('COM_JOOMET_TOOLBAR_TITLE_TRANSLATIONS'), 'fas fa-language');


	}
}
