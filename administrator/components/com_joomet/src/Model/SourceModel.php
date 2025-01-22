<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Joomet\Administrator\Model;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Registry\Registry;
use NXD\Component\Joomet\Administrator\Helper\CheckerMessage;
use NXD\Component\Joomet\Administrator\Helper\JoometErrorType;
use NXD\Component\Joomet\Administrator\Helper\JoometMessageSource;
use NXD\Component\Joomet\Administrator\Helper\JoometHelper;
use NXD\Component\Joomet\Administrator\Helper\JoometMessageType;
use NXD\Component\Joomet\Administrator\Helper\RowObject;
use NXD\Component\Joomet\Administrator\Helper\RowType;
use NXD\Component\Joomet\Administrator\Helper\SourceItem;

/**
 * Methods supporting a list of joomet records.
 *
 * @since  1.0
 */
class SourceModel extends ListModel
{
	private Registry $params;

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @throws Exception
	 * @since   1.0
	 * @see     \JControllerLegacy
	 */

	public function __construct($config = [])
	{
		$this->params = ComponentHelper::getParams('com_joomet');
		parent::__construct($config);
	}

	public function getSources():array
	{
		$target = $this->getTargetView();
		$user = Factory::getApplication()->getIdentity();

		$sources = array();
		if($user->authorise("core.create", "com_joomet"))
		{
			$sources[] = new SourceItem(Text::_("COM_JOOMET_SOURCE_UPLOAD_TXT"), "fas fa-file-upload", "index.php?option=com_joomet&view=upload&target={$target}");
		}
		$sources[] = new SourceItem(Text::_("COM_JOOMET_SOURCE_UPLOADED_TXT"), "fas fa-folder-open", "index.php?option=com_joomet&view=uploaded&target={$target}");
		$sources[] = new SourceItem(Text::_("COM_JOOMET_SOURCE_LOCAL_TXT"), "fas fa-file-import", "index.php?option=com_joomet&view=local&target={$target}");

		return $sources;
	}

	public function getTargetView():string
	{
		// Get the URL Parameter for the task
		$vt = trim(Factory::getApplication()->input->get('target', '', 'string'));
		if(empty($vt)){
			$vt = 'check';
		}
		return $vt;
	}

}
