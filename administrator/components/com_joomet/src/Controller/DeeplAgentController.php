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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Session\Session;
use NXD\Component\Joomet\Administrator\Helper\JoometHelper;

class DeeplAgentController extends BaseController
{
	public function getLanguagesFromDeepl()
	{
		Session::checkToken('get') or die('Invalid Session Token');

		echo json_encode(['success' => false, 'message' => 'Zwischenmeldung']);

		// get Params Settings
		$apiKey = JoometHelper::getDeeplApiKey();
		error_log($apiKey);
		if (!$apiKey) {
			echo json_encode(['success' => false, 'message' => 'No API Key set']);
			Factory::getApplication()->close();
		}

		// Beispiel: Machen Sie hier etwas mit Ihrer Methode
		$languages = ['EN', 'DE', 'FR']; // Diese Daten können Sie von Deepl laden
		echo json_encode(['success' => true, 'languages' => $languages]);

		// Wichtig: Terminate sofort, da Joomla sonst die komplette Ausgabe generiert
		Factory::getApplication()->close();
	}
}