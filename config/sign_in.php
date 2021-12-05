<?php
	//ACCESS POINT FOR USERS THAT REQUEST TO SIGN IN

	/*FIRST PARTY IMPORTS*/
	/*THIRD PARTY IMPORTS*/
	/*DEVELOOPER IMPORTS*/
	if (!__ROOT__)
		define('__ROOT__', dirname(__FILE__));

	require_once(__ROOT__.'./lib.php');

	print($_POST[0]);
	print($_POST['username']);
	print($_POST['passwd']);

	$username = $_POST['username'];
	$passwd = $_POST['passwd'];

	$verified = verify_user_credentials($username, $passwd);
	if ($verified)
		/*(IMPLEMENT) : SESSION_ID CREATION*/ 
		header('Location: ../site/browse.html');
	else
		header('Location: ../site/login.html');

?>