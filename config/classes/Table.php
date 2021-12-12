<?php
	/*FIRST PARTY IMPORTS*/
	/*THIRD PARTY IMPORTS*/
	/*DEVELOPER IMPORTS*/
	if (!defined('__ROOT__'))
		define('__ROOT__', dirname(dirname(__FILE__)));
	
	require_once(__ROOT__.'/globals.php');
	require_once(__ROOT__.'/classes/Field.php');

	class Table
	{
		public /*array*/	$fields;

		public /*string*/	$name;
		
		public function __construct(string $name) {
			global $TABLE_FIELD_PAIRS;

			$this->name = $name;
			$this->fields = array();
			$this->create_fields($TABLE_FIELD_PAIRS[$name]);
		}

		private function create_fields(string $str_fields) {
			/*array*/	$arr_str_fields;

			$arr_fields = explode(", ", $str_fields);
			foreach ($arr_fields as $field) {
				$this->fields[$field] = new Field($field);
			}
		}

		public function execute_action() {
			/*array*/	$data;
			print("EXECUTE ACTION: ". $this->action);
			print_r($this->fields);
			if ($this->action == 'CREATE')
				$response = $this->execute_action_create();
			if ($this->action == 'UPDATE')
				$response = $this->execute_action_update();

			return ($response);
		}

		public function execute_action_create() {
			/*array*/	$data;

			$data = array();
			foreach ($this->fields as $field) {
				$data[$field->name] = $field->value;
			}

			insert_new_record($this->name, $data, TRUE);
			return (false);
		}

		public function execute_action_update() {
			return (false);
		}

		/*Returns false if user input is valid, otherwise returns an error message*/
		public function validate_user_input(array $post, array $get) {
			global $RGX_PATTERNS;

			/*string*/	$action;
			/*string*/	$regex;

			/*array*/	$data_types;

			$action = $get['action'];
			if ($action != 'CREATE' && $action != 'READ' && $action != 'UPDATE' && $action != 'DELETE')
				return ('ERROR: Invalid action');
			$this->action = $action;
			foreach ($this->fields as $field) {
				if (!isset($post[$field->name]))
					return ('ERROR: Fill in all neccesary fields. REQUIRED: '.$field->name);
				if ($field->name == 'age' || $field->name == 'year') {
					$regex = $RGX_PATTERNS['numeric'];
					$response = 'Input must contain only numeric characters';
				} else {
					$regex = $RGX_PATTERNS['alpha'];
					$response = 'Input must contain only alpabetical characters and spaces';
				}
				if ($field->name == 'genres') {
					$regex = $RGX_PATTERNS['genres'];
					$response = 'Input must contain only alpabetical characters as well as comma and space';
				}
				print("REGEX: ".$regex." || ");
				print("VALUE: ".$post[$field->name]." || ");
				if (!preg_match('/'.$regex.'/', $post[$field->name], $matches)) {
					print("MATHCES:");
					print_r($matches[0]);
					print(" || ");
					return ('ERROR: '.$response);
				}
				print("MATHCES:");
				print_r($matches[0]);
				print(" || ");
				$this->fields[$field->name]->value = $post[$field->name];
			}
			return (false);
		}
	}
?>