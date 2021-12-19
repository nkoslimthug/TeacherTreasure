<?php
$root_path="../../../";
include($root_path."config/paths.php");
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}

function mc_table_load()
{
	include ("../../functions/sunungura.php");
	$_SESSION['dataload_query']="LOAD DATA LOCAL INFILE '../../../tmp/".$_SESSION['username']."_table_dump.csv' 
					INTO TABLE tblmctestlog 
					FIELDS TERMINATED BY '~' ";
	echo $_SESSION['dataload_query']."<br>";
	if (!$result=mysqli_query($cxn,$_SESSION['dataload_query']))
	{
		$_SESSION['dataload_message']="<br>Failed to load question :".mysqli_error($cxn)."<br>";
	}
	else
	{
		$_SESSION['dataload_message']="<br>Question logged<br>";
	}
	echo $_SESSION['dataload_message'];
}

?>
