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

use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

class LanguageFileItem
{
	public string $name;
	public string $label;
	public string|null $timestamp;

	public string $path;
	public string $relative_path;
	public string $url;
	public string $src;

	public string $languageTag;

	public int $size;

	public function __construct(string $path, string $src)
	{
		$this->name = $this->setName($path);
		[$this->label, $this->timestamp] = $this->setLabelAndTimestampFromName($this->name);
		$this->path = $path;
		$this->relative_path = $this->setRelativePath($path);
		$this->url = $this->setUrl($path);
		$this->src = $src;
		$this->languageTag = $this->setLanguageTag($path);
		$this->size = filesize($path);
	}

	private function setLabelAndTimestampFromName(string $fileName):array
	{
		$parts     = explode('.', $fileName);
		$timestamp = intval($parts[0]) === 0 ? null : $parts[0];
		$name      = implode('.', array_slice($parts, 1));

		return [$name, $timestamp];
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

	private function setLanguageTag(string $path)
	{
		$pathElements = explode("/", $path);
		$languageTag = $pathElements[count($pathElements) - 2];
		return $languageTag;
	}
}