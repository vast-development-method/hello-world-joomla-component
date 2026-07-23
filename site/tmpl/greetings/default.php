<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				VDM 
/-------------------------------------------------------------------------------------------------------/

	@version		6.0.0
	@build			23rd July, 2026
	@created		20th July, 2026
	@package		Hello World
	@subpackage		default.php
	@author			Llewellyn <https://www.vdm.io>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\HTML\HTMLHelper as Html;
use JCB\Component\Helloworld\Site\Helper\HelloworldHelper;
use JCB\Component\Helloworld\Site\Helper\RouteHelper;
// The default template header for site views. Only use this option if you have a getListQuery as your Main Get.

// No direct access to this file
defined('_JEXEC') or die;


/***[JCBGUI.site_view.php_view.85.$$$$]***/
// the PHP script that must run in the head of the file.
$edit = "index.php?option=com_helloworld&view=greetings&task=greeting.edit&layout=edit";/***[/JCBGUI$$$$]***/


?>
<?php echo $this->toolbar->render(); ?>

<!--[JCBGUI.site_view.default.85.$$$$]-->
<?php if (!empty($this->items)): ?>
	<ul class="uk-list uk-list-striped">
		<?php foreach ($this->items as $item): ?>
			<li><?php echo Text::_('COM_HELLOWORLD_GREETING'); ?>:
				<a href="<?php echo Route::_(RouteHelper::getGreetRoute($item->slug)); ?>"><?php echo $item->greeting; ?></a>
				<a href="<?php echo $edit; ?>&id=<?php echo $item->id; ?>"><?php echo Text::_('COM_HELLOWORLD_EDIT'); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
<?php else: ?>
	<b><?php echo Text::_('COM_HELLOWORLD_NO_GREETINGS_FOUND'); ?>.</b>
<?php endif; ?><!--[/JCBGUI$$$$]-->

