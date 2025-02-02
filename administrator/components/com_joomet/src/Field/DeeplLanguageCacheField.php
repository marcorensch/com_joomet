<?php
/**
 * @package     NXD\Component\Joomet\Administrator\Field
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace NXD\Component\Joomet\Administrator\Field;

defined('_JEXEC') or die;

use DeepL\DeepLClient;
use DeepL\DeepLException;
use DeepL\Usage;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Uri\Uri;
use NXD\Component\Joomet\Administrator\Helper\PasswordHelper;

require_once JPATH_ADMINISTRATOR . '/components/com_joomet/vendor/autoload.php';

class DeeplLanguageCacheField extends FormField
{
	protected $type = 'deepllanguagecache';

	public function getInput():string
	{
		$this->includeAssets();
		$btn = "<button id='get-languages-btn' class='btn btn-primary w-100'><i class='fas fa-rotate'></i><span class='d-inline-block ms-3'>".Text::_('COM_JOOMET_PARAMS_DEEPL_LANGUAGE_UPDATE_LABEL')."</span></button>";
		$from = "<label for='from'>".Text::_('COM_JOOMET_PARAMS_DEEPL_LANGUAGE_SUPPORTED_SRC_LABEL')."</label><textarea id='from' class='form-control' rows='5' readonly></textarea>";
		$to = "<label for='to'>".Text::_('COM_JOOMET_PARAMS_DEEPL_LANGUAGE_SUPPORTED_TRG_LABEL')."</label><textarea id='to' class='form-control' rows='5' readonly></textarea>";
		$html = "<div><div class='row align-items-end g-2'><div class='col-12 col-xl-4'>$from</div><div class='col-12 col-xl-4'>$to</div><div class='col-12 col-xl-4'>$btn</div></div></div>";
		return $html;
	}

	public function getLanguagesFromDeepl(){

	}

	private function includeAssets(): void
	{
		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
		$wa->useScript('jquery');
		$wa->registerAndUseScript('joomet.deepl.ajax.calls', Uri::root() . 'media/com_joomet/js/admin-joomet-ajax.js'); // useScript does not work here
	}


}