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
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\DatabaseQuery;
use Joomla\Database\QueryInterface;

class LocalextensionsModel extends ListModel
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
		$db    = $this->getDatabase();
		$query = $db->getQuery(true);
		$query->select(
			$db->quoteName(['a.extension_id', 'a.name', 'a.type', 'a.element', 'a.folder', 'a.client_id', 'a.locked', 'a.protected'])
		);
		$query->from($db->quoteName('#__extensions', 'a'));

		// ##### Filters
		$conditions  = array();
		$filter_type = $this->getState('filter.type');
		if (!empty($filter_type))
		{
			$conditions[] = $db->quoteName('a.type') . " = " . $db->quote($filter_type);
		}
		else
		{
			$conditions[] = $db->quoteName('a.type') . ' IN ("component", "module", "plugin", "template")';
		}

		$filter_protected = $this->getState('filter.protected');
		if ($filter_protected !== "")
		{
			$conditions[] = $db->quoteName('a.protected') . " = " . $db->quote($filter_protected);
		}

		$filter_locked = $this->getState('filter.locked');
		if ($filter_locked !== "")
		{
			$conditions[] = $db->quoteName('a.locked') . " = " . $db->quote($filter_locked);
		}

		$filter_client_id = $this->getState('filter.client_id');
		if ($filter_client_id !== "")
		{
			$conditions[] = $db->quoteName('a.client_id') . " = " . $db->quote($filter_client_id);
		}

		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$conditions[] = $db->quoteName('a.extension_id') . ' = ' . (int) substr($search, 3);
			}
			else
			{
				$search           = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$searchConditions = [
					$db->quoteName('a.name') . ' LIKE ' . $search,
					$db->quoteName('a.element') . ' LIKE ' . $search,
				];

				$conditions[] = '(' . implode(' OR ', $searchConditions) . ')';
			}
		}

		if(!empty($conditions))
		{
			$query->where(implode(' AND ', $conditions));
		}

//		$query->order($db->quoteName('a.extension_id') . ' ASC');
		$orderCol  = $this->state->get('list.ordering', 'a.ordering');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	public function getTargetView(): string
	{
		// Get the URL Parameter for the task
		return trim(Factory::getApplication()->input->get('target', '', 'string'));
	}

	public function getItems(): array
	{
		return parent::getItems();
	}

}