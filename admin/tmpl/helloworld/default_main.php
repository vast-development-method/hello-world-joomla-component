<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				VDM 
/-------------------------------------------------------------------------------------------------------/

	@version		6.0.0
	@build			23rd July, 2026
	@created		20th July, 2026
	@package		Hello World
	@subpackage		default_main.php
	@author			Llewellyn <https://www.vdm.io>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

use Joomla\CMS\Language\Text;

// No direct access to this file
defined('_JEXEC') or die;

?>
<?php if (isset($this->icons['main']) && is_array($this->icons['main']) && !empty($this->icons['main'])) : ?>
    <div class="dashboard-icons" role="list">
		<?php foreach ($this->icons['main'] as $icon) : ?>
            <div class="dashboard-icon-item" role="listitem">
                <a class="dashboard-icon-link" href="<?php echo $icon->url; ?>">
					<span class="dashboard-icon-image">
						<img
                            alt="<?php echo $icon->alt; ?>"
                            src="components/com_helloworld/assets/images/icons/<?php echo $icon->image; ?>"
                            loading="lazy"
                            decoding="async"
                        >
					</span>
                    <span class="dashboard-icon-title">
						<?php echo Text::_($icon->name); ?>
					</span>
                </a>
            </div>
		<?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="alert alert-danger">
        <h4 class="alert-heading">
			<?php echo Text::_("Permission denied, or not correctly set"); ?>
        </h4>
        <div>
			<?php echo Text::_("Please notify your System Administrator if result is unexpected."); ?>
        </div>
    </div>
<?php endif; ?>