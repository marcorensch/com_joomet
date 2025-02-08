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

use DeepL\DeepLClient;
use DeepL\DeepLException;
use DeepL\Usage;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Layout\FileLayout;
use NXD\Component\Joomet\Administrator\Helper\PasswordHelper;

require_once JPATH_ADMINISTRATOR . '/components/com_joomet/vendor/autoload.php';

class DeeplKeyCheckField extends FormField
{

	protected $type = 'deeplkeycheck';

	public function getInput():string
	{
		$attributes = $this->element->attributes();
		$keyField = isset($attributes['keyField']) ? (string) $attributes['keyField'] : null;
		if(!$keyField) return "";

		$keyFieldValue = $this->form->getValue($keyField);
		if(!$keyFieldValue) return "";

		$decryptedKey = $this->decodeKey($keyFieldValue);
		$usage = $this->getKeyUsage($decryptedKey);
		if(!$usage){
			return "";
		};

		$layout = new FileLayout('deepl_key_check_template', __DIR__ . '/layouts');
		return $layout->render(['usage' => $usage]);
	}

	private function decodeKey(string $key):string
	{
		return (new PasswordHelper)->decrypt($key);
	}

	private function getKeyUsage(string $authKey):false | Usage
	{
		try
		{
			$deeplClient = new DeepLClient($authKey);
			return $deeplClient->getUsage();
		}catch (DeepLException $e){
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return false;
	}

}