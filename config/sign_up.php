<?php
	//ACCESS POINT FOR USERS THAT REQUEST TO SIGN IN

	/*FIRST PARTY IMPORTS*/
	/*THIRD PARTY IMPORTS*/
	/*DEVELOOPER IMPORTS*/
	if (!defined('__ROOT__'))
		define('__ROOT__', dirname(__FILE__));

	require_once(__ROOT__.'./classes/User.php');
	require_once(__ROOT__.'./globals.php');
	require_once(__ROOT__.'./lib.php');
	/*VARIBLES*/

	/*	GLOBAL*/
	/*string*/	global	$RGX_USERNAME;

	/*	LOCAL*/

	/*string*/	$username		="";
	/*string*/	$email			="";
	/*string*/	$passwd			="";
	/*string*/	$confirm_passwd	="";

	/*User*/	$user			="";

	/*SESSION START*/
	session_start();

	/*VALIDATE REQUEST*/
	if (!isset($_POST))
		redirect('../site/login.html?page=sign-in', true);
	if (!is_set_array($_POST, 'username', 'email_address', 'passwd', 'confirm_passwd'))
		redirect('../site/login.html?page=sign-in', true);

	/*INITIALIZE VARIABLES*/
	$username = $_POST['username'];
	$email = $_POST['email_address'];
	$passwd = $_POST['passwd'];
	$confirm_passwd = $_POST['confirm_passwd'];

	if (!valid_username($username) || !valid_email($email) || !valid_passwd($passwd, $confirm_passwd))
		redirect('../site/login.html?page=sign-in', true);

	$user = new User($username, $email, $passwd);
	$user->encrypt_password();
	if ($user->in_database())
		redirect('../site/login.html?page=sign-in', true);
	$user->register();
	$_SESSION['user'] = $user;
	redirect('../site/browse.html');
?>