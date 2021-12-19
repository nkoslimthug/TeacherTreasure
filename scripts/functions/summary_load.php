<?php
$root_path="../../";

function summary_load()
{
	include ($root_path."config/paths.php");
	include ($root_path."config/sungunura.php");
	
	$username=$_SESSION['username'];
	//include ($root_path."config/sungunura.php");
	/*echo "Current user is ".$username."<br>";
	echo "Current subject is ".$subject."<br>";
	echo "Chosen topic is ".$topic."<br>";
	echo "Question type is ".$question_type."<br>";
	echo "Score is ".$_SESSION['percentage']."<br>";*/
	$teststart_query="SELECT start_date 
						FROM tblteststart
						WHERE username='".$_SESSION['username']."'";
	//echo $teststart_query."<br>";
	if (!$teststart_result=mysqli_query($cxn,$teststart_query))
	{	
		$_SESSION['teststart_message']="Failed to retrieve start date :".mysqli_error($cxn)."<br>"; 
	}
	else
	{	
		$_SESSION['teststart_message']="Test start message retrieved<br>"; 
	}
	$row=mysqli_fetch_assoc($teststart_result);
	extract ($row);
	$_SESSION['start_logtime']=$start_date;
	echo "Start logtime before insert is ".$_SESSION['start_logtime']."<br>";
	
	echo "Test was started at ".$_SESSION['start_logtime']."<br>";
	echo "Test was completed at ".$_SESSION['end_timestamp']."<br>";
	echo "Test was started at ".$_SESSION['start_timestamp']."<br>";
	echo "Test was completed at ".$_SESSION['end_timestamp']."<br>";
	$forename=$_SESSION['forename'];
	$lastname=$_SESSION['lastname'];
	$percentage=$_SESSION['percentage'];
	$start_date=$_SESSION['start_logtime'];
	$end_date=$_SESSION['end_timestamp'];
	$score_log_query="INSERT INTO tblperformance
						VALUES
						('$username',
						 '$forename',
						 '$lastname',
						'$subject',
						'$topic',
						'$question_type',
						$percentage,
						'".$_SESSION['start_logtime']."',
						'$end_date')";
		//echo $score_log_query."<br>";
	if (!$score_log_result=mysqli_query($cxn,$score_log_query))
	{
		echo "Current database is $dbname";
		$_SESSION['score_log_msg']="Failed to log performance ".mysqli_error($cxn)."<br>";
		//header ("Location:student_home.php");
		//exit;
	}
	else
	{
		$_SESSION['score_log_msg']="Score recorded<br>";
	}	
	//echo $_SESSION['score_log_msg']."<br>";
}
	


?>