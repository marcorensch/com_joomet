<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\Model;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseModel;
use NXD\Component\Joomet\Administrator\Helper\JoometHelper;
use NXD\Component\Joomet\Administrator\Helper\DashboardItem;

class DashboardModel extends BaseModel
{

	public function getItems():array
	{

		// Reset User State
		Factory::getApplication()->setUserState('com_joomet.file', null);
		Factory::getApplication()->setUserState('com_joomet.context', null);

		$items = array();

		$items[] = new DashboardItem(Text::_("COM_JOOMET_DASHBOARD_CHECK"), "fas fa-file-circle-check", "index.php?option=com_joomet&view=source&target=check");
		$items[] = new DashboardItem(Text::_("COM_JOOMET_DASHBOARD_TRANSLATE"), "fas fa-file", "index.php?option=com_joomet&view=source&target=translations");
		$items[] = new DashboardItem(Text::_("COM_JOOMET_SOURCE_UPLOADED_TXT"), "fas fa-folder-open", "index.php?option=com_joomet&view=uploaded");
		$items[] = new DashboardItem(Text::_("COM_JOOMET_DASHBOARD_SPONSOR"), "fas fa-heart", "index.php?option=com_joomet&view=sponsor");

		return $items;

	}

	public function getComponentVersion(){
		return JoometHelper::getComponentVersion();
	}

	public function apiKeyIsSet(): bool
	{
		return JoometHelper::getDeeplApiKey() !== null;
	}
}