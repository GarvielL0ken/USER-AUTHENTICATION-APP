<?php
	class User 
	{
		public /*string	*/	$email;
		public /*string	*/	$passwd;
		public /*string	*/	$username;

		public function __construct(string $username, string $email, string $passwd) {
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

		public function in_database() {
			if (is_in_database_mult('users', 'username', $this->username, 'OR', 'email_address', $this->email))
				return (false);
			return (true);
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
	}
?>
