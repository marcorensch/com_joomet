<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\Controller;

defined('_JEXEC') or die;

use DeepL\DeepLClient;
use DeepL\DeepLException;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Session\Session;
use NXD\Component\Joomet\Administrator\Helper\JoometHelper;

require_once JPATH_ADMINISTRATOR . '/components/com_joomet/vendor/autoload.php';

class DeeplAgentController extends BaseController
{
	/**
	 * Method to get the Source and Target Languages from the DeepL API
	 * Requires a DeepL API key stored in the component configuration
	 *
	 * @throws DeepLException
	 *
	 * @since 1.0
	 */
	public function getLanguagesFromDeepl()
	{
		Session::checkToken('get') or die('Invalid Session Token');

		// get Params Settings
		$apiKey = JoometHelper::getDeeplApiKey();
		if (!$apiKey)
		{
			echo json_encode(['success' => false, 'message' => 'No API Key set']);
			Factory::getApplication()->close();
		}

		$deeplClient = new DeepLClient($apiKey);
		$languages = [];
		$languages['source'] = $deeplClient->getSourceLanguages();
		$languages['target'] = $deeplClient->getTargetLanguages();

		echo json_encode(['success' => true, 'languages' => $languages]);

		Factory::getApplication()->close();
	}
}