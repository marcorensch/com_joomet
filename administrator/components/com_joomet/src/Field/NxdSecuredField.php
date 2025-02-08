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

use Joomla\CMS\Form\Field\PasswordField;
use Joomla\Registry\Registry;
use NXD\Component\Joomet\Administrator\Helper\PasswordHelper;

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