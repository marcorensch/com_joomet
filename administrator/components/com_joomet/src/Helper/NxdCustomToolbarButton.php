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


// URL Fallback Const
use Joomla\CMS\Language\Text;

const HELP_URL = "https://manuals.nx-designs.com/docs/intro";

class NxdCustomToolbarButton
{
	private string $url;
	private string $text;

	private string $target;
	private string $classes;
	private string $iconClasses;

	public function __construct(
		string $text="",
		string $url = "",
		$target = "_blank",
		$classes="btn-primary nxd-ext-btn nxd-help-btn",
		string $iconClasses = "fas fa-question"
	)
	{
		$this->text   = $text ? Text::_($text) : Text::_("JHELP");
		$this->url    = $url ?: HELP_URL;
		$this->target = $target;
		$this->classes = $classes;
		$this->iconClasses = $iconClasses;
	}

	public function getHtml(): string
	{
		return '<joomla-toolbar-button><a title="'.$this->text.'" href="' . $this->url . '" class="btn '.$this->classes.'" target="' . $this->target . '"><span class="'.$this->iconClasses.'"></span>' . $this->text . '</a></joomla-toolbar-button>';
	}

}