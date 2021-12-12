<?php
	/*FIRST PARTY IMPORTS*/
	/*THIRD PARTY IMPORTS*/
	/*DEVELOOPER IMPORTS*/
	if (!defined('__ROOT__'))
		define('__ROOT__', dirname(__FILE__));

	require_once(__ROOT__.'./lib.php');
	require_once(__ROOT__.'./classes/Table.php');
	require_once(__ROOT__.'./classes/User.php');

	/*string*/	$response;
	/*string*/	$page;
	/*string*/	$path;

	/*Table*/	$table;
	/*User*/	$user;
	
	/*session_start();
	$user = new User($_SESSION['username']);
	$user->verify_access('librarian');*/
	
	$page = '';
	$path = '../site/edit.html';
	if (!isset($_POST) || !isset($_GET))
		redirect($path.'&response=an error occured', true);
	if (!isset($_GET['page']))
		redirect($path.'&response=Error: no table selected', true);
	if (!isset($_GET['action']))
		redirect($path.'&response=Error: no action selected', true);
	$page = $_GET['page'];
	$path .= '?page='.$page;

	$table = new Table($page);
	$response = $table->validate_user_input($_POST, $_GET);
	print($response);
	if ($response)	
		redirect($path.'&response='.$response, true);
	$response = $table->execute_action();
	print($response);
	if ($response)
		redirect($path.'&response='.$response, true);
	redirect($path);
?>