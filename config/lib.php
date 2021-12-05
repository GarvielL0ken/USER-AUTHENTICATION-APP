<?php
	//Library for commonly used functions

	/*FIRST PARTY IMPORTS*/
	/*THIRD PARTY IMPORTS*/
	/*DEVELOOPER IMPORTS*/
	if (!defined('__ROOT__'))
		define('__ROOT__', dirname(__FILE__));
	
	require_once(__ROOT__.'./globals.php');
	require_once(__ROOT__.'./setup.php');
	/*CONTENTS*/
	//bool	is_in_database_mult(string $table, string ...$data);

	//bool	is_set_array(array $array, string ...$keys);
	
	//void	redirect(string $path, bool $exit=false);
	
	//bool	valid_email(string $email);

	//bool	valid_passwd(string $passwd, string $confirm_passwd);
	
	//bool	valid_username(string $username);

	function insert_new_record($table, $data)
	{
		/*array*/			$keys		=null;

		/*string*/			$fields		="";
		/*string*/			$values		="";

		/*PDO Connection*/	$connection	=null;
		/*PDO Statement*/	$statement	=null;

		$keys = array_keys($data);
		$fields = '';
		$values = '';
		foreach ($keys as $field)
		{
			$fields .= $field . ', ';
			$values .= ':' . $field . ', '; 
		}
		$fields = rtrim($fields, ', ');
		$values = rtrim($values, ', ');
		$sql = 'INSERT INTO ' . $table . ' (' . $fields . ') VALUES (' . $values . ')';
		print($sql);
		$connection = connect_to_database();
		$statement = $connection->prepare($sql);
		$statement->execute($data);
	}

	/*Used to check if there are any values mathcing those passed via ...$data
		in any of the fields passed via ...$data
		using the operators passed via ...$data
		in the table passed via $table

		Example:
			is_in_database('users', 'username', 'John Doe', 'OR', 'email', 'john@doe.com')
		is equavalent to:
			"SELECT FROM users WHERE username = 'John Doe' OR email = 'john@doe.com'"
	*/
	function is_in_database_mult(string $table, string ...$data) {
		/*array*/	$field_value_pairs	=array();
		/*array*/	$results			=null;

		/*int*/		$index				=0;
		/*int*/		$operation			=0;

		/*string*/	$field				="";

		/*PDO Connection*/	$connection	=null;
		/*PDO Statement*/	$statement	=null;

		$sql = 'SELECT * FROM '. $table . ' WHERE';
		foreach ($data as $value) {
			$operation = $index % 3;
			
			if (!$operation) {
				$field = $value;
				$sql .= ' ' . $value . ' = ';
			}
			elseif ($operation == 1) {
				$sql .= ':' . $field;
				$field_value_pairs[$field] = $value;
			}
			elseif ($operation == 2) {
				$sql .= ' ' . $value;
			}
			$index++;
		}

		print($sql);
		$connection = connect_to_database();
		$statement = $connection->prepare($sql);
		$statement->execute($field_value_pairs);
		$results = $statement->fetchAll();
		if ($results)
			return (true);
		return(false);
	}

	/*Used to check if a set of keys in an array have been set*/
	/*Takes the array to be checked and the keys that need to be checked*/
	/*Returns True or False*/
	function is_set_array(array $array, string ...$keys) {
		foreach ($keys as $key) {
			if (!isset($array[$key]))
				return (false);
		}
		return (true);
	}

	/*Used to redirect a user to the specified page. Optionally exits the script that called it*/
	function redirect(string $path, bool $exit=false) {
		header('Location: '. $path);
		if ($exit)
			exit();
	}

	/*Tests the validity of the passed email address against a REGEX*/
	function valid_email(string $email) {
		/*string*/	global	$RGX_EMAIL;

		return (preg_match('/'.$RGX_EMAIL.'/', $email));
	}

	/*Tests the validity of the passed password against a REGEX,
		Tests for equality between passwd and confirm_passwd*/
	function valid_passwd(string $passwd, string $confirm_passwd) {
		/*string*/	global	$RGX_PASSWD;

		if (strcmp($passwd, $confirm_passwd))
			return (false);
		return (preg_match('/'.$RGX_PASSWD.'/', $passwd));
	}

	/*Tests the validity of the passed username against a REGEX*/
	function valid_username(string $username) {
		/*string*/	global	$RGX_USERNAME;
		
		return (preg_match('/'.$RGX_USERNAME.'/', $username));
	}

	function verify_user_credentials(string $username, string $passwd) {
		if ($username == 'admin' && $passwd == "123456")
			return (TRUE);
		else
			return (FALSE);
	}
?>