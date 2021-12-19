<?php
$root_path="../../../";
include($root_path."config/paths.php");
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
//include ($root_path."config/sungunura.php");

//include ("../../../tmp/table.php");
//include ("../../functions/draw_table33.php");
function mc_questions_load()
{
	include ("../../functions/sunungura.php");
	$dataload_query="LOAD DATA LOCAL INFILE '../../../tmp/mc_questions_".$_SESSION['username'].".csv' 
					INTO TABLE tblmctestlog 
					FIELDS TERMINATED BY '~' ";
	echo $dataload_query."<br>";
	$result=mysqli_query($cxn,$dataload_query);
	/*{
		$_SESSION['dataload_message']="Failed to load question :".mysqli_error($cxn)."<br>";
	}
	else
	{
		$_SESSION['dataload_message']="Question logged<br>";
	}
	echo $_SESSION['dataload_message'];*/
}

?>
