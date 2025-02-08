<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

require_once JPATH_ADMINISTRATOR . '/components/com_joomet/vendor/autoload.php';

class DeeplLanguageCacheField extends FormField
{
	protected $type = 'deepllanguagecache';

	protected $value;

	protected $sourceLanguages = "";
	protected $targetLanguages = "";

	public function getInput():string
	{
		$this->includeAssets();
		$this->setLanguagesForPreview();
		$attributes = $this->element->attributes();
		$keyField = isset($attributes['keyField']) ? (string) $attributes['keyField'] : null;
		if(!$keyField) return "";

		$keyFieldValue = $this->form->getValue($keyField);
		$disabled = $keyFieldValue ? '' : 'disabled';


		$btn = "<button $disabled id='get-languages-btn' class='btn btn-primary w-100'><i class='fas fa-rotate'></i><span class='d-inline-block ms-3'>".Text::_('COM_JOOMET_PARAMS_DEEPL_LANGUAGE_UPDATE_LABEL')."</span></button>";
		$source = "<label for='source'>".Text::_('COM_JOOMET_PARAMS_DEEPL_LANGUAGE_SUPPORTED_SRC_LABEL')."</label><textarea id='source' class='form-control' rows='5' readonly>".$this->sourceLanguages."</textarea>";
		$target = "<label for='target'>".Text::_('COM_JOOMET_PARAMS_DEEPL_LANGUAGE_SUPPORTED_TRG_LABEL')."</label><textarea id='target' class='form-control' rows='5' readonly>".$this->targetLanguages."</textarea>";
		$value = '<textarea hidden data-deepl-languages-field name="'.$this->name.'" id="'.$this->id.'" class="form-control" rows="5">'.$this->value.'</textarea>';
		return "<div><div class='row align-items-end g-2'><div class='col-12 col-xl-4'>$source</div><div class='col-12 col-xl-4'>$target</div><div class='col-12 col-xl-4'>$btn</div></div></div>" . $value;
	}

	private function includeAssets(): void
	{
		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
		$wa->useScript('jquery');
		$wa->registerAndUseScript('joomet.deepl.ajax.calls', Uri::root() . 'media/com_joomet/js/admin-joomet-ajax.js'); // useScript does not work here
	}

	private function setLanguagesForPreview():void
	{
		if(!trim($this->value)) return;
		$languages = json_decode($this->value, true);
		$i = 0;
		foreach($languages['source'] as $srcLang){
			$this->sourceLanguages .= $srcLang['name'] . " (".$srcLang['code'].")";
			if($i < count($languages['source']) - 1) $this->sourceLanguages .= "\n";
			$i++;
		}
		$i = 0;
		foreach($languages['target'] as $trgLang){
			$this->targetLanguages .= $trgLang['name'] . " (".$trgLang['code'].")";
			if($i < count($languages['target']) - 1) $this->targetLanguages .= "\n";
			$i++;
		}
	}


}