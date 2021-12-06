<?php
	//ACCESS POINT FOR USERS THAT REQUEST TO SIGN IN

	/*FIRST PARTY IMPORTS*/
	/*THIRD PARTY IMPORTS*/
	/*DEVELOOPER IMPORTS*/
	if (!defined('__ROOT__'))
		define('__ROOT__', dirname(__FILE__));

	require_once(__ROOT__.'./lib.php');
	require_once(__ROOT__.'./classes/User.php');
	/*VARIBLES*/
	/*	GLOBAL*/
	/*	LOCAL*/

	/*string*/	$username		="";
	/*string*/	$passwd			="";
	/*string*/	$error_message	=null;
	/*string*/	$response		="";

	/*User*/	$user			="";

	/*SESSION START*/
	session_start();

	/*VALIDATE REQUEST*/
	if (!isset($_POST))
		redirect('../site/login.html?page=sign-in', true);
	if (!is_set_array($_POST, 'username', 'passwd'))
		redirect('../site/login.html?page=sign-in', true);

	$user = new User($_POST['username'], $_POST['passwd']);
	$verified = $user->verify_login_credentials();
	if ($verified) {
		$_SESSION['user'] = serialize($user->to_array());
		header('Location: ../site/browse.html');
	}
	else
		header('Location: ../site/login.html?error-message=Invalid Username or Password', true);

?>