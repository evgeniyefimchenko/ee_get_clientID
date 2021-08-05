<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;

if (isset($_SESSION['auth']) && isset($_SESSION['auth']['user_id']) && isset($_COOKIE['_ym_uid'])) {
	if (!isset($_SESSION['auth']['ee_clientID'])) {
		db_query('UPDATE ?:users SET ee_clientID = ?s WHERE user_id = ?i', $_COOKIE['_ym_uid'], $_SESSION['auth']['user_id']);
		$_SESSION['auth']['ee_clientID'] = $_COOKIE['_ym_uid'];
	} 		
}
