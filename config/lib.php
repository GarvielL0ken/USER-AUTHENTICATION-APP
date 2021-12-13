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
	//string	data_to_sql(array $data, string $modifier=null);
	//void		delete_record(string $table, array $data);
	//array		execute_sql(string $sql, array $data, bool $return_results=TRUE);
	//void		insert_new_record($table, $data, $ignore=FALSE);
	//bool		is_in_database_mult(string $table, string ...$data);
	//bool		is_set_array(array $array, string ...$keys);
	//void		redirect(string $path, bool $exit=false);
	//array		select_all(string $table, string $fields)
	//array		select_where_mult(string $table, string $fields, array $data=null, array $conditions=null);
	//void		update_record(string $table, array $data);
	//bool		valid_email(string $email);
	//bool		invalid_passwd(string $passwd, string $confirm_passwd);
	//bool		valid_username(string $username);
	//bool		verify_user_credentials(string $username, string $passwd);


	function data_to_sql(array $data, string $modifier=null) {
		/*array*/	$keys;

		/*index*/	$index;

		/*string*/	$sql;
		/*string*/	$key_string;

		$index = 0;
		$sql = '';
		$keys = array_keys($data);
		if ($modifier != 'PRIMARY_KEY') {
			foreach ($keys as $key) {
				if ($key != 'primary_key' && $key != 'primary_key_value') {
					$key_string = $key.' = :'.$key;
					if (!$modifier)
						$key_string = '('.$key_string.')';
					$key_string = ' '.$key_string;
					if ($index)
						$key_string = ','.$key_string;
					$sql .= $key_string;
				}
				$index++;
			}
		} else {
			$sql = '('.$data['primary_key'] .'= :primary_key_value)';
		}
		return ($sql);
	}

	function delete_record(string $table, array $data) {
		/*string*/	$sql;

		$sql = 'DELETE FROM '.$table;
		$sql .= ' WHERE'. data_to_sql($data, 'PRIMARY_KEY');
		unset($data['primary_key']);
		
		execute_sql($sql, $data, FALSE);
	}
	
	function execute_sql(string $sql, array $data, bool $return_results=TRUE) {
		/*array*/			$results;
		
		/*PDO Connection*/	$connection;
		/*PDO Statement*/	$statement;

		$connection = connect_to_database();
		$statement = $connection->prepare($sql);
		$statement->execute($data);
		$results = '';
		if ($return_results)
			$results = $statement->fetchAll();
		return ($results);
	}

	function insert_new_record($table, $data, $ignore=FALSE) {
		/*array*/			$keys		=NULL;

		/*string*/			$fields		="";
		/*string*/			$values		="";

		/*PDO Connection*/	$connection	=NULL;
		/*PDO Statement*/	$statement	=NULL;

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
		if ($ignore)
			$ignore = 'IGNORE';
		else
			$ignore = '';

		$sql = 'INSERT '. $ignore . ' INTO '. $table . ' (' . $fields . ') VALUES (' . $values . ')';
		print($sql);
		print_r($data);
		execute_sql($sql, $data, FALSE);
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
	function is_in_database_mult(string $table, array $data) {
		/*array*/	$results	=null;

		$results = select_where_mult($table, '*', $data);
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
	function redirect(string $path, bool $exit=FALSE, bool $print_instead=FALSE) {
		if ($print_instead)
			print($path);
		else
			header('Location: '. $path);
		if ($exit)
			exit();
	}

	function select_all(string $table, string $fields) {
		return (select_where_mult($table, $fields));
	}

	/*Used to get all fields where values match those passed via ...$data
		in any of the fields passed via ...$data
		using the operators passed via ...$data
		in the table passed via $table

		Example:
			select_where_mult('users', 'username', 'John Doe', 'OR', 'email', 'john@doe.com')
		is equavalent to:
			"SELECT FROM users WHERE username = 'John Doe' OR email = 'john@doe.com'"
	*/
	function select_where_mult(string $table, string $fields, array $data=null, array $conditions=null) {
		/*array*/	$keys				=null;
		/*array*/	$results			=null;

		/*int*/		$index				=0;

		/*PDO Connection*/	$connection	=null;
		/*PDO Statement*/	$statement	=null;

		$sql = 'SELECT ';
		if ($fields)
			$sql .= $fields;
		else
			$sql .= '*';
		$sql .= ' FROM ' . $table;

		if (isset($data)) {
			$sql .= ' WHERE';
			$keys = array_keys($data);
			foreach ($keys as $key) {
				$sql .= ' ('.$key.' = :'.$key.')';
				if ($index < count($conditions))
					$sql .= ' '.$conditions[$index];
				$index++;
			}
		}
		
		$connection = connect_to_database();
		$statement = $connection->prepare($sql);
		$statement->execute($data);
		$results = $statement->fetchAll();
		return ($results);
	}
	
	function update_record(string $table, array $data) {
		/*string*/	$sql;
		
		$sql = 'UPDATE '.$table;
		$sql .= ' SET'. data_to_sql($data, 'UPDATE');
		$sql .= ' WHERE'. data_to_sql($data, 'PRIMARY_KEY');
		
		return (execute_sql($sql, $data, FALSE));
	}

	/*Tests the validity of the passed email address against a REGEX*/
	function valid_email(string $email) {
		/*string*/	global	$RGX_EMAIL;

		return (preg_match('/'.$RGX_EMAIL.'/', $email));
	}

	/*Tests the validity of the passed password against a REGEX,
		Tests for equality between passwd and confirm_passwd*/
	function invalid_passwd(string $passwd, string $confirm_passwd) {
		/*string*/	global	$RGX_PASSWD;


		if (strcmp($passwd, $confirm_passwd))
			return ('Password does not match confirmation password');
		if (!preg_match('/'.$RGX_PASSWD.'/', $passwd))
			return ('Invalid password: Password must be between 8 and 32 characters long: ' .
				'Must contain at least one: lowercase, uppercase, numeric and special charater');
		return (false);
	}

	/*Tests the validity of the passed username against a REGEX*/
	function valid_username(string $username) {
		/*string*/	global	$RGX_USERNAME;
		
		return (preg_match('/'.$RGX_USERNAME.'/', $username));
	}

	function verify_user_credentials(string $username, string $passwd) {
		if ($username == 'admin' && $passwd == '123456')
			return (TRUE);
		else
			return (FALSE);
	}
?>