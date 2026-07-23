<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				VDM 
/-------------------------------------------------------------------------------------------------------/

	@version		6.0.0
	@build			23rd July, 2026
	@created		20th July, 2026
	@package		Hello World
	@subpackage		GreetingsModel.php
	@author			Llewellyn <https://www.vdm.io>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/
namespace JCB\Component\Helloworld\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\User\User;
use Joomla\Utilities\ArrayHelper;
use Joomla\Input\Input;
use JCB\Component\Helloworld\Administrator\Helper\HelloworldHelper;
use Joomla\CMS\Helper\TagsHelper;
use JCB\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use JCB\Joomla\Utilities\ObjectHelper;
use JCB\Joomla\Utilities\StringHelper;
// The class header for admin views model.

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Greetings List Model
 *
 * @since  1.6
 */
class GreetingsModel extends ListModel
{
	/**
	 * The application object.
	 *
	 * @var   CMSApplicationInterface  The application instance.
	 * @since 3.2.0
	 */
	protected CMSApplicationInterface $app;

	/**
	 * The styles array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $styles = [
		'administrator/components/com_helloworld/assets/css/admin.css',
		'administrator/components/com_helloworld/assets/css/greetings.css'
 	];

	/**
	 * The scripts array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $scripts = [
		'administrator/components/com_helloworld/assets/js/admin.js'
 	];

	/**
	 * Constructor
	 *
	 * @param   array                 $config   An array of configuration options (name, state, dbo, table_path, ignore_request).
	 * @param   ?MVCFactoryInterface  $factory  The factory.
	 *
	 * @since   1.6
	 * @throws  \Exception
	 */
	public function __construct($config = [], ?MVCFactoryInterface $factory = null)
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'a.id','id',
				'a.published','published',
				'a.access','access',
				'a.ordering','ordering',
				'a.created_by','created_by',
				'a.modified_by','modified_by',
				'a.greeting','greeting'
			);
		}

		parent::__construct($config, $factory);

		$this->app ??= Factory::getApplication();
	}


/***[JCBGUI.admin_view.php_model_list.326.$$$$]***/
// Add PHP methods for the model that the controller will use. Do not add the php tags./***[/JCBGUI$$$$]***/


	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 * @since   1.7.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = $this->app;
		$input = $this->app->getInput();

		// Adjust the context to support modal layouts.
		if ($layout = $input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 16107] Check if the form was submitted
		$formSubmited = $input->post->get('form_submited');

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
		if ($formSubmited)
		{
			$access = $input->post->get('access');
			$this->setState('filter.access', $access);
		}

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$created_by = $this->getUserStateFromRequest($this->context . '.filter.created_by', 'filter_created_by', '');
		$this->setState('filter.created_by', $created_by);

		$created = $this->getUserStateFromRequest($this->context . '.filter.created', 'filter_created');
		$this->setState('filter.created', $created);

		$sorting = $this->getUserStateFromRequest($this->context . '.filter.sorting', 'filter_sorting', 0, 'int');
		$this->setState('filter.sorting', $sorting);

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$greeting = $this->getUserStateFromRequest($this->context . '.filter.greeting', 'filter_greeting');
		if ($formSubmited)
		{
			$greeting = $input->post->get('greeting');
			$this->setState('filter.greeting', $greeting);
		}

		// List state information.
		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 * @since   1.6
	 */
	public function getItems()
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CheckInNow 35] Check in items
		$this->checkInNow();

		// load parent items
		$items = parent::getItems();


/***[JCBGUI.admin_view.php_getitems.326.$$$$]***/
// Add PHP Here that should run in the getItems Method. Do not add the php tags./***[/JCBGUI$$$$]***/


/***[JCBGUI.admin_view.php_getitems_after_all.326.$$$$]***/
// Add PHP Here that should run in the getItems Method after all. Do not add the php tags./***[/JCBGUI$$$$]***/


		// return items
		return $items;
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string    An SQL query
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11901] Get the user object.
		$user = $this->getCurrentUser();
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11910] Create a new query object.
		$db = $this->getDatabase();
		$query = $db->getQuery(true);

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11922] Select some fields
		$query->select('a.*');

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11932] From the helloworld_item table
		$query->from($db->quoteName('#__helloworld_greeting', 'a'));


/***[JCBGUI.admin_view.php_getlistquery.326.$$$$]***/
// Add PHP Here that should run in the getListQuery Method of the model of this view, just before the $query object is started. Do not add the php tags./***[/JCBGUI$$$$]***/


		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11951] Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11968] Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11981] Filter by access level.
		$_access = $this->getState('filter.access');
		if ($_access && is_numeric($_access))
		{
			$query->where('a.access = ' . (int) $_access);
		}
		elseif (UtilitiesArrayHelper::check($_access))
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11996] Secure the array for the query
			$_access = ArrayHelper::toInteger($_access);
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 12001] Filter by the Access Array.
			$query->where('a.access IN (' . implode(',', $_access) . ')');
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 12007] Implement View Level Access
		if (!$user->authorise('core.options', 'com_helloworld'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 12171] Filter by search.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search) . '%');
				$query->where('(a.greeting LIKE '.$search.')');
			}
		}


		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 12117] Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'a.id');
		$orderDirn = $this->getState('list.direction', 'desc');
		if ($orderCol != '')
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 12125] Check that the order direction is valid encase we have a field called direction as part of filers.
			$orderDirn = (is_string($orderDirn) && in_array(strtolower($orderDirn), ['asc', 'desc'])) ? $orderDirn : 'desc';
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Method to get list export data.
	 *
	 * @param   array  $pks  The ids of the items to get
	 * @param   JUser  $user  The user making the request
	 *
	 * @return mixed  An array of data items on success, false on failure.
	 */
	public function getExportData($pks, $user = null)
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11355] setup the query
		if (($pks_size = UtilitiesArrayHelper::check($pks)) !== false || 'bulk' === $pks)
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11360] Set a value to know this is export method. (USE IN CUSTOM CODE TO ALTER OUTCOME)
			$_export = true;
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11365] Get the user object if not set.
			if (!isset($user) || !ObjectHelper::check($user))
			{
				$user = $this->getCurrentUser();
			}
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11379] Create a new query object.
			$db = $this->getDatabase();
			$query = $db->getQuery(true);

			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11392] Select some fields
			$query->select('a.*');

			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11396] From the helloworld_greeting table
			$query->from($db->quoteName('#__helloworld_greeting', 'a'));
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11403] The bulk export path
			if ('bulk' === $pks)
			{
				$query->where('a.id > 0');
			}
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11412] A large array of ID's will not work out well
			elseif ($pks_size > 500)
			{
				// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11417] Use lowest ID
				$query->where('a.id >= ' . (int) min($pks));
				// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11421] Use highest ID
				$query->where('a.id <= ' . (int) max($pks));
			}
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11427] The normal default path
			else
			{
				$query->where('a.id IN (' . implode(',',$pks) . ')');
			}

	
/***[JCBGUI.admin_view.php_getlistquery.326.$$$$]***/
// Add PHP Here that should run in the getListQuery Method of the model of this view, just before the $query object is started. Do not add the php tags./***[/JCBGUI$$$$]***/

			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11476] Implement View Level Access
			if (!$user->authorise('core.options', 'com_helloworld'))
			{
				$groups = implode(',', $user->getAuthorisedViewLevels());
				$query->where('a.access IN (' . $groups . ')');
			}

			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11514] Order the results by ordering
			$query->order('a.ordering  ASC');

			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11520] Load the items
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$items = $db->loadObjectList();

				// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 16776] Set values to display correctly.
				if (UtilitiesArrayHelper::check($items))
				{
					foreach ($items as $nr => &$item)
					{
						// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 16913] unset the values we don't want exported.
						unset($item->asset_id);
						unset($item->checked_out);
						unset($item->checked_out_time);
					}
				}
				// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 16928] Add headers to items array.
				$headers = $this->getExImPortHeaders();
				if (ObjectHelper::check($headers))
				{
					array_unshift($items,$headers);
				}

		
/***[JCBGUI.admin_view.php_getitems.326.$$$$]***/
// Add PHP Here that should run in the getItems Method. Do not add the php tags./***[/JCBGUI$$$$]***/


		
/***[JCBGUI.admin_view.php_getitems_after_all.326.$$$$]***/
// Add PHP Here that should run in the getItems Method after all. Do not add the php tags./***[/JCBGUI$$$$]***/

				return $items;
			}
		}
		return false;
	}

	/**
	* Method to get header.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getExImPortHeaders()
	{
		// Get a db connection.
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		// get the columns
		$columns = $db->getTableColumns("#__helloworld_greeting");
		if (UtilitiesArrayHelper::check($columns))
		{
			// remove the headers you don't import/export.
			unset($columns['asset_id']);
			unset($columns['checked_out']);
			unset($columns['checked_out_time']);
			$headers = new \stdClass();
			foreach ($columns as $column => $type)
			{
				$headers->{$column} = $column;
			}
			return $headers;
		}
		return false;
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * @return  string  A store id.
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15903] Compile the store id.
		$id .= ':' . $this->getState('filter.id');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 16056] Check if the value is an array
		$_access = $this->getState('filter.access');
		if (UtilitiesArrayHelper::check($_access))
		{
			$id .= ':' . implode(':', $_access);
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 16071] Check if this is only an number or string
		elseif (is_numeric($_access)
		 || StringHelper::check($_access))
		{
			$id .= ':' . $_access;
		}
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		$id .= ':' . $this->getState('filter.greeting');

		return parent::getStoreId($id);
	}

	/**
	 * Method to get the styles that have to be included on the view
	 *
	 * @return  array    styles files
	 * @since   4.3
	 */
	public function getStyles(): array
	{
		return $this->styles;
	}

	/**
	 * Method to set the styles that have to be included on the view
	 *
	 * @return  void
	 * @since   4.3
	 */
	public function setStyles(string $path): void
	{
		$this->styles[] = $path;
	}

	/**
	 * Method to get the script that have to be included on the view
	 *
	 * @return  array    script files
	 * @since   4.3
	 */
	public function getScripts(): array
	{
		return $this->scripts;
	}

	/**
	 * Method to set the script that have to be included on the view
	 *
	 * @return  void
	 * @since   4.3
	 */
	public function setScript(string $path): void
	{
		$this->scripts[] = $path;
	}

	/**
	 * Build an SQL query to check in all items left checked out longer then a set time.
	 *
	 * @return void
	 * @throws \DateMalformedStringException
	 * @since 3.2.0
	 */
	protected function checkInNow(): void
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CheckInNow 61] Get set check in time
		$time = ComponentHelper::getParams('com_helloworld')->get('check_in');

		if ($time)
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CheckInNow 65] Get a db connection.
			$db = $this->getDatabase();
			// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CheckInNow 67] Reset query.
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__helloworld_greeting'));
			// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CheckInNow 71] Only select items that are checked out.
			$query->where($db->quoteName('checked_out') . ' >= 0');
			// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CheckInNow 75] Query only to see if we have a rows
			$db->setQuery($query, 0, 1);
			$db->execute();
			if ($db->getNumRows())
			{
				// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CheckInNow 80] Get target date in the past.
				$date = Factory::getDate()->modify($time)->toSql();
				// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CheckInNow 82] Reset query.
				$query = $db->getQuery(true);

				// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CheckInNow 84] Fields to update.
				$fields = [
					$db->quoteName('checked_out_time') . ' = NULL',
					$db->quoteName('checked_out') . ' = NULL'
				];

				// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CheckInNow 91] Conditions for which records should be updated.
				$conditions = [
					$db->quoteName('checked_out') . ' = 0 OR ' . $db->quoteName('checked_out') . ' > 0',
					$db->quoteName('checked_out_time') . ' < ' . $db->quote($date)
				];

				// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CheckInNow 98] Check table.
				$query->update($db->quoteName('#__helloworld_greeting'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}
	}
}
