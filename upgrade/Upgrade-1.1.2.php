<?php

function upgrade_module_1_1_2($module)
{
	return $module->uninstallAdminTabs() &&
		$module->installAdminTabs();
}