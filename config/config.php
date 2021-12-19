<?php
	ob_start();
	session_start();
	//database credentials
	
	$user="root";
	$host="localhost";
	$password="";
	$dbname="dbchampions";
	$cxn=mysqli_connect($host,$user,$password,$dbname);
	
	define('DBHOST', $host);
	define('DBUSER',$user);
	define('DBPASS',$password);
	define('DBNAME',$dbname);
	$db = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//set timezone
	date_default_timezone_set('Africa/Harare');
	//load classes as needed
	function __autoload($class) {
	   $class = strtolower($class);
		//if call from within assets adjust the path
	   $classpath = 'classes/class.'.$class . '.php';
	   if ( file_exists($classpath)) {
			require_once $classpath;
		} 	
		//if call from within admin adjust the path
	   $classpath = '../classes/class.'.$class . '.php';
	   if ( file_exists($classpath)) {
		 require_once $classpath;
		}
		//if call from within admin adjust the path
	   $classpath = '../../classes/class.'.$class . '.php';
	   if ( file_exists($classpath)) {
		  require_once $classpath;
		} 		
	}
	$user = new User($db); 
?>