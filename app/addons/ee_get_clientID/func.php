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
	// Установим доп. хук ee_rus_exim_1c_order_data в app/addons/rus_exim_1c/Tygh/Commerceml/RusEximCommerceml.php $order_xml = \$this->getOrderDataForXml\(\$order_data, \$cml\);
	// Строка 2518
	$path = 'app/addons/rus_exim_1c/Tygh/Commerceml/RusEximCommerceml.php';
	$oldstr = '$order_xml = $this->getOrderDataForXml($order_data, $cml);'; 	 
	$newstr = '$order_xml = $this->getOrderDataForXml($order_data, $cml); fn_set_hook(\'ee_rus_exim_1c_order_data\', $order_data, $order_xml);'; 
	$file = file($path);
	if (is_array($file) && !fn_ee_get_clientID_check_hook()) { 
		$file = str_replace($oldstr, $newstr, $file);
		if ($fp = fopen($path, 'w+')) {
			fwrite($fp, implode('', $file)); 
			fclose($fp);
			fn_set_notification('N', 'ee_get_clientID: ', 'Хук ee_rus_exim_1c_order_data установлен.');			
		} else {
			fn_set_notification('E', 'ee_get_clientID error: ', 'Ошибка открытия файла app/addons/rus_exim_1c/Tygh/Commerceml/RusEximCommerceml.php для записи.');
			fn_set_notification('E', 'ee_get_clientID error: ', var_export(error_get_last(), true));
		}	
	} else {		
		fn_set_notification('E', 'ee_get_clientID error: ', 'Ошибка чтения файла app/addons/rus_exim_1c/Tygh/Commerceml/RusEximCommerceml.php');
	}	
}


function fn_ee_get_clientID_dispatch_assign_template($controller, $mode, $area, $controllers_cascade) {
	if ($area == 'A') {
		if (!fn_ee_get_clientID_check_hook()) {
			fn_set_notification('E', 'ee_get_clientID error: ', 'Хук ee_rus_exim_1c_order_data не найден, переустановите модуль!');
		}
	}
}

function fn_ee_get_clientID_check_hook() {
	$str = 'fn_set_hook(\'ee_rus_exim_1c_order_data\', $order_data, $order_xml);';
	$file = file('app/addons/rus_exim_1c/Tygh/Commerceml/RusEximCommerceml.php');
	$flag = false;
	if (is_array($file)) {
		foreach($file as $key => $value) {
			if (mb_strpos($value, $str)) {
				$flag = true;
			}
		}
	}
	return $flag;
}

/**
* Удалим за собой хук
*/
function fn_ee_get_clientID_uninstall() {
	if (fn_ee_get_clientID_check_hook()) {
		$path = 'app/addons/rus_exim_1c/Tygh/Commerceml/RusEximCommerceml.php';
		$str = '$order_xml = $this->getOrderDataForXml($order_data, $cml); fn_set_hook(\'ee_rus_exim_1c_order_data\', $order_data, $order_xml);';
		$newstr = '$order_xml = $this->getOrderDataForXml($order_data, $cml);';
		$file = str_replace($str, $newstr, file($path));
		$fp = fopen($path, 'w+');
		fwrite($fp, implode('', $file)); 
		fclose($fp);		
	}
}

function fn_ee_get_clientID_get_ID($user_id) {
	return db_get_field('SELECT ee_clientID FROM ?:users WHERE user_id = ?i', $user_id);
}

// добавляем поле roistat в xml для моего_склада
function fn_ee_get_clientID_ee_rus_exim_1c_order_data($order_data, &$order_xml) {      	
	//$order_xml['ЗначенияРеквизитов'][0]['ЗначениеРеквизита']['Наименование'] = "ee_clientID";	
	//$order_xml['ЗначенияРеквизитов'][0]['ЗначениеРеквизита']['Значение'] = $order_data['order_id'];	
}
