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

use Joomla\CMS\Form\FormField;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;

class DeeplKeyCheckField extends FormField
{

	protected $type = 'deeplkeycheck';

	public function getInput()
	{
		$layout = new FileLayout('deepl_key_check_template', __DIR__ . '/layouts');
		return $layout->render();
	}

}