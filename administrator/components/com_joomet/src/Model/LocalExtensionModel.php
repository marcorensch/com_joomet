<?php
/**
 * @package     NXD\Component\Joomet\Administrator\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace NXD\Component\Joomet\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\Database\DatabaseInterface;
use Joomla\CMS\Filesystem\Folder;

//@ToDo Check Compatibility 6.0
use Joomla\Filesystem\Path;
use NXD\Component\Joomet\Administrator\Helper\JoometHelper;
use NXD\Component\Joomet\Administrator\Helper\LocalExtensionLanguageFileItem;

class LocalExtensionModel extends BaseModel
{
	public $typeAlias = 'com_joomet.localextension';

	public function getTargetView(): string
	{
		// Get the URL Parameter for the task
		return trim(Factory::getApplication()->input->get('target', '', 'string'));
	}

	public function getLanguageFilesForExtension(\stdClass $extension): array
	{
		$files                   = array();
		$files['site']           = $this->getLanguageFilesFrontend($extension);
		$files['administration'] = $this->getLanguageFilesBackend($extension);

		return $files;
	}


	private function getLanguageFilesFrontend(\stdClass $extension): array
	{
		$extensionPath          = $this->buildPathToExtension(JPATH_ROOT, $extension);
		$joomlaLocationFiles    = $this->scanLanguageFolders(JPATH_ROOT, $extension, 'joomla');
		$extensionLocationFiles = $this->scanLanguageFolders($extensionPath, $extension, 'extension');
		$files                  = array_merge($joomlaLocationFiles, $extensionLocationFiles);

		return $files;
	}

	private function getLanguageFilesBackend(\stdClass $extension): array
	{
		$extensionPath          = $this->buildPathToExtension(JPATH_ADMINISTRATOR, $extension);
		$joomlaLocationFiles    = $this->scanLanguageFolders(JPATH_ADMINISTRATOR, $extension, 'joomla');
		$extensionLocationFiles = $this->scanLanguageFolders($extensionPath, $extension, 'extension');
		$files                  = array_merge($joomlaLocationFiles, $extensionLocationFiles);

		return $files;
	}

	private function scanLanguageFolders(string $path, \stdClass $extension, string $src): array
	{
		$languageSubDirs = array("language", "languages", "lang", "langs");
		$files           = [];
		foreach ($languageSubDirs as $languageSubDir)
		{
			$subPath = Path::clean($path . '/' . $languageSubDir);

			if (!Folder::exists($subPath))
			{
				continue;
			}
			$folders = Folder::folders($subPath);
			foreach ($folders as $folder)
			{
				$folderPath           = Folder::makeSafe($subPath . '/' . $folder);
				$iniFiles             = Folder::files($folderPath, '^' . preg_quote($extension->ini_name, '/') . '.*\.ini$', false, true);
				$preparedFileElements = array();
				foreach ($iniFiles as $iniFile)
				{
					$preparedFileElements[] = new LocalExtensionLanguageFileItem($iniFile, $src);
				}

				$files = array_merge($files, $preparedFileElements);
			}
		}

		return $files;
	}

	public function getExtension(string $element)
	{
		$db    = Factory::getContainer()->get(DatabaseInterface::class);
		$query = $db->getQuery(true);
		$query->select('extension_id, name, type, element, folder, client_id, locked, protected')
			->from('#__extensions')
			->where('element = ' . $db->quote($element));
		$db->setQuery($query);

		$element = $db->loadObject();
		return JoometHelper::prepareExtensionData($element);
	}

	private function buildPathToExtension(string $basePath, \stdClass $extension): string
	{
		$pathPartType = "";
		if ($extension->type === "component")
		{
			$pathPartType = "components/";
		}
		elseif ($extension->type === "module")
		{
			$pathPartType = "modules/";
		}
		elseif ($extension->type === "plugin")
		{
			$pathPartType = "plugins/";
		}
		elseif ($extension->type === "template")
		{
			$pathPartType = "templates/";
		}

		return Path::clean($basePath . "/" . $pathPartType . $extension->folder . "/" . $extension->element);
	}

}