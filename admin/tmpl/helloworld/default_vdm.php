<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				VDM 
/-------------------------------------------------------------------------------------------------------/

	@version		6.0.0
	@build			23rd July, 2026
	@created		20th July, 2026
	@package		Hello World
	@subpackage		default_vdm.php
	@author			Llewellyn <https://www.vdm.io>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

use Joomla\CMS\Language\Text;
use JCB\Joomla\Utilities\ArrayHelper;

// No direct access to this file
defined('_JEXEC') or die;

?>
<div class="com-helloworld-dashboard-details">
	<div class="com-helloworld-dashboard-details__image mb-4">
		<img
			class="img-fluid w-100"
			alt="<?php echo Text::_('COM_HELLOWORLD'); ?>"
			src="components/com_helloworld/assets/images/vdm-component.jpg"
			loading="lazy"
			decoding="async"
		>
	</div>
	<ul class="list-group list-group-flush mb-4">
		<li class="list-group-item d-flex flex-wrap justify-content-between gap-2">
			<span>
				<strong><?php echo Text::_('COM_HELLOWORLD_VERSION'); ?>:</strong>
				<?php echo $this->manifest->version; ?>
			</span>
			<span class="update-notice" id="component-update-notice"></span>
		</li>
		<li class="list-group-item">
			<strong><?php echo Text::_('COM_HELLOWORLD_DATE'); ?>:</strong>
			<?php echo $this->manifest->creationDate; ?>
		</li>
		<li class="list-group-item">
			<strong><?php echo Text::_('COM_HELLOWORLD_AUTHOR'); ?>:</strong>
			<a href="mailto:<?php echo $this->manifest->authorEmail; ?>">
				<?php echo $this->manifest->author; ?>
			</a>
		</li>
		<li class="list-group-item">
			<strong><?php echo Text::_('COM_HELLOWORLD_WEBSITE'); ?>:</strong>
			<a
				href="<?php echo $this->manifest->authorUrl; ?>"
				target="_blank"
				rel="noopener noreferrer"
			>
				<?php echo $this->manifest->authorUrl; ?>
			</a>
		</li>
		<li class="list-group-item">
			<strong><?php echo Text::_('COM_HELLOWORLD_LICENSE'); ?>:</strong>
			<?php echo $this->manifest->license; ?>
		</li>
		<li class="list-group-item">
			<strong><?php echo $this->manifest->copyright; ?></strong>
		</li>
	</ul>
	<?php if (ArrayHelper::check($this->contributors)) : ?>
		<div class="com-helloworld-dashboard-details__contributors mt-4">
			<h3 class="h5 mb-3">
				<?php if (count($this->contributors) > 1) : ?>
					<?php echo Text::_('COM_HELLOWORLD_CONTRIBUTORS'); ?>
				<?php else : ?>
					<?php echo Text::_('COM_HELLOWORLD_CONTRIBUTOR'); ?>
				<?php endif; ?>
			</h3>
			<ul class="list-group list-group-flush">
				<?php foreach ($this->contributors as $contributor) : ?>
					<li class="list-group-item">
						<strong><?php echo $contributor['title']; ?>:</strong>
						<?php echo $contributor['name']; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
</div>