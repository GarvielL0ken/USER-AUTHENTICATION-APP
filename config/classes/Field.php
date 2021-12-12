<?php
	/*FIRST PARTY IMPORTS*/
	/*THIRD PARTY IMPORTS*/
	/*DEVELOPER IMPORTS*/
	if (!defined('__ROOT__'))
		define('__ROOT__', dirname(dirname(__FILE__)));
	
	class Field {
		public	/*any*/		$value;
		
		public	/*string*/	$name;

		public function __construct(string $name) {
			$this->name = $name;
		}
	}