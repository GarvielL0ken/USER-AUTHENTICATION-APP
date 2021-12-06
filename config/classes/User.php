<?php
	class User 
	{
		public /*string	*/	$email;
		public /*string	*/	$passwd;
		public /*string	*/	$username;

		public function __construct(string $username, string $passwd, string $email=null) {
			$this->email = $email;
			$this->passwd = $passwd;
			$this->username = $username;
		}

		/*Used to encrypt either the password passed through or the password stored in the instance*/
		public function encrypt_password(string $passwd=null) {
			if (!$passwd)
				$passwd = $this->passwd;
			
			$this->passwd = password_hash($passwd, 0);
			
			return (true);
		}

		public function in_database(bool $return_field=false) {
			/*array*/	$results;

			$results = select_where_mult('users', 'user_id', 'username', $this->username, 'OR', 'email_address', $this->email);
			if ($return_field) {
				if ($results[0]['username'] == $this->username)
					return ("username");
				else if ($results[0]['email'] == $this->email)
					return ("email");
			}
			else if ($results)
				return (true);
			return (false);
		}
	
		/* Used to add a new user to the database*/
		/* Planned Features: create a verification hash and insert into db for verifing users via email*/
		public function register() {
			/*array*/	$data	=array();

			$data['email'] = $this->email;
			$data['passwd'] = $this->passwd;
			$data['username'] = $this->username;
			insert_new_record('users', $data);
			/*Optional*/
			//create verification hash
			//send email
			//insert new verification hash into database
		}

		public function verify_login_credentials() {
			/*array*/	$results	=null;

			//table
			//fields
			//key, value
			//conditions
			$results = select_where_mult('users', 'password', 'username', $username, 'AND', 'password', $passwd);
		}
	}
?>
