<?php
//include($root_path."config/paths.php");
include ("../../../config/sungunura.php");

/*if ($_SESSION['logged_in']!="T")
{
	//session_destroy();
	$_SESSION['login_message']="Please log in before accessing that page<br>";
	header ("Location:../../index.php");
}
	*/
	
//Load questions
//$load_questions();

//Load stories
//$load_stories();

//Load images
//$load_images()	
	
$load_query="LOAD DATA LOCAL 
			INFILE '../../../data/plus_content.csv' 
			INTO TABLE tblmcquestions 
			FIELDS TERMINATED BY '~'";
			
if (!$load_result=mysqli_query($cxn,$load_query))
{
	$_SESSION['load_message']="Data load failed: ".mysqli_error($cxn)."<br>";
	header("Location:../administrator_home.php");
	exit;
}
else
{
	$_SESSION['load_message']="Data load successful<br>";
	header("Location:../administrator_home.php");
	exit;
}
echo $_SESSION['load_message'];
?>