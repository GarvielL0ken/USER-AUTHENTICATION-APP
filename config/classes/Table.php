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
			global $PRIMARY_KEYS;

			$this->name = $name;
			$this->fields = array();
			$this->create_fields($TABLE_FIELD_PAIRS[$name]);
			$this->primary_key = $PRIMARY_KEYS[$name];
		}

		private function create_fields(string $str_fields) {
			/*array*/	$arr_str_fields;

			$arr_fields = explode(", ", $str_fields);
			foreach ($arr_fields as $field) {
				$this->fields[$field] = new Field($field);
			}
		}

		private function generateNonDuplicatableFields(array $data) {
			/*array*/	$field_value_pairs;

			switch ($this->name) {
				case 'authors':
					$field_value_pairs['name'] = $data['name'];
				case 'books':
					$field_value_pairs['title'] = $data['title'];
			}
			return ($field_value_pairs);
		}

		private function prepare_data(bool $set_primary_key=FALSE, bool $set_primary_key_only=FALSE) {
			/*array*/	$data;

			$data = array();
			if (!$set_primary_key_only) {
				foreach ($this->fields as $field) 
					$data[$field->name] = $field->value;
			}
			if ($set_primary_key) {
				$data['primary_key'] = $this->primary_key;
				$data['primary_key_value'] = $this->primary_key_value;
			}
			return ($data);
		}

		public function execute_action() {
			if ($this->action == 'CREATE')
				$response = $this->execute_action_create();
			if ($this->action == 'UPDATE')
				$response = $this->execute_action_update();
			if ($this->action == 'DELETE')
				$response = $this->execute_action_delete();

			return ($response);
		}

		public function execute_action_create() {
			/*array*/	$data;

			$data = $this->prepare_data();
			if (is_in_database_mult($this->name, $this->generateNonDuplicatableFields($data)))
				return (TRUE);
			insert_new_record($this->name, $data);
			return (FALSE);
		}

		public function execute_action_update() {
			/*array*/	$data;

			$data = $this->prepare_data(TRUE);
			print_r($data);
			update_record($this->name, $data);
			return (false);
		}

		public function execute_action_delete() {
			/*array*/	$data;

			$data = $this->prepare_data(TRUE, TRUE);
			delete_record($this->name, $data);
		}

		/*Returns false if user input is valid, otherwise returns an error message*/
		public function validate_user_input(array $post, array $get) {
			global $RGX_PATTERNS;

			/*string*/	$action;
			/*string*/	$regex;

			/*array*/	$data_types;
			/*array*/	$results;

			$action = $get['action'];
			if ($action != 'CREATE' && $action != 'READ' && $action != 'UPDATE' && $action != 'DELETE')
				return ('ERROR: Invalid action');
			$this->action = $action;
			foreach ($this->fields as $field) {
				if ($field->name != 'author_id' && $field->name != 'username') {
					if (!isset($post[$field->name]))
						return ('ERROR: Fill in all neccesary fields. REQUIRED: '.$field->name);
					if ($field->name == 'age' || $field->name == 'year') {
						$regex = $RGX_PATTERNS['numeric'];
						$response = 'Input must contain only numeric characters';
					} else if ($field->name == 'age_group') {
						$regex = $RGX_PATTERNS['alphanumeric'];
						$response = 'Input must contain only alphanumeric character as well as space';
					} else {
						$regex = $RGX_PATTERNS['alpha'];
						$response = 'Input must contain only alpabetical characters and spaces';
					}
					if ($field->name == 'genres') {
						$regex = $RGX_PATTERNS['genres'];
						$response = 'Input must contain only alpabetical characters as well as comma and space';
					}
					if ($field->name == 'role') {
						if ($post[$field->name] != '0' && $post[$field->name] != '1')
							return ('ERROR: User role invalid');
					} else if (!preg_match('/'.$regex.'/', $post[$field->name], $matches)) {
						return ('ERROR: '.$response);
					}
					$this->fields[$field->name]->value = $post[$field->name];
				}
			}
			$this->primary_key_value = $post['primaryKeyValue'];
			if ($get['table'] == 'books') {
				$results = select_where_mult('authors', 'author_id', ['name' => $post['author']]);
				if ($results) {
					$this->fields['author_id'] = new Field('author_id');
					$this->fields['author_id']->value = $results[0]['author_id'];
					unset($this->fields['author']);
				}
				else
					return ('ERROR: Author not found. Make sure that the author is already in the authors table');
			} else if ($get['table'] == 'users') {
				$this->fields['username']->value = $post['username'];
			}
			return (false);
		}
	}
?>