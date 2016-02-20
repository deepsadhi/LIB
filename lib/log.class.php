<?php

class Log
{

	static private $log_path = LOG_PATH;
	static private $log_file = LOG_FILE;
	static private $new_log_file;

	static public function log_action($action, $message=""){
		if(self::check_file_status()){
			if(@$handle = fopen(self::$log_file, 'a')){
				$timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
				$content = "{$timestamp} | {$action}: {$message}\n";
				if(!(@fwrite($handle, $content))){
					die('Could not write to SITE ROOT/logs/log.txt');
				}
				fclose($handle);
			}else{
				die('Could not open SITE ROOT/logs/log.txt file for writing');
			}
		}
	}

	static private function check_file_status(){
		if(!file_exists(self::$log_file)){
			die('Create a log.txt file here SITE ROOT/logs/');
			return false;
		}
		if(!is_writable(self::$log_file)){
			die('Give write permission to SITE ROOT/logs/log.txt');
			return false;
		}
		if(!is_readable(self::$log_path)){
			die('Give read permission to SITE ROOT/logs/log.txt');
			return false;
		}
		return true;
	}

	static public function clear(){
		if(self::check_file_status()){
			file_put_contents(self::$log_file, '');
		}else{
			die('Could not open SITE ROOT/logs/log.txt file for writing');
		}
	}

	static public function display_log(){
		if(self::check_file_status()){
			echo @file_get_contents(self::$log_file);
		}else{
			die('Could not read from file SITE ROOT/logs/log.txt');
		}
	}

	static public function check_password($password){
	/**
	 * Generate password
	 *	$password = '';
	 *	$cost = 10;
	 *	$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
	 *	$salt = sprintf("$2a$%02d$", $cost) . $salt;
	 *	$hash = crypt($password, $salt);
	 *	echo $hash;
	 */

		$hash = '';
		if(crypt($password, $hash) == $hash){
			return true;
		}else{
			return false;
		}
	}
}
