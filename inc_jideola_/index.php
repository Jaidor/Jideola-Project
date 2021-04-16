<?php
ob_start();
session_start();

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

/* Database config */
if(file_exists(dirname(__FILE__).'/db.php')) {
	$params = include(dirname(__FILE__).'/db.php');
}

if(file_exists(dirname(__FILE__).'/config.php')){
	require_once(dirname(__FILE__).'/config.php');
}
if(file_exists(dirname(__FILE__).'/constants.php')) {
	require_once(dirname(__FILE__).'/constants.php');
}

if(file_exists(dirname(__FILE__).'/paths.php')) {
	include(dirname(__FILE__).'/paths.php');
}

/* Require class jideola */
include_once(MVC.'extension/class.jideola.php');
$jideola = new jideolaFunctions($params);
include_once MVC."load.php";
?>
