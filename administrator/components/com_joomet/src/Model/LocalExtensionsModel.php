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
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\DatabaseQuery;
use Joomla\Database\QueryInterface;

class LocalExtensionsModel extends ListModel
{
	public $typeAlias = 'com_joomet.localextensions';

	public function __construct($config = [])
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'extension_id', 'a.extension_id',
				'name', 'a.name',
				'type', 'a.type',
				'client_id', 'a.client_id',
				'ordering', 'a.ordering',
			);
		}


		parent::__construct($config);
	}

	protected function getListQuery(): QueryInterface|DatabaseQuery
	{
		$db = $this->getDatabase();
		$query = $db->getQuery(true);
		$query->select(
			$db->quoteName(['a.extension_id', 'a.name', 'a.type', 'a.element', 'a.folder', 'a.client_id'])
		);
		$query->from($db->quoteName('#__extensions', 'a'));

		// ##### Filters
		$filter_type = $this->getState('filter.type');
		if (!empty($filter_type)){
			$query->where('a.type = ' . $db->quote($filter_type));
		}else{
			$query->where('a.type IN ("component", "module", "plugin", "template")');
		}

		$filter_client_id = $this->getState('filter.client_id');
		if ($filter_client_id !== ""){
			$query->where('a.client_id = ' . $db->quote($filter_client_id));
		}

		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where($db->quoteName('a.extension_id') . ' = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(' . $db->quoteName('a.name') . ' LIKE ' . $search . ')');
				$query->orWhere('(' . $db->quoteName('a.element') . ' LIKE ' . $search . ')');
			}
		}

//		$query->order($db->quoteName('a.extension_id') . ' ASC');
		$orderCol  = $this->state->get('list.ordering', 'a.ordering');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	public function getTargetView():string
	{
		// Get the URL Parameter for the task
		return trim(Factory::getApplication()->input->get('target', '', 'string'));
	}

	public function getItems():array
	{
		return parent::getItems();
	}

}