<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\Helper;

defined('_JEXEC') or die;

class SourceItem
{

	public string $title;
	public string $icon;
	public string $link;

	public function __construct(string $title, string $icon, string $link)
	{
		$this->title = $title;
		$this->icon = $icon;
		$this->link = $link;
	}
}