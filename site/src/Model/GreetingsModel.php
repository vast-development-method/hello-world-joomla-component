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
namespace JCB\Component\Helloworld\Site\Model;

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
use JCB\Component\Helloworld\Site\Helper\HelloworldHelper;
use JCB\Component\Helloworld\Site\Helper\RouteHelper;
use Joomla\CMS\Helper\TagsHelper;
use JCB\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use Joomla\CMS\Uri\Uri;
// The class header for site views model. Only use this option if you have a getListQuery as your Main Get.

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Helloworld List Model for Greetings
 *
 * @since  1.6
 */
class GreetingsModel extends ListModel
{
	/**
	 * Represents the current user object.
	 *
	 * @var   User  The user object representing the current user.
	 * @since 3.2.0
	 */
	protected User $user;

	/**
	 * The unique identifier of the current user.
	 *
	 * @var   int|null  The ID of the current user.
	 * @since 3.2.0
	 */
	protected ?int $userId;

	/**
	 * Flag indicating whether the current user is a guest.
	 *
	 * @var   int  1 if the user is a guest, 0 otherwise.
	 * @since 3.2.0
	 */
	protected int $guest;

	/**
	 * An array of groups that the current user belongs to.
	 *
	 * @var   array|null  An array of user group IDs.
	 * @since 3.2.0
	 */
	protected ?array $groups;

	/**
	 * An array of view access levels for the current user.
	 *
	 * @var   array|null  An array of access level IDs.
	 * @since 3.2.0
	 */
	protected ?array $levels;

	/**
	 * The application object.
	 *
	 * @var   CMSApplicationInterface  The application instance.
	 * @since 3.2.0
	 */
	protected CMSApplicationInterface $app;

	/**
	 * The input object, providing access to the request data.
	 *
	 * @var   Input  The input object.
	 * @since 3.2.0
	 */
	protected Input $input;

	/**
	 * The styles array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $styles = [
		'components/com_helloworld/assets/css/site.css',
		'components/com_helloworld/assets/css/greetings.css'
 	];

	/**
	 * The scripts array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $scripts = [
		'components/com_helloworld/assets/js/site.js'
 	];

	/**
	 * A custom property for UIKit components. (not used unless you load v2)
	 */
	protected $uikitComp;

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
		parent::__construct($config, $factory);

		$this->app ??= Factory::getApplication();
		$this->input ??= $this->app->getInput();

		// Set the current user for authorisation checks (for those calling this model directly)
		$this->user ??= $this->getCurrentUser();
		$this->userId = $this->user->get('id');
		$this->guest = $this->user->get('guest');
		$this->groups = $this->user->get('groups');
		$this->authorisedGroups = $this->user->getAuthorisedGroups();
		$this->levels = $this->user->getAuthorisedViewLevels();

		// will be removed
		$this->initSet = true;
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return   string  An SQL query
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\ListQuery 141] Make sure all records load, since no pagination allowed.
		$this->setState('list.limit', 0);
		// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\ListQuery 145] Get a db connection.
		$db = $this->getDatabase();

		// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\ListQuery 158] Create a new query object.
		$query = $db->getQuery(true);

		// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\Queries 178] Get from #__helloworld_greeting as a
		$query->select($db->quoteName(
			array('a.id','a.asset_id','a.greeting','a.published','a.created_by','a.modified_by','a.created','a.modified','a.version','a.hits','a.ordering'),
			array('id','asset_id','greeting','published','created_by','modified_by','created','modified','version','hits','ordering')));
		$query->from($db->quoteName('#__helloworld_greeting', 'a'));

		// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\ListQuery 170] Filtering.

/***[JCBGUI.dynamic_get.php_getlistquery.146.$$$$]***/
// Add PHP Here that should run in the getListQuery Method of the model of this view, just before the $query object is started. Do not add the php tags/***[/JCBGUI$$$$]***/

		// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\QueryWhere 169] Get where a.published is 1
		$query->where('a.published = 1');

		// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\ListQuery 179] return the query object
		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 * @since   1.6
	 */
	public function getItems()
	{
		$user = $this->user;
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 1841] check if this user has permission to access item
		if (!$user->authorise('site.greetings.access', 'com_helloworld'))
		{
			$app = Factory::getApplication();
			$app->enqueueMessage(Text::_('COM_HELLOWORLD_NOT_AUTHORISED_TO_VIEW_GREETINGS'), 'error');
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 1836] redirect away to the home page if no access allowed.
			$app->redirect(Uri::root());
			return false;
		}


/***[JCBGUI.dynamic_get.php_before_getitems.146.$$$$]***/
// Add PHP Here that should run in the getItems Method before the items are loaded. Do not add the php tags./***[/JCBGUI$$$$]***/

		// load parent items
		$items = parent::getItems();

		// Get the global params
		$globalParams = ComponentHelper::getParams('com_helloworld', true);

		// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\GetItems 222] Insure all item fields are adapted where needed.
		if (UtilitiesArrayHelper::check($items))
		{
			foreach ($items as $nr => &$item)
			{
				// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\GetItems 228] Always create a slug for sef URL's
				$item->slug = ($item->id ?? '0') . (isset($item->alias) ? ':' . $item->alias : '');
				
				/***[JCBGUI.dynamic_get.php_calculation.146.$$$$]***/
				// Add PHP to do the calculation on any field. Do not add the php tags./***[/JCBGUI$$$$]***/
				
			}
		}


/***[JCBGUI.dynamic_get.php_after_getitems.146.$$$$]***/
// Add PHP Here that should run in the getItems Method. Do not add the php tags/***[/JCBGUI$$$$]***/


		// return items
		return $items;
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


/***[JCBGUI.site_view.php_model.85.$$$$]***/
// Add PHP methods for the model that the controller will use. Do not add the php tags./***[/JCBGUI$$$$]***/

}
