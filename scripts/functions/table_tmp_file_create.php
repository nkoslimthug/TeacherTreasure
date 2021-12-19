<?php
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
function create_table_file($table_file)
{
	$table_file = '../../tmp/table.php';
	$handle = fopen($table_file, 'w') or die('Cannot open file:  '.$table_file); //implicitly creates file
}
?>