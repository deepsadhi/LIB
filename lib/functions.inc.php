<?php

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
