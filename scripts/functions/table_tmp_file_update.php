<?php
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
function update_table($table_file)
{
	$table_file = '../../../tmp/table.php';
	$handle = fopen($table_file, 'w') or die('Cannot open file:  '.$table_file);
	fwrite($handle, "<?php\n");
	fwrite($handle,"function draw_math_table()\n{\n\t");
	fwrite($handle, $_SESSION['question_complete'].";"); //store table function
	fwrite($handle, "\n}\n");
	fwrite($handle, "?>");		
	fclose ($handle);
	return $current_table;
}
?>