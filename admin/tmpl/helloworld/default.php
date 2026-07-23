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
use JCB\Component\Helloworld\Administrator\Helper\HelloworldHelper;
// The file header for dashboard view.

// No direct access to this file
defined('_JEXEC') or die;

?>
<div id="j-main-container" class="container-fluid">
	<div class="main-card jcb-dashboard p-3 p-lg-4">
		<div class="jcb-dashboard__content">
			<div class="row g-4 align-items-start">
			<div class="col-12 col-xxl-9">
				<div class="jcb-dashboard__main d-flex flex-column gap-4">
					<?php echo $this->loadTemplate('main');?>
				</div>
			</div>
			<div class="col-12 col-xxl-3">
				<div class="jcb-dashboard__sidebar d-flex flex-column gap-4">
					<?php echo $this->loadTemplate('vdm');?>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>