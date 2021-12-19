<?php
function log_attempted()
{
	include ("../../functions/sunungura.php");
	$_SESSION['attempt_log_query']="INSERT INTO tblscheduledwritten
						(test_owner,subject_code,grade,subject_counter,student)
						VALUES
						('".$_SESSION['test_owner']."',
						".$_SESSION['subject_code'].",
						".$_SESSION['student_grade'].",
						".$_SESSION['test_counter'].",
						'".$_SESSION['username']."')";
	if (!$attempt_log_result=mysqli_query($cxn,$_SESSION['attempt_log_query']))
	{
		$_SESSION['attempt_log_message']="Failed to log attempt: ".mysqli_error($cxn)."<br>";
	}
	else
	{
		$_SESSION['attempt_log_message']="Test attempt has been logged<br>";
	}
	echo $_SESSION['attempt_log_message'];
}
?>