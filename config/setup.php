<?php
	//Connect to database, initilize database if not already initilized and return the connection

	/*FIRST PARTY IMPORTS*/
	/*THIRD PARTY IMPORTS*/
	/*DEVELOOPER IMPORTS*/
	if (!defined('__ROOT__'))
		define('__ROOT__', dirname(__FILE__));

	require_once(__ROOT__.'/database.php');

	/*Create a PDO object using the values specified in database.php,
		Then create a database with the specified database name if it does not already exist
		Create specified tables if they don't exist
		return the connection to allow outside methods to make calls to the database
	*/
	function connect_to_database()
	{
		global $dbhost, $dbname, $dbusername, $dbpassword;
		try {
			$conn = new PDO("mysql:host=$dbhost", $dbusername, $dbpassword);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->exec("CREATE DATABASE IF NOT EXISTS $dbname");
			$conn->query("use $dbname");
			$sql = "CREATE TABLE IF NOT EXISTS `authors` (
				`author_id`		INT(4)			AUTO_INCREMENT	PRIMARY KEY,
				`name`			CHAR(32)		UNIQUE,
				`age`			CHAR(32)		NOT NULL,
				`genres`		CHAR(255)		NOT NULL
				);";
			$conn->exec($sql);
			$sql = "CREATE TABLE IF NOT EXISTS `books` (
				`book_id`		INT(4)			AUTO_INCREMENT	PRIMARY KEY,
				`title`			CHAR(128)	NOT NULL,
				`age_group`		CHAR(64)		NOT NULL,
				`genre`			CHAR(64)		NOT NULL,
				`year`			YEAR			NOT NULL,
				`author_id`		INT(4),
				FOREIGN KEY (author_id) REFERENCES authors(author_id)
				);";
			$conn->exec($sql);
			$sql = "CREATE TABLE IF NOT EXISTS `users` (
				`user_id`		INT(4)			AUTO_INCREMENT	PRIMARY KEY,
				`username`		CHAR(32)		NOT NULL,
				`email_address`	CHAR(64)		NOT NULL,
				`passwd`		CHAR(255)	NOT NULL,
				`role`			BOOL			NOT NULL		DEFAULT 0
				);";
			$conn->exec($sql);
			return ($conn);
		} catch (PDOException $pe) {
			die("Could not connect to the database $dbname :" . $pe->getMessage());
		}
	}
?>