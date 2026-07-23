<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				VDM 
/-------------------------------------------------------------------------------------------------------/

	@version		6.0.0
	@build			23rd July, 2026
	@created		20th July, 2026
	@package		Hello World
	@subpackage		GreetingModel.php
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
use Joomla\CMS\Form\Form;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Table\Table;
use Joomla\CMS\UCM\UCMType;
use Joomla\CMS\Versioning\VersionableModelTrait;
use Joomla\CMS\User\User;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;
use Joomla\Input\Input;
use JCB\Component\Helloworld\Administrator\Helper\HelloworldHelper;
use Joomla\CMS\Helper\TagsHelper;
use JCB\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Helloworld Greeting Admin Model
 *
 * @since  1.6
 */
class GreetingModel extends AdminModel
{
	use VersionableModelTrait;

	/**
	 * The tab layout fields array.
	 *
	 * @var      array
	 */
	protected $tabLayoutFields = array(
		'details' => array(
			'left' => array(
				'greeting',
				'alias'
			)
		)
	);

	/**
	 * The styles array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $styles = [
		'components/com_helloworld/assets/css/site.css',
		'components/com_helloworld/assets/css/greeting.css'
 	];

	/**
	 * The scripts array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $scripts = [
		'components/com_helloworld/assets/js/site.js',
		'media/com_helloworld/js/greeting.js'
 	];

	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_HELLOWORLD';

	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_helloworld.greeting';

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type    $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  Table  A database object
	 *
	 * @since   3.0
	 * @throws  \Exception
	 */
	public function getTable($type = 'greeting', $prefix = 'Administrator', $config = [])
	{
		// get instance of the table
		return parent::getTable($type, $prefix, $config);
	}


/***[JCBGUI.admin_view.php_model.326.$$$$]***/
// Add PHP methods for the model that the controller will use. Do not add the php tags./***[/JCBGUI$$$$]***/


	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			if (property_exists($item, 'metadata') && !is_array($item->metadata))
			{
				// Convert the metadata field to an array.
				$metadata       = new Registry($item->metadata);
				$item->metadata = $metadata->toArray();
			}

			// check edit access permissions
			if (!empty($item->id) && !$this->allowEdit((array) $item))
			{
 				$app = Factory::getApplication();
  				$app->enqueueMessage(Text::_('Not authorised!'), 'error');
				$app->redirect('index.php?option=com_helloworld&view=greetings');
				return false;
			}


/***[JCBGUI.admin_view.php_getitem.326.$$$$]***/
// Add PHP Here that should run in the getItem Method. Do not add the php tags./***[/JCBGUI$$$$]***/

		}

		return $item;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 * @param   array    $options   Optional array of options for the form creation.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = [], $loadData = true, $options = array('control' => 'jform'))
	{
		// set load data option
		$options['load_data'] = $loadData;
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15264] check if xpath was set in options
		$xpath = false;
		if (isset($options['xpath']))
		{
			$xpath = $options['xpath'];
			unset($options['xpath']);
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15272] check if clear form was set in options
		$clear = false;
		if (isset($options['clear']))
		{
			$clear = $options['clear'];
			unset($options['clear']);
		}

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15280] Get the form.
		$form = $this->loadForm('com_helloworld.greeting', 'greeting', $options, $clear, $xpath);

		if (empty($form))
		{
			return false;
		}

		$app = Factory::getApplication();

		$jinput = method_exists($app, 'getInput') ? $app->getInput() : $app->input;

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15294] The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('a_id'))
		{
			$id = $jinput->get('a_id', 0, 'INT');
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15302] The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0, 'INT');
		}

		$user = Factory::getApplication()->getIdentity();

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15319] Check for existing item.
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15321] Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_helloworld.greeting.' . (int) $id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_helloworld')))
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15333] Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15339] Disable fields while saving.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15346] If this is a new item insure the greated by is set.
		if (0 == $id)
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15350] Set the created_by to this user
			$form->setValue('created_by', null, $user->id);
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15355] Modify the form based on Edit Creaded By access controls.
		if (!$user->authorise('core.edit.created_by', 'com_helloworld'))
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15373] Disable fields for display.
			$form->setFieldAttribute('created_by', 'disabled', 'true');
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15377] Disable fields for display.
			$form->setFieldAttribute('created_by', 'readonly', 'true');
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15381] Disable fields while saving.
			$form->setFieldAttribute('created_by', 'filter', 'unset');
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15386] Modify the form based on Edit Creaded Date access controls.
		if (!$user->authorise('core.edit.created', 'com_helloworld'))
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15405] Disable fields for display.
			$form->setFieldAttribute('created', 'disabled', 'true');
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15409] Disable fields while saving.
			$form->setFieldAttribute('created', 'filter', 'unset');
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15473] Only load these values if no id is found
		if (0 == $id)
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15477] Set redirected view name
			$redirectedView = $jinput->get('ref', null, 'STRING');
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15481] Set field name (or fall back to view name)
			$redirectedField = $jinput->get('field', $redirectedView, 'STRING');
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15485] Set redirected view id
			$redirectedId = $jinput->get('refid', 0, 'INT');
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15489] Set field id (or fall back to redirected view id)
			$redirectedValue = $jinput->get('field_id', $redirectedId, 'INT');
			if (0 != $redirectedValue && $redirectedField)
			{
				// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15496] Now set the local-redirected field default value
				$form->setValue($redirectedField, null, $redirectedValue);
			}
			$initDefaults = $jinput->get('init_defaults', null, 'STRING');
			if (!empty($initDefaults))
			{
				// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 15509] Now check if this json values are valid
				$initDefaults = json_decode(urldecode($initDefaults), true);
				if (is_array($initDefaults))
				{
					foreach ($initDefaults as $field => $value)
					{
						$form->setValue($field, null, $value);
					}
				}
			}
		}

/***[JCBGUI.admin_view.php_getform.326.$$$$]***/
// Add PHP Here that should run in the getForm Method. Do not add the php tags./***[/JCBGUI$$$$]***/

		return $form;
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
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (empty($record->id) || ($record->published != -2))
		{
			return false;
		}

		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CanDelete 78] The record has been set. Check the record permissions.
		return $this->getCurrentUser()->authorise('core.delete', 'com_helloworld.greeting.' . (int) $record->id);
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		$user = $this->getCurrentUser();
		$recordId = $record->id ?? 0;

		if ($recordId)
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CanEditState 77] The record has been set. Check the record permissions.
			$permission = $user->authorise('core.edit.state', 'com_helloworld.greeting.' . (int) $recordId);
			if (!$permission && !is_null($permission))
			{
				return false;
			}
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\Model\CanEditState 99] In the absence of better information, revert to the component permissions.
		return parent::canEditState($record);
	}

	/**
	 * Method to check if you can edit an existing record.
	 *   We know this is a double access check (Controller already does an allowEdit check)
	 *   But when the item is directly accessed the controller is skipped (2025_).
	 *
	 * @param   array    $data   An array of input data.
	 * @param   string   $key    The name of the key for the primary key.
	 *
	 * @return   boolean True if allowed to edit the record. Defaults to the permission set in the component.
	 * @since    2.5
	 */
	protected function allowEdit(array $data = [], string $key = 'id'): bool
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\Model\AllowEdit 203] get user object.
		$user = $this->getCurrentUser();
		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\Model\AllowEdit 206] get record id.
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;

/***[JCBGUI.admin_view.php_allowedit.326.$$$$]***/
// Add PHP Here that should run in the allowEdit Method to add custom access control. Do not add the php tags./***[/JCBGUI$$$$]***/


		if ($recordId)
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Architecture\Model\AllowEdit 230] The record has been set. Check the record permissions.
			$permission = $user->authorise('core.edit', 'com_helloworld.greeting.' . (int) $recordId);
			if (!$permission)
			{
				if ($user->authorise('core.edit.own', 'com_helloworld.greeting.' . $recordId))
				{
					// [VDM\Joomla\Componentbuilder\Compiler\Architecture\Model\AllowEdit 243] Now test the owner is the user.
					$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
					if (empty($ownerId))
					{
						return false;
					}

					// [VDM\Joomla\Componentbuilder\Compiler\Architecture\Model\AllowEdit 251] If the owner matches 'me' then allow.
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
		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\Model\AllowEdit 275] Since there is no permission given, core edit must be checked.
		return $user->authorise('core.edit', $this->option);
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   Table  $table  A Table object.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		$date = Factory::getDate();
		$user = $this->getCurrentUser();

		if (isset($table->name))
		{
			$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);
		}

		if (isset($table->alias) && empty($table->alias))
		{
			$table->generateAlias();
		}

		if (empty($table->id))
		{
			$table->created = $date->toSql();
			// set the user
			if ($table->created_by == 0 || empty($table->created_by))
			{
				$table->created_by = $user->id;
			}
			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = $this->getDatabase();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->quoteName('#__helloworld_greeting'));
				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
		}
		else
		{
			$table->modified = $date->toSql();
			$table->modified_by = $user->id;
		}

		if (!empty($table->id))
		{
			// Increment the items version number.
			$table->version++;
		}
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_helloworld.edit.greeting.data', []);

		if (empty($data))
		{
			$data = $this->getItem();
		}

		// run the perprocess of the data
		$this->preprocessData('com_helloworld.greeting', $data);

		return $data;
	}

	/**
	 * Method to get the unique fields of this table.
	 *
	 * @return  mixed  An array of field names, boolean false if none is set.
	 *
	 * @since   3.0
	 */
	protected function getUniqueFields()
	{
		return false;
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   array  &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   12.2
	 */
	public function delete(&$pks)
	{

/***[JCBGUI.admin_view.php_before_delete.326.$$$$]***/
// Add PHP Here that should run in the delete Method before items are deleted. Do not add the php tags./***[/JCBGUI$$$$]***/

		if (!parent::delete($pks))
		{
			return false;
		}


/***[JCBGUI.admin_view.php_after_delete.326.$$$$]***/
// Add PHP Here that should run in the delete Method before items are deleted. Do not add the php tags./***[/JCBGUI$$$$]***/


		return true;
	}

	/**
	 * Method to change the published state of one or more records.
	 *
	 * @param   array    &$pks   A list of the primary keys to change.
	 * @param   integer  $value  The value of the published state.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   12.2
	 */
	public function publish(&$pks, $value = 1)
	{

/***[JCBGUI.admin_view.php_before_publish.326.$$$$]***/
// Add PHP Here that should run in the publish Method before items published state is changed. Do not add the php tags./***[/JCBGUI$$$$]***/

		if (!parent::publish($pks, $value))
		{
			return false;
		}


/***[JCBGUI.admin_view.php_after_publish.326.$$$$]***/
// Add PHP Here that should run in the publish Method after the item's published state has been changed. Do not add the php tags./***[/JCBGUI$$$$]***/


		return true;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		$app = Factory::getApplication();
		$input = method_exists($app, 'getInput') ? $app->getInput() : $app->input;
		$filter = InputFilter::getInstance();

		// set the metadata to the Item Data
		if (isset($data['metadata']) && isset($data['metadata']['author']))
		{
			$data['metadata']['author'] = $filter->clean($data['metadata']['author'], 'TRIM');

			$metadata = new Registry;
			$metadata->loadArray($data['metadata']);
			$data['metadata'] = (string) $metadata;
		}


/***[JCBGUI.admin_view.php_before_save.326.$$$$]***/
// Add PHP Here that should run in the save Method. Do not add the php tags./***[/JCBGUI$$$$]***/



/***[JCBGUI.admin_view.php_save.326.$$$$]***/
// Add PHP Here that should run in the save Method. Do not add the php tags./***[/JCBGUI$$$$]***/


		// Set the Params Items to data
		if (isset($data['params']) && is_array($data['params']))
		{
			$params = new Registry;
			$params->loadArray($data['params']);
			$data['params'] = (string) $params;
		}

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 6487] Alter the greeting for save as copy
		if ($input->get('task') === 'save2copy')
		{
			$origTable = clone $this->getTable();
			$origTable->load($input->getInt('id'));

			if ($data['greeting'] == $origTable->greeting)
			{
				list($greeting, $alias) = $this->_generateNewTitle($data['alias'], $data['greeting']);
				$data['greeting'] = $greeting;
				$data['alias'] = $alias;
			}
			else
			{
				if ($data['alias'] == $origTable->alias)
				{
					$data['alias'] = '';
				}
			}

			$data['published'] = 0;
		}

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 6555] Automatic handling of alias for empty fields
		if (in_array($input->get('task'), array('apply', 'save', 'save2new')) && (int) $input->get('id') == 0)
		{
			if ($data['alias'] == null || empty($data['alias']))
			{
				if (Factory::getConfig()->get('unicodeslugs') == 1)
				{
					$data['alias'] = OutputFilter::stringURLUnicodeSlug($data['greeting']);
				}
				else
				{
					$data['alias'] = OutputFilter::stringURLSafe($data['greeting']);
				}

				$table = clone $this->getTable();

				if ($table->load(array('alias' => $data['alias'])) && ($table->id != $data['id'] || $data['id'] == 0))
				{
					$msg = Text::_('COM_HELLOWORLD_GREETING_SAVE_WARNING');
				}

				$data['alias'] = $this->_generateNewTitle($data['alias']);

				if (isset($msg))
				{
					Factory::getApplication()->enqueueMessage($msg, 'warning');
				}
			}
		}

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 6635] Alter the unique field for save as copy
		if ($input->get('task') === 'save2copy')
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 6640] Automatic handling of other unique fields
			$uniqueFields = $this->getUniqueFields();
			if (UtilitiesArrayHelper::check($uniqueFields))
			{
				foreach ($uniqueFields as $uniqueField)
				{
					$data[$uniqueField] = $this->generateUnique($uniqueField,$data[$uniqueField]);
				}
			}
		}

		if (parent::save($data))
		{
			return true;
		}
		return false;
	}

	/**
	 * Method to generate a unique value.
	 *
	 * @param   string  $field name.
	 * @param   string  $value data.
	 *
	 * @return  string  New value.
	 *
	 * @since   3.0
	 */
	protected function generateUnique($field, $value)
	{
		// set field value unique
		$table = $this->getTable();

		while ($table->load(array($field => $value)))
		{
			$value = StringHelper::increment($value);
		}

		return $value;
	}

	/**
	 * Method to change the title/s & alias.
	 *
	 * @param   string         $alias        The alias.
	 * @param   string/array   $title        The title.
	 *
	 * @return	array/string  Contains the modified title/s and/or alias.
	 *
	 */
	protected function _generateNewTitle($alias, $title = null)
	{

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 6687] Alter the title/s & alias
		$table = $this->getTable();

		while ($table->load(['alias' => $alias]))
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 6693] Check if this is an array of titles
			if (UtilitiesArrayHelper::check($title))
			{
				foreach($title as $nr => &$_title)
				{
					$_title = StringHelper::increment($_title);
				}
			}
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 6705] Make sure we have a title
			elseif ($title)
			{
				$title = StringHelper::increment($title);
			}
			$alias = StringHelper::increment($alias, 'dash');
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 6715] Check if this is an array of titles
		if (UtilitiesArrayHelper::check($title))
		{
			$title[] = $alias;
			return $title;
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 6723] Make sure we have a title
		elseif ($title)
		{
			return array($title, $alias);
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 6729] We only had an alias
		return $alias;
	}
}
