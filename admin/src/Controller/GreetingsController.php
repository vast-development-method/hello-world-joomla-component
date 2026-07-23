<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				VDM 
/-------------------------------------------------------------------------------------------------------/

	@version		6.0.0
	@build			23rd July, 2026
	@created		20th July, 2026
	@package		Hello World
	@subpackage		GreetingsController.php
	@author			Llewellyn <https://www.vdm.io>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/
namespace JCB\Component\Helloworld\Administrator\Controller;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use JCB\Component\Helloworld\Administrator\Helper\HelloworldHelper;
use JCB\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use JCB\Joomla\Utilities\ObjectHelper;
// The class header for admin views controller.

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Greetings Admin Controller
 *
 * @since  1.6
 */
class GreetingsController extends AdminController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_HELLOWORLD_GREETINGS';

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Greeting', $prefix = 'Administrator', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function exportData()
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11607] Check for request forgeries
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11611] check if export is allowed for this user.
		$user = Factory::getApplication()->getIdentity();
		if ($user->authorise('greeting.export', 'com_helloworld') && $user->authorise('core.export', 'com_helloworld'))
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11627] Get the input
			$input = Factory::getApplication()->input;
			$pks = $input->post->get('cid', array(), 'array');
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11633] Sanitize the input
			$pks = ArrayHelper::toInteger($pks);
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11636] Get the model
			$model = $this->getModel('Greetings');
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11641] get the data to export
			$data = $model->getExportData($pks);
			if (UtilitiesArrayHelper::check($data))
			{
				// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11648] now set the data to the spreadsheet
				$date = Factory::getDate();
				HelloworldHelper::xls($data,'Greetings_'.$date->format('jS_F_Y'),'Greetings exported ('.$date->format('jS F, Y').')','greetings');
			}
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11660] Redirect to the list screen with error.
		$message = Text::_('COM_HELLOWORLD_EXPORT_FAILED');
		$this->setRedirect(Route::_('index.php?option=com_helloworld&view=greetings', false), $message, 'error');
		return;
	}


	public function importData()
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11675] Check for request forgeries
		Session::checkToken() or die(Text::_('JINVALID_TOKEN'));
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11679] check if import is allowed for this user.
		$user = Factory::getApplication()->getIdentity();
		if ($user->authorise('greeting.import', 'com_helloworld') && $user->authorise('core.import', 'com_helloworld'))
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11695] Get the import model
			$model = $this->getModel('Greetings');
			// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11700] get the headers to import
			$headers = $model->getExImPortHeaders();
			if (ObjectHelper::check($headers))
			{
				// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11707] Load headers to session.
				$session = Factory::getSession();
				$headers = json_encode($headers);
				$session->set('greeting_VDM_IMPORTHEADERS', $headers);
				$session->set('backto_VDM_IMPORT', 'greetings');
				$session->set('dataType_VDM_IMPORTINTO', 'greeting');
				// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11718] Redirect to import view.
				$message = Text::_('COM_HELLOWORLD_IMPORT_SELECT_FILE_FOR_GREETINGS');
				$this->setRedirect(Route::_('index.php?option=com_helloworld&view=import', false), $message);
				return;
			}
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 11749] Redirect to the list screen with error.
		$message = Text::_('COM_HELLOWORLD_IMPORT_FAILED');
		$this->setRedirect(Route::_('index.php?option=com_helloworld&view=greetings', false), $message, 'error');
		return;
	}


/***[JCBGUI.admin_view.php_controller_list.326.$$$$]***/
// Add PHP methods for the controller that the button will target. Do not add the php tags./***[/JCBGUI$$$$]***/

}