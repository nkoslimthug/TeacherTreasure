<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
include ("../../functions/draw_table33.php");
function success_log($subject_code,$topic_id,$question_number)
{
	include ("./sunungura.php");
	
	$current_query="SELECT subject_code,topic_id,question_number,question_counter,verdict
					FROM tblmctestlog 
					WHERE subject_code=$subject_code
					AND topic_id=$topic_id
					AND question_number=$question_number";
	$current_result=mysqli_query($cxn,$current_query);
	
	while ($row=mysqli_fetch_assoc($current_result))
	{
		$exist_query="SELECT COUNT(*) AS question_count 
							  FROM tblmctestlog
							  WHERE subject_code=$subject_code
							  AND topic_id=$topic_id
							  AND question_number=$question_number
							  GROUP BY subject_code,topic_id,question_number,verdict
							  ORDER BY verdict";
		echo $exist_query."<br>";
		$exist_result=mysqli_query($cxn,$exist_query);
		$row=mysqli_fetch_assoc($exist_result);
		extract ($row);
		echo $question_count."<br>";
		//	echo "Question count is ".$question_count."<br>";
		if ($question_count==1)       //question never attempted
		{
			$verdict_log_query="INSERT INTO tblsuccesslog
								VALUES
								($subject_code,$topic_id,$question_number,$verdict,$question_count)";
		}
		else if ($question_count>1)    //question previously attempted
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
	}
}

?>