<?php
$root_path="../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
	
	
	//read current test array
	$successes=$_SESSION['successes'];
	$questions_counter=0;
	if (isset($_SESSION['questions_counter']))
	{
		$questions_count=$_SESSION['questions_counter'];
	}
	echo "Questions counter has an initial value of ".$questions_counter."<br>";
	echo "There are ".$questions_count." questions in the test<br>";
	while ($questions_counter<$questions_count)
	{
		echo $successes[$questions_counter][0]." ";
		echo $successes[$questions_counter][1]." ";
		echo $successes[$questions_counter][2]." ";
		echo $successes[$questions_counter][3]." ";
		echo $questions_counter."<br>";
		$verdict=$successes[$questions_counter][3];
		$question_number=$successes[$questions_counter][2];
		
		//this question previously attempted
		$exist_query="SELECT COUNT(*) as success_counter
					FROM tblsuccesslog
					WHERE subject_code=".$_SESSION['subject_code']."
					AND topic_id=".$_SESSION['topic_id']."
					AND question_number=".$question_number.".
					AND verdict=".$verdict;
		echo $exist_query."<br>";
		$exist_result=mysqli_query($cxn,$exist_query);
		$row=mysqli_fetch_assoc($exist_result);
		extract ($row);
		echo "Success counter is ".$success_counter."<br>";
		
		
		if ($success_counter==0)       //question never attempted
		{
			$verdict_log_query="INSERT INTO tblsuccesslog
								VALUES
								(".$_SESSION['subject_code'].","
								.$_SESSION['topic_id'].","
								.$question_number.","
								.$verdict.",
								1)";
		}
		else if ($success_counter>=1)    //question previously attempted
		{
			$verdict_log_query="UPDATE tblsuccesslog
								SET score_count=score_count+1
								WHERE subject_code=$subject_code
								AND topic_id=$topic_id
								AND question_number=$question_number
								AND verdict=$verdict";
		}
		echo $verdict_log_query."<br>";
		if (!$verdict_log_result=mysqli_query($cxn,$verdict_log_query))
		{
			$_SESSION['verdict_log_message']="Failed to log success status: ".mysqli_error($cxn)."<br>";
		}
		else
		{
			$_SESSION['verdict_log_message']="Success status logged<br>";
		}	
		echo $_SESSION['verdict_log_message'];
		$questions_counter++;
		echo "<br><br>++++++++++++++++++++++++++++++++++++++++++<br><br>";
	}
	header ("Location:./student_home.php");
?>