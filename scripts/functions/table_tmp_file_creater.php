<?php
function create_table_file($table_file)
{
	$table_file = '../../../tmp/table.php';
	$handle = fopen($table_file, 'w') or die('Cannot open file:  '.$table_file); //implicitly creates file
}
?>