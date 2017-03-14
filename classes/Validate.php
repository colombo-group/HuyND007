<?php
/**
* 
* @category Validate
*/
	class Validate {
		/**
		*
		* @var boolean $_passed
		* @var array $_errors 
		* @var object $_db connect database
		*/
		private $_passed = false,
				$_errors = array(),
				$_db = null;

		/**
		* initialize object _db
		*
		*/
		public function __construct() {
			$this->_db = Database::getInstance();
		}

		/**
		* check condition when sign in or sign up
		*
		* @param string $source $_POST or $_GET
		* @param array $items
		* @return Validate
		*/
		public function check($source, $items = array()) {
			foreach ($items as $item => $rules) {
				foreach ($rules as $rule => $rule_value) {
					$value 	= trim($source[$item]);
					$item 	= escape($item);
					
					if ($rule === 'required' && empty($value)) {
						$this->addError("{$item} is required");	//ToDo: Pick up 'name' value
					} else if (!empty($value)) {
						switch ($rule) {
							case 'min':
								if (strlen($value) < $rule_value) {
									$this->addError("{item} must be a minimum of {$rule_value} characters");
								}
								break;
							case 'max':
								if (strlen($value) > $rule_value) {
									$this->addError("{item} must be no longer than {$rule_value} characters");
								}
								break;
							case 'matches':
								if ($value != $source[$rule_value]) {
									$this->addError("{$rule_value} must match {$item}");
								}
								break;
							case 'unique':
								$check = $this->_db->get($rule_value,array($item, '=' , $value));
								if ($check->count()) {
									$this->addError("{$item} already exists");
								}
								break;
						}
					}
				}
			}

			if (empty($this->_errors)) {
				$this->_passed = true;
			}

			return $this;
		}

		/**
		* Add error to array.
		* 
		* @param array|null $error
		*/
		private function addError($error) {
			$this->_errors[] = $error;
		}


		/**
		* return error
		*
		* @return array 
		*/
		public function errors() {
			return $this->_errors;
		}

		/**
		* check whether sign in, sign up completely or not
		*
		* @return boolean
		*/
		public function passed() {
			return $this->_passed;
		}
	}
?>