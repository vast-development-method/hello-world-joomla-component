<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				VDM 
/-------------------------------------------------------------------------------------------------------/

	@version		6.0.0
	@build			23rd July, 2026
	@created		20th July, 2026
	@package		Hello World
	@subpackage		GreetingController.php
	@author			Llewellyn <https://www.vdm.io>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/
namespace JCB\Component\Helloworld\Site\Controller;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Versioning\VersionableControllerTrait;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use JCB\Component\Helloworld\Administrator\Helper\HelloworldHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Greeting Form Controller
 *
 * @since  1.6
 */
class GreetingController extends FormController
{
	use VersionableControllerTrait;

	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_HELLOWORLD_GREETING';

	/**
	 * Current or most recently performed task.
	 *
	 * @var    string
	 * @since  12.2
	 * @note   Replaces _task.
	 */
	protected $task;

	/**
	 * The context for storing internal data, e.g. record.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $context = 'greeting';

	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_item = 'greeting';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_list = 'greetings';

	/**
	 * Referral value
	 *
	 * @var    string
	 * @since  5.0
	 */
	protected string $ref;

	/**
	 * Referral ID value
	 *
	 * @var    int
	 * @since  5.0
	 */
	protected int $refid;


/***[JCBGUI.admin_view.php_controller.326.$$$$]***/
// Add PHP methods for the controller that the button/s will target. Do not add the php tags./***[/JCBGUI$$$$]***/


	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = [])
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Controller\AllowAdd 88] Get user object.
		$user = $this->app->getIdentity();
		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Controller\AllowAdd 94] Access check.
		$access = $user->authorise('greeting.access', 'com_helloworld');
		if (!$access)
		{
			return false;
		}

/***[JCBGUI.admin_view.php_allowadd.326.$$$$]***/
// Add PHP Here that should run in the allowAdd Method to add custom access control. Do not add the php tags./***[/JCBGUI$$$$]***/

		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Controller\AllowAdd 121] In the absence of better information, revert to the component permissions.
		return parent::allowAdd($data);
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = [], $key = 'id')
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Controller\AllowEdit 210] get user object.
		$user = $this->app->getIdentity();
		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Controller\AllowEdit 213] get record id.
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;

/***[JCBGUI.admin_view.php_allowedit.326.$$$$]***/
// Add PHP Here that should run in the allowEdit Method to add custom access control. Do not add the php tags./***[/JCBGUI$$$$]***/


		if ($recordId)
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Controller\AllowEdit 237] The record has been set. Check the record permissions.
			$permission = $user->authorise('core.edit', 'com_helloworld.greeting.' . (int) $recordId);
			if (!$permission)
			{
				if ($user->authorise('core.edit.own', 'com_helloworld.greeting.' . $recordId))
				{
					// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Controller\AllowEdit 250] Now test the owner is the user.
					$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
					if (empty($ownerId))
					{
						// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Controller\AllowEdit 256] Need to do a lookup from the model.
						$record = $this->getModel()->getItem($recordId);

						if (empty($record))
						{
							return false;
						}
						$ownerId = $record->created_by;
					}

					// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Controller\AllowEdit 266] If the owner matches 'me' then allow.
					if ($ownerId == $user->id)
					{
						if ($user->authorise('core.edit.own', 'com_helloworld'))
						{
							return true;
						}
					}
				}
				return false;
			}
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Controller\AllowEdit 290] Since there is no permission, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   1.6
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		// get int-defaults (to int new items with default values dynamically)
		$init_defaults = $this->input->get('init_defaults', null, 'STRING');

		// get the referral options (old method use init_defaults or return instead see parent)
		$ref = $this->input->get('ref', 0, 'string');
		$refid = $this->input->get('refid', 0, 'int');

		// get redirect info.
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);

		// set int-defaults
		if (!empty($init_defaults))
		{
			$append = '&init_defaults='. (string) $init_defaults . $append;
		}

		// set the referral options
		if ($refid && $ref)
		{
			$append = '&ref=' . (string) $ref . '&refid='. (int) $refid . $append;
		}
		elseif ($ref)
		{
			$append = '&ref='. (string) $ref . $append;
		}

		return $append;
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   12.2
	 */
	public function cancel($key = null)
	{
		// get the referral options
		$this->ref = $this->input->get('ref', 0, 'word');
		$this->refid = $this->input->get('refid', 0, 'int');

		// Check if there is a return value
		$return = $this->input->get('return', null, 'base64');

/***[JCBGUI.admin_view.php_before_cancel.326.$$$$]***/
// Add PHP Here that should run in the Cancel Method before cancel. Do not add the php tags./***[/JCBGUI$$$$]***/


		$cancel = parent::cancel($key);

		if (!is_null($return) && Uri::isInternal(base64_decode($return)))
		{
			$redirect = base64_decode($return);

			// Redirect to the return value.
			$this->setRedirect(
				Route::_(
					$redirect, false
				)
			);
		}
		elseif ($this->refid && $this->ref)
		{
			$redirect = '&view=' . (string) $this->ref . '&layout=edit&id=' . (int) $this->refid;

			// Redirect to the item screen.
			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . $redirect, false
				)
			);
		}
		elseif ($this->ref)
		{
			$redirect = '&view=' . (string) $this->ref;

			// Redirect to the list screen.
			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . $redirect, false
				)
			);
		}

/***[JCBGUI.admin_view.php_after_cancel.326.$$$$]***/
// Add PHP Here that should run in the Cancel Method after cancel. Do not add the php tags./***[/JCBGUI$$$$]***/

		return $cancel;
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   12.2
	 */
	public function save($key = null, $urlVar = null)
	{
		// get the referral options
		$this->ref = $this->input->get('ref', 0, 'word');
		$this->refid = $this->input->get('refid', 0, 'int');

		// Check if there is a return value
		$return = $this->input->get('return', null, 'base64');
		$canReturn = (!is_null($return) && Uri::isInternal(base64_decode($return)));

		if ($this->ref || $this->refid || $canReturn)
		{
			// to make sure the item is checkedin on redirect
			$this->task = 'save';
		}

		$saved = parent::save($key, $urlVar);

		// This is not needed since parent save already does this
		// Due to the ref and refid implementation we need to add this
		if ($canReturn)
		{
			$redirect = base64_decode($return);

			// Redirect to the return value.
			$this->setRedirect(
				Route::_(
					$redirect, false
				)
			);
		}
		elseif ($this->refid && $this->ref)
		{
			$redirect = '&view=' . (string) $this->ref . '&layout=edit&id=' . (int) $this->refid;

			// Redirect to the item screen.
			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . $redirect, false
				)
			);
		}
		elseif ($this->ref)
		{
			$redirect = '&view=' . (string) $this->ref;

			// Redirect to the list screen.
			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . $redirect, false
				)
			);
		}
		return $saved;
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param   BaseDatabaseModel  $model     The data model object.
	 * @param   array              $validData  The validated data.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	protected function postSaveHook(BaseDatabaseModel $model, $validData = [])
	{

/***[JCBGUI.admin_view.php_postsavehook.326.$$$$]***/
// Add PHP Here that should run in the postSaveHook Method. Do not add the php tags./***[/JCBGUI$$$$]***/


		return;
	}

}
