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

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Model\BaseModel;

/**
 * Joomet Check controller class.
 *
 * @since  1.0.0
 */
class TranslationsController extends BaseController
{
	public function getModel($name = 'Translations', $prefix = 'Administrator', $config = ['ignore_request' => true]): BaseModel
	{
		return parent::getModel($name, $prefix, $config);
	}
}