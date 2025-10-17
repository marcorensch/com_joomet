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

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

require_once JPATH_ADMINISTRATOR . '/components/com_joomet/vendor/autoload.php';

class DeeplLanguageSelectionField extends ListField
{
	protected $type = 'deeplLanguageSelection';
	protected $context = "";
	private bool $addScript;

	private array $languages;

	public function getOptions(): array
	{
		$options = array();
		$this->context = $this->getAttribute('context');
		$this->addScript = $this->getAttribute('addScript') === "true";
		$languageOptions = $this->getLanguagesFromConfig();

		if($this->context === "source")
		{
			$options[] = HTMLHelper::_('select.option', 'auto', Text::_('COM_JOOMET_FIELD_SOURCE_LANGUAGE_OPT_AUTO'));
		}

		return array_merge($options, parent::getOptions(), $languageOptions);

	}

	private function getLanguagesFromConfig():array
	{
		$componentParams = ComponentHelper::getParams('com_joomet');
		$availableLanguages = $componentParams->get('deepl_language_cache');
		$options = array();
		if(empty($availableLanguages)){
			return $options;
		}
		$languagesCache = json_decode($availableLanguages);
		//check if the property exists
		if(property_exists($languagesCache, $this->context)){
			$this->languages = $languagesCache->{$this->context};

			if($this->addScript){
				$this->addScriptOptions();
			}

			foreach($this->languages as $language){
				$options[] = HTMLHelper::_('select.option', $language->code, $language->name . " (".$language->code.")");
			}
		}
		return $options;
	}

	private function addScriptOptions():void
	{
		try{
			$app = Factory::getApplication();
		}catch (Exception $e) {
			error_log('Error retrieving application data: ' . $e->getMessage());
			return;
		}

		$wa = $app->getDocument()->getWebAssetManager();
		$jsObject = array();
		foreach ($this->languages as $lang){
			$jsObject[$lang->code] = $lang->supportsFormality;
		}
		$script = "const formalitySupportTable = ".json_encode($jsObject).";";
		$wa->addInlineScript($script);
	}
}