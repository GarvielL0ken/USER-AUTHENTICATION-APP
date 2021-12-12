<?php
	class User 
	{
		public /*string	*/	$email;
		public /*string	*/	$passwd;
		public /*string	*/	$username;

		public function __construct(string $username, string $passwd=null, string $email=null) {
			$this->email = $email;
			$this->passwd = $passwd;
			$this->username = $username;
		}

		/*Used to encrypt either the password passed through or the password stored in the instance*/
		public function encrypt_password(string $passwd=null) {
			if (!$passwd)
				$passwd = $this->passwd;
			$this->passwd = password_hash($this->passwd, PASSWORD_DEFAULT);
			return (true);
		}

		public function in_database(bool $return_field=false) {
			/*array*/	$conditions	=null;
			/*array*/	$data		=null;
			/*array*/	$results	=null;

			$data = array('username' => $this->username);
			if ($this->email) {
				$data['email_address'] = $this->email;
				$conditions = array('OR');
			}
			$results = select_where_mult('users', 'user_id', $data, $conditions);
			if ($return_field && $results) {
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

			$data['email_address'] = $this->email;
			$data['passwd'] = $this->passwd;
			$data['username'] = $this->username;
			insert_new_record('users', $data);
			/*Optional*/
			//create verification hash
			//send email
			//insert new verification hash into database
		}

		public function verify_login_credentials() {
			/*array*/	$conditions	=null;
			/*array*/	$data		=null;
			/*array*/	$results	=null;
			/*string*/	$fields		='';

			$data = array('username' => $this->username);
			$conditions = array();
			$results = select_where_mult('users', $fields, $data, $conditions);
			if ($results) {
				$results = $results[0];
				if (password_verify($this->passwd, $results['passwd']))
					return ('Username and Password do not match');
				if (/*['verified']*/true) {
					$this->id = $results['user_id'];
					$this->email = $results['email_address'];
					$this->role = $results['role'];
					$this->passwd = null;
					return ('ok');
				} else
					return ('Please verifiy your account via the verification email sent to your email address');
			} else {
				return ('Username and Password do not match');
			}
		}
	}
?>
