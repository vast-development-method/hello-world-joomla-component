<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				VDM 
/-------------------------------------------------------------------------------------------------------/

	@version		6.0.0
	@build			23rd July, 2026
	@created		20th July, 2026
	@package		Hello World
	@subpackage		HelloworldInstallerPowerloader.php
	@author			Llewellyn <https://www.vdm.io>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die;

// [VDM\Joomla\Componentbuilder\Compiler\Power\Autoloader 446] register additional namespace
spl_autoload_register(function ($class) {
	// [VDM\Joomla\Componentbuilder\Compiler\Power\Autoloader 449] project-specific base directories and namespace prefix
	$search = [
		'libraries/jcb_powers/JCB.Joomla' => 'JCB\\Joomla'
	];
	// Start the search and load if found
	$found = false;
	$found_base_dir = "";
	$found_len = 0;
	foreach ($search as $base_dir => $prefix)
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Power\Autoloader 475] does the class use the namespace prefix?
		$len = strlen($prefix);
		if (strncmp($prefix, $class, $len) === 0)
		{
			// [VDM\Joomla\Componentbuilder\Compiler\Power\Autoloader 480] we have a match so load the values
			$found = true;
			$found_base_dir = $base_dir;
			$found_len = $len;
			// [VDM\Joomla\Componentbuilder\Compiler\Power\Autoloader 485] done here
			break;
		}
	}
	// [VDM\Joomla\Componentbuilder\Compiler\Power\Autoloader 491] check if we found a match
	if (!$found)
	{
		// [VDM\Joomla\Componentbuilder\Compiler\Power\Autoloader 496] not found so move to the next registered autoloader
		return;
	}
	// [VDM\Joomla\Componentbuilder\Compiler\Power\Autoloader 502] get the relative class name
	$relative_class = substr($class, $found_len);
	// [VDM\Joomla\Componentbuilder\Compiler\Power\Autoloader 505] replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = __DIR__ . '/' . $found_base_dir . '/src' . str_replace('\\', '/', $relative_class) . '.php';
	// [VDM\Joomla\Componentbuilder\Compiler\Power\Autoloader 510] if the file exists, require it
	if (file_exists($file))
	{
		require $file;
	}
});
