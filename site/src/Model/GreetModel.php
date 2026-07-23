<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				VDM 
/-------------------------------------------------------------------------------------------------------/

	@version		6.0.0
	@build			23rd July, 2026
	@created		20th July, 2026
	@package		Hello World
	@subpackage		GreetModel.php
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
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\User;
use Joomla\Input\Input;
use Joomla\Utilities\ArrayHelper;
use JCB\Component\Helloworld\Site\Helper\HelloworldHelper;
use JCB\Component\Helloworld\Site\Helper\RouteHelper;
use Joomla\CMS\Helper\TagsHelper;
// The class header for site view model. Only use this option if you have a getItem as your Main Get.

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Helloworld Greet Item Model
 *
 * @since  1.6
 */
class GreetModel extends ItemModel
{
	/**
	 * Model context string.
	 *
	 * @var     string
	 * @since   1.6
	 */
	protected $_context = 'com_helloworld.greet';

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
		'components/com_helloworld/assets/css/greet.css'
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
	 * A custom property for UI Kit components.
	 *
	 * @var   array|null  Property for storing UI Kit component-related data or objects.
	 * @since 3.2.0
	 */
	protected ?array $uikitComp;

	/**
	 * @var     object item
	 * @since   1.6
	 */
	protected $item;

	/**
	 * Constructor
	 *
	 * @param   array                 $config   An array of configuration options (name, state, dbo, table_path, ignore_request).
	 * @param   ?MVCFactoryInterface  $factory  The factory.
	 *
	 * @since   3.0
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
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function populateState()
	{
		// Get the itme main id
		$id = $this->input->getInt('id', null);
		$this->setState('greet.id', $id);

		// Load the parameters.
		$params = $this->app->getParams();
		$this->setState('params', $params);

		parent::populateState();
	}

	/**
	 * Method to get article data.
	 *
	 * @param   integer  $pk  The id of the article.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 1841] check if this user has permission to access item
		if (!$this->user->authorise('site.greet.access', 'com_helloworld'))
		{
			$app = Factory::getApplication();
			$app->enqueueMessage(Text::_('COM_HELLOWORLD_NOT_AUTHORISED_TO_VIEW_GREET'), 'error');
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 1836] redirect away to the home page if no access allowed.
			$app->redirect(Uri::root());
			return false;
		}

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('greet.id');

/***[JCBGUI.dynamic_get.php_before_getitem.145.$$$$]***/
// Add PHP Here that should run in the getItem Method. Do not add the php tags./***[/JCBGUI$$$$]***/


		if ($this->_item === null)
		{
			$this->_item = [];
		}

		if (!isset($this->_item[$pk]))
		{
			try
			{
				// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\GetItem 362] Get a db connection.
				$db = $this->getDatabase();

				// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\GetItem 373] Create a new query object.
				$query = $db->getQuery(true);

				// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\Queries 178] Get from #__helloworld_greeting as a
				$query->select($db->quoteName(
			array('a.id','a.asset_id','a.greeting','a.published','a.created_by','a.modified_by','a.created','a.modified','a.version','a.hits','a.ordering'),
			array('id','asset_id','greeting','published','created_by','modified_by','created','modified','version','hits','ordering')));
				$query->from($db->quoteName('#__helloworld_greeting', 'a'));
				$query->where('a.id = ' . (int) $pk);

				// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\GetItem 444] Reset the query using our newly populated query object.
				$db->setQuery($query);
				// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\GetItem 446] Load the results as a stdClass object.
				$data = $db->loadObject();

				if (empty($data))
				{
					$app = Factory::getApplication();
					// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\GetItem 477] If no data is found redirect to default page and show warning.
					$app->enqueueMessage(Text::_('COM_HELLOWORLD_NOT_FOUND_OR_ACCESS_DENIED'), 'warning');
					$app->redirect(Uri::root());
					return false;
				}
				
				/***[JCBGUI.dynamic_get.php_calculation.145.$$$$]***/
				// Add PHP to do the calculation on any field. Do not add the php tags./***[/JCBGUI$$$$]***/
				

				// [VDM\Joomla\Componentbuilder\Compiler\Dynamicget\GetItem 802] set data object to item.
				$this->_item[$pk] = $data;
			}
			catch (\Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					throw $e;
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

/***[JCBGUI.dynamic_get.php_after_getitem.145.$$$$]***/
// Add PHP Here that should run in the getItem Method. Do not add the php tags./***[/JCBGUI$$$$]***/


		return $this->_item[$pk];
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


/***[JCBGUI.site_view.php_model.84.$$$$]***/
// Add PHP methods for the model that the controller will use. Do not add the php tags./***[/JCBGUI$$$$]***/

}
