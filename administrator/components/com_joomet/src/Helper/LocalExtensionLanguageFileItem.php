<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\Helper;

use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

class LocalExtensionLanguageFileItem
{
	public string $name;
	public string $path;
	public string $relative_path;
	public string $url;
	public string $src;

	public function __construct(string $path, string $src)
	{
		$this->name = $this->setName($path);
		$this->path = $path;
		$this->relative_path = $this->setRelativePath($path);
		$this->url = $this->setUrl($path);
		$this->src = $src;
	}

	private function setName(string $path):string
	{
		return basename($path);
	}

	private function setUrl(string $path):string
	{
		// Remove JPATH_ROOT from string
		$relPath = str_replace(JPATH_ROOT, "", $path);
		// Build an URI
		$uri = Uri::root() . ltrim(Route::_($relPath, false), '/');
		return $uri;
	}

	private function setRelativePath(string $path):string
	{
		return str_replace(JPATH_ROOT, "", $path);
	}
}