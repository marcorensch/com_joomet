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

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use JUri;

class JoometHelper extends ComponentHelper
{
	public static function getComponentVersion()
	{
		// Get the Version from the manifest file.
		$manifest = Installer::parseXMLInstallFile(JPATH_ADMINISTRATOR . '/components/com_joomet/joomet.xml');

		// Return the version.
		return $manifest['version'];
	}

	public static function checkIfLocalFileExists(string $fileName): bool|string
	{
		$app = Factory::getApplication();

		// cancel if there is no file info
		if (empty($fileName))
		{
			$app->enqueueMessage(Text::_('COM_JOOMET_MSG_NO_FILE_DATA'), 'error');

			return false;
		}

		$path = JPATH_ROOT . '/media/com_joomet/uploads/' . $fileName;

		if (!file_exists($path))
		{
			$app->enqueueMessage(Text::sprintf('COM_JOOMET_MSG_FILE_NOT_FOUND', $fileName), 'error');

			return false;
		}

		return $path;
	}

	/**
	 * Loads the content of a given file and returns each row as array
	 *
	 * @param $path string  The path of the file to load the data from
	 *
	 * @return array Array that contains every row as element
	 *
	 *
	 * @since 1.0.0
	 */
	public static function getFileContents(string $path): array
	{
		$file = fopen($path, 'r');
		if ($file === false)
		{
			throw new \RuntimeException("Die Datei konnte nicht geöffnet werden.");
		}

		$lines = [];

		// Read File row
		while (($line = fgets($file)) !== false)
		{
			$line = trim($line); // Leerzeichen und Zeilenumbrüche entfernen

			$lines[] = $line;
		}

		fclose($file); // Datei schließen

		return $lines;

	}

	public static function prepareExtensionData($element)
	{
		$element->ini_name = str_contains($element->name, ' ') ? strtolower($element->element) : strtolower($element->name);

		$requiredPrefixes = ['component' => 'com_', 'module' => 'mod_', 'plugin' => 'plg_', 'template' => 'tpl_', 'library' => 'lib_'];
		foreach ($requiredPrefixes as $prefix){
			if (str_starts_with($element->ini_name, $prefix)) {
				return $element;
			}
		}

		// Does not start with a prefix - set prefix manually
		$element->ini_name = $requiredPrefixes[$element->type] . $element->name;

		return $element;
	}

}