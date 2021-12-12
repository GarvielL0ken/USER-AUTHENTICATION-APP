<?php
	/*FIRST PARTY IMPORTS*/
	/*THIRD PARTY IMPORTS*/
	/*DEVELOOPER IMPORTS*/
	if (!defined('__ROOT__'))
		define('__ROOT__', dirname(dirname(__FILE__)));

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
	
	$post = json_decode(file_get_contents('php://input'), TRUE);
	if (!isset($_GET['table']))
		redirect($path.'?response=Error: no table selected', TRUE, TRUE);
	if (!isset($_GET['action']))
		redirect($path.'?response=Error: no action selected', TRUE, TRUE);
	$page = $_GET['table'];
	$path .= '?page='.$page;

	
	$table = new Table($page);
	$response = $table->validate_user_input($post, $_GET);
	if ($response)	
		redirect($path.'&response='.$response, TRUE, TRUE);
	$response = $table->execute_action();
	if ($response)
		redirect($path.'&response='.$response, TRUE, TRUE);
	redirect($path.'$response=SUCESS', TRUE, TRUE);
?>