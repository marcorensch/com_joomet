<?php
/**
 * @package     NXD\Module\NCE\Site\Field
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace NXD\Component\Joomet\Administrator\Field;

use Joomla\CMS\Form\Field\PasswordField;
use Joomla\Registry\Registry;
use NXD\Module\NCE\Site\Helper\PasswordHelper;

class NxdSecuredField extends PasswordField
{

	protected $type = 'nxdsecured';

	public function getInput(): string
	{
		$this->value = (new PasswordHelper)->decrypt($this->value);
		return parent::getInput();
	}

	public function filter($value, $group = null, ?Registry $input = null): string
	{
		return (new PasswordHelper)->encrypt($value);
	}
}