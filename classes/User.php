<?php
/**
* 
* @category User
*/
	class User {
		/**
		*
		* @var object $db
		* @var array $_data the first true record 
		* @var string $_sessionName
		* @var string $_cookieName
		* @var boolean _isLoggedIn check whether logged in or not
		*/
		private $_db,
				$_data,
				$_sessionName,
				$_cookieName,
				$_isLoggedIn;

		/**
		* initialize method connect to database
		* Set and Check if user infor true or false
		*  
		* @param string $user
		*/
		public function __construct($user = null) {
			$this->_db 			= Database::getInstance();
			$this->_sessionName = Config::get('session/sessionName');
			$this->_cookieName 	= Config::get('remember/cookieName');

			if (!$user) {
				if (Session::exists($this->_sessionName)) {
					$user = Session::get($this->_sessionName);

					if ($this->find($user)) {
						$this->_isLoggedIn = true;
					} else {
						self::logout();
					}
				}
			} else {
				$this->find($user);
			}
		}

		/**
		* update infor
		* 
		* @param array $fields infor content
		* @param interger $id
		*/
		public function update($fields = array(), $id = null) {

			if (!$id && $this->isLoggedIn()) {
				$id = $this->data()->ID;
			}

			if (!$this->_db->update('users', $id, $fields)) {
				throw new Exception("There was a problem updating your details");
			}
		}

		/**
		* Create account
		* 
		* @param array $fields signing up user's infor
		*/
		public function create($fields = array()) {
			if (!$this->_db->insert('users', $fields)) {
				throw new Exception("There was a problem creating your account");
			}
		}

		/**
		* check whether user exists or not
		* 
		* @param string|interger 
		* @return boolean
		*/
		public function find($user = null) {
			if ($user) {
				$fields = (is_numeric($user)) ? 'id' : 'username';	//Numbers in username issues
				$data 	= $this->_db->get('users', array($fields, '=', $user));

				if ($data->count()) {
					$this->_data = $data->first();
					return true;
				}
			}
			return false;
		}

		/**
		*   
		* @param string $username
		* @param string $password
		* @param boolean $remember
		* @return boolean
		*/
		public function login($username = null, $password = null, $remember = false) {
			if (!$username && !$password && $this->exists()) {
				Session::put($this->_sessionName, $this->data()->ID);
			} else {
				$user = $this->find($username);
				if ($user) {
					if ($this->data()->password === Hash::make($password,$this->data()->salt)) {
						Session::put($this->_sessionName, $this->data()->ID);

						if ($remember) {
							$hash = Hash::unique();
							$hashCheck = $this->_db->get('usersSessions', array('userID','=',$this->data()->ID));

							if (!$hashCheck->count()) {
								$this->_db->insert('usersSessions', array(
									'userID' 	=> $this->data()->ID,
									'hash' 		=> $hash
								));
							} else {
								$hash = $hashCheck->first()->hash;
							}
							Cookie::put($this->_cookieName, $hash, Config::get('remember/cookieExpiry'));
						}

						return true;
					}
				}
			}
			return false;
		}

		/**
		* 
		* @param interger $key
		* @return boolean
		*/
		public function hasPermission($key) {
			$group = $this->_db->get('groups', array('ID', '=', $this->data()->userGroup));
			if ($group->count()) {
				$permissions = json_decode($group->first()->permissions,true);

				if ($permissions[$key] == true) {
					return true;
				}
			}
			return false;
		}

		/**
		* check input data whether 's contained in database or not
		*
		* @return boolean
		*/
		public function exists() {
			return (!empty($this->_data)) ? true : false;
		}

		/**
		* delete session in database
		* unset session variable
		* delete cookie variable 
		*
		*/
		public function logout() {
			$this->_db->delete('usersSessions', array('userID', '=', $this->data()->ID));
			Session::delete($this->_sessionName);
			Cookie::delete($this->_cookieName);
		}

		/**
		*
		*@return array return the first true record
		*/
		public function data() {
			return $this->_data;
		}

		/**
		* 
		* @return boolean check user whether logs in or not
		*/
		public function isLoggedIn() {
			return $this->_isLoggedIn;
		}
	}
?>