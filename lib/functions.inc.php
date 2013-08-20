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


function class_autoloader($class){
	if(file_exists(LIB_PATH.DS.strtolower($class).'.class.php')){
		require_once(LIB_PATH.DS.strtolower($class).'.class.php');
	}else{
		$msg = 'SITE ROOT/lib/'.strtolower($class). '.class.php missing';
		error_encountered('Class', $msg);
	}
}

function redirect_to($location = null){
	if($location != null){
		header("Location: {$location}");
		exit;
	}
}

function hack_attempt(){
	$ip = $_SERVER['REMOTE_ADDR'];
	Log::log_action($ip, 'Hacking attempt?');
	die('Hacking attempt?');
}

function error_encountered($action, $msg){
	Log::log_action($action, $msg);
	die('Error! encountered');
#	die("<b>Error:</b><br/>{$action}<br/>{$msg}");
}
