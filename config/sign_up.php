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
	/*string*/	$error_message	=null;
	/*string*/	$response		="";

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

	/*VALIDATE USERNAME, EMAIL AND PASSWORD*/
	if (!valid_username($username))
		$error_message = 'Invalid Username: Username must be between 5 and 32 characters long. Allowed chars are: a-z0-9._';
	else if (!valid_email($email))
		$error_message = 'Invalid email address';
	if (!$error_message)
		$error_message = invalid_passwd($passwd, $confirm_passwd);

	if (!$error_message) {
		$user = new User($username, $passwd);
		$response = $user->in_database(true);
		if ($response) {
			if ($response == 'username')
				$error_message = 'Invalid Username: Username already in use';
			if ($response == 'email')
				$error_message = 'Invalid Email: Email address already in use. Got to forgot password page to recover your account';
		}
	}

	if ($error_message)
		redirect('../site/login.html?page=sign-up&error-message='.$error_message, true);

	/*USER REGISTRATION*/
	$user->encrypt_password();
	$user->register();
	redirect('../site/login.html?page=sign-in');
?>