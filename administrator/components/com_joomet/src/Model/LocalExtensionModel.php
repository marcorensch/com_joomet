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

}