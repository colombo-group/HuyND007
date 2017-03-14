<?php
/**
* 
*@category Database
*/
	class Database {
		/**
		*
		* @var string $_instance
		*/
		private static $_instance = null;

		/**
		*
		* @var object $_pdo connect to database
		* @var string $_query use to execute database
		* @var boolean $_erro Notice if something's wrong, default return false
		* @var array $_results Save result after execute
		* @var interger $_count Save number of $_resutlt's record
		*/
		private $_pdo,
				$_query,
				$_error = false,
				$_results,
				$_count = 0;

		/**
		*config database
		*/
		private function __construct() {
			try {
				$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'));
			} catch (PDOException $e) {
				die($e->getMessage());
			}
		}

		/**
		*
		* @return object Database
		*/
		public static function getInstance() {
			if (!isset(self::$_instance)) {
				self::$_instance = new Database();
			}
			return self::$_instance;
		}


		/**
		* 
		* @param string $sql sql command
		* @param array $params
		* @return \Database
		*/
		public function query($sql, $params = array()) {
			$this->_error = false;
			if ($this->_query = $this->_pdo->prepare($sql)) {
				$x = 1;
				if (count($params)) {
					foreach ($params as $param) {
						$this->_query->bindValue($x, $param);
						$x++;
					}
				}

				if ($this->_query->execute()) {
					$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
					$this->_count	= $this->_query->rowCount();
				} else {
					$this->_error = true;
				}
			}

			return $this;
		}

		/**
		* execute sql command
		*
		* @param string $action sql command
		* @param string $table name of table in database
		* @param array $where find record to execute
		*/
		public function action($action, $table, $where = array()) {
			if (count($where) === 3) {	//Allow for no where
				$operators = array('=','>','<','>=','<=','<>');

				$field		= $where[0];
				$operator	= $where[1];
				$value		= $where[2];

				if (in_array($operator, $operators)) {
					$sql = "{$action} FROM {$table} WHERE ${field} {$operator} ?";
					if (!$this->query($sql, array($value))->error()) {
						return $this;
					}
				}
			}
			return false;
		}

		/**
		* get suitable record in database
		* 
		* @param string $table name of table in database
		* @param array $where find record to get
		*/
		public function get($table, $where) {
			return $this->action('SELECT *', $table, $where); //ToDo: Allow for specific SELECT (SELECT username)
		}

		/**
		* delete record in database that user want
		* 
		* @param string $table name of table in database
		* @param array $where find record to delete
		*/
		public function delete($table, $where) {
			return $this->action('DELETE', $table, $where);
		}

		/**
		* add record to database
		* 
		* @param $string name of table in database
		* @param array $field infor needing to add 
		*/
		public function insert($table, $fields = array()) {
			if (count($fields)) {
				$keys 	= array_keys($fields);
				$values = null;
				$x 		= 1;

				foreach ($fields as $field) {
					$values .= '?';
					if ($x<count($fields)) {
						$values .= ', ';
					}
					$x++;
				}

				$sql = "INSERT INTO {$table} (`".implode('`,`', $keys)."`) VALUES({$values})";

				if (!$this->query($sql, $fields)->error()) {
					return true;
				}
			}
			return false;
		}

		/**
		* change infor of a specify record in database
		* 
		* @param string $table name of table
		* @param interger $id position of record needing to change
		* @param array $fields new infor
		*/
		public function update($table, $id, $fields = array()) {
			$set 	= '';
			$x		= 1;

			foreach ($fields as $name => $value) {
				$set .= "{$name} = ?";
				if ($x<count($fields)) {
					$set .= ', ';
				}
				$x++;
			}

			$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
			
			if (!$this->query($sql, $fields)->error()) {
				return true;
			}
			return false;
		}

		/**
		* data of record after get from database
		* 
		* @return array
		*/
		public function results() {
			return $this->_results;
		}

		/**
		* data of the first record after get from database
		* 
		* @return array
		*/
		public function first() {
			return $this->_results[0];
		}

		/**
		* Notice execute sql command success or not
		* 
		* @return boolean
		*/
		public function error() {
			return $this->_error;
		}

		/**
		* count record gotten
		* 
		* @return int
		*/
		public function count() {
			return $this->_count;
		}
	}
?>