<?php
	/*FIRST PARTY IMPORTS*/
	/*THIRD PARTY IMPORTS*/
	/*DEVELOOPER IMPORTS*/
	if (!defined('__ROOT__'))
		define('__ROOT__', dirname(dirname(__FILE__)));
	
	require_once(__ROOT__.'/globals.php');
	require_once(__ROOT__.'/lib.php');
	
	global $TABLE_FIELD_PAIRS;
	if (isset($_GET)) {
		
		if (isset($_GET['table'])) {
			$table = $_GET['table'];
			$fields = $TABLE_FIELD_PAIRS[$table];
			$results = select_all($table, $fields);

			print(json_encode($results));
		}
	}
	
	//$results = select_where_mult()
?>