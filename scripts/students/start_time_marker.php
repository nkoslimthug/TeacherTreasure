<?php
$root_path="../../";
include ($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
include ("./student_cleanup.php");
	
if ($_SESSION['logged_in']!="T")
{
	session_destroy();
	$_SESSION['login_message']="Please log in before accessing that page<br>";
	header ("Location:../../index.php");
}

//Retrieve start_time
$start_time_query="SELECT start_time 
					FROM tblteststart
					WHERE username='".$_SESSION['username']."'";
$start_time_result=mysqli_query($cxn,$start_time_query);
$row=mysqli_fetch_assoc($start_time_result);
extract ($row);
echo "Start time is ".$start_time."<br>";

//Retrieve MAX logged time
$max_time_query="SELECT MAX(start_time) AS max_time 
			FROM tblmctestlog 
			WHERE username='".$_SESSION['username']."'";
$max_time_result=mysqli_query($cxn,$max_time_query);
$row=mysqli_fetch_assoc($max_time_result);
extract ($row);

//Adjust start time
$start_time_update="UPDATE tblmctestlog 
					SET start_time='".$start_time."' 
					WHERE username='".$_SESSION['username']."' 
					AND start_time='".$max_time."'" ;
echo $start_time_update."<br>";	
if (!$start_time_result=mysqli_query($cxn,$start_time_update))
{
	$start_time_message="Failed to update start_time: ".mysqli_error($cxn)."<br>";
}
else
{
	$start_time_message="Start time updated<br>";
}
echo $start_time_message;
foreach ($_POST as $key => $value)
{
	echo "$key = $value<br>";
	if ($value=='End Test')
	{
		header ("Location:./success_log.php");
	}
	if ($value=='Detailed Results')
	{
		$_SESSION['test_key']=$key.'+'.$start_time;
		header ("Location:./mc/scores_process.php");
	}
}
?>