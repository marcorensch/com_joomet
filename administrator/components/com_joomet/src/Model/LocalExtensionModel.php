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
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\Model\BaseModel;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\DatabaseQuery;
use Joomla\Database\QueryInterface;
use phpseclib3\Crypt\EC\BaseCurves\Base;

class LocalExtensionModel extends BaseModel
{
	public $typeAlias = 'com_joomet.localextension';

	public function getTargetView():string
	{
		// Get the URL Parameter for the task
		return trim(Factory::getApplication()->input->get('target', '', 'string'));
	}

	public function getLanguageFilesForExtension(string $extension):array
	{
		$files = array();
		$files['frontend'] = $this->getLanguageFilesFrontend($extension);
		$files['backend'] = $this->getLanguageFilesBackend($extension);
		return $files;
	}



	private function getLanguageFilesFrontend(string $extension):array
	{
		$path = JPATH_ROOT . '/language/';
		return $this->scanLanguageFolder($path, $extension);
	}

	private function getLanguageFilesBackend(string $extension):array
	{
		$path = JPATH_ROOT . '/language/';
		return $this->scanLanguageFolder($path, $extension);
	}

	private function scanLanguageFolder(string $path, string $extension):array
	{
		$folders = scandir($path);
		$files = [];
		foreach ($folders as $folder)
		{
			if ($folder === '.' || $folder === '..')
			{
				continue;
			}
			$files = array_merge($files, glob($path . $folder . '/'.$extension.'*.ini'));
		}
		return $files;
	}

}