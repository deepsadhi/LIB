<?php
/**
 *  LIB - PHP API for MYSQL CRUD operation and more
 *  
 *  Copyright (C) 2013  Deepak Adhikari <deeps.adhi@gmail.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along
 *  with this program; if not, write to the Free Software Foundation, Inc.,
 *  51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */


require_once(LIB_PATH.DS.'config.inc.php');
require_once(LIB_PATH.DS.'log.class.php');


class Database
{

	private $db_host = DB_HOST;
	private $db_user = DB_USER;
	private $db_pass = DB_PASS;
	private $db_name = DB_NAME;

	private $dbLink;
	private $connection = false;
	
	private $magic_quotes_active;
	private $real_escape_string_exists;

	private $last_query;
	private $result = array();
	private $resultKeys = array();
	private $numResults = null;


	function __construct(){
		$this->connect();
		$this->magic_quotes_active = get_magic_quotes_gpc();
		$this->real_escape_exists = function_exists("mysqli_real_escape_string");
	}

	private function connect(){
		if(!$this->connection){
			$this->dbLink = @mysqli_connect($this->db_host, $this->db_user, $this->db_pass);
			if(mysqli_connect_errno($this->dbLink)){
				error_encountered('Database', 'Could not connect to database => '.mysqli_connect_error($this->dbLink));
				return false;
			}else{
				$select_db = mysqli_select_db($this->dbLink, $this->db_name);
				if($select_db){
					$this->connection = true;
					return true;
				}else{
					error_encountered('Database','Could not select database => '.mysqli_error($this->dbLink));
					return false;
				}
			}
		}else{
			return true;
		}
	}


	public function escape_value($value){
		if($this->real_escape_string_exists){
			if($this->magic_quotes_active){
				$value = stripslashes($value);
			}
			$value = mysqli_real_escape_string($value);
		}else{
			if(!$this->magic_quotes_active){
				$value = addslashes($value);
			}
		}
		return $value;
	}

	private function sanitized_params($params){
		$clean_params = array();
		foreach($params as $key => $value){
			$clean_params[$key] = $this->escape_value($value);
		}
		return $clean_params;
	}

	public function query($sql){
		$this->last_query = $sql;
		$result = mysqli_query($this->dbLink, $sql);
		return $this->confirm_query($result);
	}

	private function confirm_query($result){
		if(!$result){
			error_encountered('Query/Error', $this->last_query.' / '.mysqli_error($this->dbLink));
			return false;
		}else{
			return $result;
		}
	}

	public function tableExists($table){
		$table = $this->escape_value($table);
		$query = 'SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"';
		$tablesInDB = $this->query($query);
		if($tablesInDB){
			if(mysqli_num_rows($tablesInDB)==1){
				return true;
			}else{
				return false;
			}
		}
	}

	public function extractRows($query){
		$numResults = mysqli_num_rows($query);
		$this->numResults = mysqli_num_rows($query);
		for($i=0; $i<$numResults; $i++){
			$r = mysqli_fetch_array($query);
			$key = array_keys($r);
			$this->resultKeys = $key;
			for($x=0; $x<count($key); $x++){
				if(!is_int($key[$x])){
					if($numResults < 1){
						$this->result = null;
					}else{
						$this->result[$i][$key[$x]] = $r[$key[$x]];
					}
				}
			}
		}
	}

	public function sql($sql){
		$sql = $this->escape_value($sql);
		$query = $this->query($sql);
		if($query){
			$this->extractRows($query);
			return true;
		}else{
			return false;
		}
	}

	public function getResult(){
		$value =  $this->result;
		$this->value = array();
		return $value;
	}

	public function getNumResults(){
		$value = $this->numResults;
		$this->numResults = null;
		return $value;
	}

	public function getResultKeys(){
		$value = $this->resultKeys;
		$this->resultKeys = null;
		return $value;
	}

	private function disconnect(){
		if($this->connection){
			if(mysqli_close($this->dbLink)){
				$this->connection = false;
				return true;
			}else{
				return false;
			}
		}
	}

	public function insert($table, $params=array()){
		$table = $this->escape_value($table);
		$params = $this->sanitized_params($params);
		if($this->tableExists($table)){
			$sql = 'INSERT INTO '.$table.' ('.implode(',', array_keys($params)).') VALUES ("'.implode('", "', $params).'")';
			if($insert = $this->query($sql)){
				$this->result = mysqli_insert_id($this->dbLink);
				$this->insertId = mysqli_insert_id($this->dbLink);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function getInsertId(){
		$value = $this->insertId;
		$this->insertId = null;
		return $value;
	}

	public function update($table, $params=array(), $where){
		$table = $this->escape_value($table);
		$where = $this->escape_value($where);
		$params = $this->sanitized_params($params);
		if($this->tableExists($table)){
			$args = array();
			foreach($params as $field => $value){
				$args[] = $field.'="'.$value.'"';
			}
			$sql = 'UPDATE '.$table.' SET '.implode(',', $args).' WHERE '.$where;
			if($query = $this->query($sql)){
				$this->result = mysqli_affected_rows($this->dbLink);
				$this->affectedRows = mysqli_affected_rows($this->dbLink);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function getAffectedRows(){
		$value = $this->affectedRows;
		$this->affectedRows = null;
		return $value;
	}

	public function delete($table, $where=null, $order='date'){
		$table = $this->escape_value($table);
		$where = $this->escape_value($where);
		if($this->tableExists($table)){
			if($where == null){
				$delete = 'DELETE FROM '.$table;
			}else if($where == 'last row'){
				$delete = 'DELETE FROM '.$table.' ORDER BY '.$order.' DESC LIMIT 1';
			}else{
				$delete = 'DELETE FROM '.$table.' WHERE '.$where.' LIMIT 1';
			}
			if($del = $this->query($delete)){
				$this->result = mysqli_affected_rows($this->dbLink);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}


	function __destruct(){
		$this->disconnect();
	}

}
