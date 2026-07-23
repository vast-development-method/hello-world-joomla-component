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
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use JCB\Component\Helloworld\Administrator\Helper\HelloworldHelper;
// The default template header for admin views.

// No direct access to this file
defined('_JEXEC') or die;

if ($this->saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_helloworld&task=greetings.saveOrderAjax&tmpl=component';
	Html::_('sortablelist.sortable', 'greetingList', 'adminForm', strtolower($this->listDirn), $saveOrderingUrl);
}
?>
<form action="<?php echo Route::_('index.php?option=com_helloworld&view=greetings'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
<?php
	// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 8374] Add the trash helper layout
	echo LayoutHelper::render('trashhelper', $this);
	// [VDM\Joomla\Componentbuilder\Compiler\Helper\Interpretation 8380] Add the searchtools
	echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
?>
<?php if (empty($this->items)): ?>
	<div class="alert alert-no-items">
		<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
	</div>
<?php else : ?>
	<table class="table table-striped" id="greetingList">
		<thead><?php echo $this->loadTemplate('head');?></thead>
		<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
		<tbody><?php echo $this->loadTemplate('body');?></tbody>
	</table>
	<input type="hidden" name="boxchecked" value="0" />
	</div>
<?php endif; ?>
	<input type="hidden" name="task" value="" />
	<?php echo Html::_('form.token'); ?>
</form>
<script type="text/javascript">
// [VDM\Joomla\Componentbuilder\Compiler\Helper\Infusion 1019] greetings footer script
/***[JCBGUI.admin_view.javascript_views_footer.326.$$$$]***/
// Add JavaScript for the list view that is loaded in the footer inside script tags. Do not add the script tags./***[/JCBGUI$$$$]***/

</script>
