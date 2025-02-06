<?php
/**
 * @package     NXD\Component\Joomet\Administrator\Field
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace NXD\Component\Joomet\Administrator\Field;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_joomet/vendor/autoload.php';

class DeeplLanguageSelectionField extends ListField
{
	protected $type = 'DeeplLanguageSelection';
	protected $context = "";
	private bool $addScript;

	private array $languages;

	public function getOptions(): array
	{
		$options = array();
		$this->context = $this->getAttribute('context');
		$this->addScript = $this->getAttribute('addScript') === "true";
		$languageOptions = $this->getLanguagesFromConfig();

		if($this->addScript){
			$this->addScriptOptions();
		}

		if($this->context === "source")
		{
			$options[] = HTMLHelper::_('select.option', 'auto', Text::_('COM_JOOMET_FIELD_SOURCE_LANGUAGE_OPT_AUTO'));
		}
		$options = array_merge($options, parent::getOptions());
		$options = array_merge($options, $languageOptions);

		return $options;
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
			foreach($this->languages as $language){
				$options[] = HTMLHelper::_('select.option', $language->code, $language->name . " (".$language->code.")");
			}
		}
		return $options;
	}

	private function addScriptOptions():void
	{
		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
		$jsObject = array();
		foreach ($this->languages as $lang){
			$jsObject[$lang->code] = $lang->supportsFormality;
		}
		$script = "const formalitySupportTable = ".json_encode($jsObject).";";
		$wa->addInlineScript($script);
	}
}