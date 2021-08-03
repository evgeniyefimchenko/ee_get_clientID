<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;

function fn_ee_get_clientID_install() {
	$db_name = Registry::get("config.db_name");
	$ee_clientID = false;
	$ee_clientID = db_get_field('SELECT 101 FROM INFORMATION_SCHEMA.COLUMNS WHERE `table_name` = "?:users" AND `table_schema` = "' . $db_name . '" AND `column_name` = "ee_clientID"'); 	
	if (!$ee_clientID) {
		db_query('ALTER TABLE `?:users` ADD `ee_clientID` varchar(255) NULL DEFAULT NULL');	
	}
}

function fn_ee_get_clientID_uninstall() {
	return true;
}
