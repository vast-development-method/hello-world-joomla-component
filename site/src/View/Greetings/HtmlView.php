<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				VDM 
/-------------------------------------------------------------------------------------------------------/

	@version		6.0.0
	@build			23rd July, 2026
	@created		20th July, 2026
	@package		Hello World
	@subpackage		HtmlView.php
	@author			Llewellyn <https://www.vdm.io>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/
namespace JCB\Component\Helloworld\Site\View\Greetings;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Document\Document;
use JCB\Component\Helloworld\Site\Helper\HeaderCheck;
use JCB\Component\Helloworld\Site\Helper\HelloworldHelper;
use JCB\Component\Helloworld\Site\Helper\RouteHelper;
use JCB\Joomla\Utilities\StringHelper;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Joomla\CMS\User\User;
// The class header for site views. Only use this option if you have a getListQuery as your Main Get.

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Helloworld Html View class for the Greetings
 *
 * @since  1.6
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The app class
	 *
	 * @var    CMSApplicationInterface
	 * @since  5.2.1
	 */
	public CMSApplicationInterface $app;

	/**
	 * The input class
	 *
	 * @var    Input
	 * @since  5.2.1
	 */
	public Input $input;

	/**
	 * The params registry
	 *
	 * @var    Registry
	 * @since  5.2.1
	 */
	public Registry $params;

	/**
	 * The user object.
	 *
	 * @var    User
	 * @since  3.10.11
	 */
	public User $user;

	/**
	 * The items from the model
	 *
	 * @var    mixed
	 * @since  3.10.11
	 */
	public mixed $items;

	/**
	 * The toolbar object
	 *
	 * @var    Toolbar
	 * @since  3.10.11
	 */
	public Toolbar $toolbar;

	/**
	 * The styles url array
	 *
	 * @var    array
	 * @since  5.0.0
	 */
	protected array $styles;

	/**
	 * The scripts url array
	 *
	 * @var    array
	 * @since  5.0.0
	 */
	protected array $scripts;

	/**
	 * The actions object
	 *
	 * @var    object
	 * @since  3.10.11
	 */
	public object $canDo;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 * @throws \Exception
	 * @since  1.6
	 */
	public function display($tpl = null): void
	{
		// get application
		$this->app ??= Factory::getApplication();
		// get input
		$this->input ??= method_exists($this->app, 'getInput') ? $this->app->getInput() : $this->app->input;
		// set params
		$this->params ??= method_exists($this->app, 'getParams')
			? $this->app->getParams()
			: ComponentHelper::getParams('com_helloworld');
		$this->menu = $this->app->getMenu()->getActive();
		// get the user object
		$this->user ??= $this->getCurrentUser();

		// Load module values
		$model = $this->getModel();
		$this->styles = $model->getStyles() ?? [];
		$this->scripts = $model->getScripts() ?? [];
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 2109] Initialise variables.
		$this->items = $model->getItems();
		
		/***[JCBGUI.site_view.php_jview_display.85.$$$$]***/
		// Add custom PHP script to the JViewLegacy display method./***[/JCBGUI$$$$]***/
		

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 2183] Set the toolbar
		$this->addToolBar();

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 2186] Set the html view document stuff
		$this->_prepareDocument();

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 2213] Check for errors.
		if (count($errors = $model->getErrors()))
		{
			throw new \Exception(implode(PHP_EOL, $errors), 500);
		}

		parent::display($tpl);
	}


/***[JCBGUI.site_view.php_jview.85.$$$$]***/
// PHP methods for the JViewLegacy class./***[/JCBGUI$$$$]***/


	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 * @throws  \Exception
	 * @since   1.6
	 */
	protected function addToolbar(): void
	{
		
		// [VDM\Joomla\Componentbuilder\Compiler\Architecture\JoomlaSix\SiteView\AddToolBar 172] now initiate toolbar if it's not already loaded
		$this->toolbar ??= $this->getDocument()->getToolbar();
/***[JCBGUI.site_view.view_toolbar.85.$$$$]***/
// Provide PHP code to be executed in the addToolbar() method of the Site_view HtmlView. When defined, this code completely overrides JCB default toolbar buttons./***[/JCBGUI$$$$]***/

	}

	/**
	 * Prepare some document related stuff.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function _prepareDocument(): void
	{

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 3001] Only load jQuery if needed. (default is true)
		if ($this->params->get('add_jquery_framework', 1) == 1)
		{
			Html::_('jquery.framework');
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 3007] Load the header checker class.
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 3029] Initialize the header checker.
		$HeaderCheck = new HeaderCheck();

		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 2621] Add View JavaScript File
		Html::_('script', 'components/com_helloworld/assets/js/greetings.js', ['version' => 'auto']);
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 2928] load the meta description
		if ($this->params->get('menu-meta_description'))
		{
			$this->getDocument()->setDescription($this->params->get('menu-meta_description'));
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 2936] load the key words if set
		if ($this->params->get('menu-meta_keywords'))
		{
			$this->getDocument()->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 2944] check the robot params
		if ($this->params->get('robots'))
		{
			$this->getDocument()->setMetadata('robots', $this->params->get('robots'));
		}
		
		/***[JCBGUI.site_view.php_document.85.$$$$]***/
		// Add PHP to the document method in the view.html.php file of this view. Do not add the php tags./***[/JCBGUI$$$$]***/
		
		// add styles
		foreach ($this->styles as $style)
		{
			Html::_('stylesheet', $style, ['version' => 'auto']);
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 2564] Set the Custom JS script to view
		$this->getDocument()->getWebAssetManager()->addInlineStyle("
			/*  CSS script to the document method. You can add in PHP like this: \".$var.\" */
		");
		// add scripts
		foreach ($this->scripts as $script)
		{
			Html::_('script', $script, ['version' => 'auto']);
		}
		// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 2650] Set the Custom JS script to view
		$this->getDocument()->getWebAssetManager()->addInlineScript("
			
			/***[JCBGUI.site_view.js_document.85.$$$$]***/
			// JS script to the document method./***[/JCBGUI$$$$]***/
			
		");
	}

	/**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed  $var     The output to escape.
	 * @param   bool   $shorten The switch to shorten.
	 * @param   int    $length  The shorting length.
	 *
	 * @return  mixed  The escaped value.
	 * @since   1.6
	 */
	public function escape($var, bool $shorten = false, int $length = 40)
	{
		if (!is_string($var))
		{
			return $var;
		}

		return StringHelper::html($var, $this->_charset ?? 'UTF-8', $shorten, $length);
	}
}
