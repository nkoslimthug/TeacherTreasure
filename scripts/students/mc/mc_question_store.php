<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ("../../functions/sunungura.php");
//include ("../../functions/draw_table33.php");
echo "Current question_counter is ".$_SESSION['question_counter']."<br>";

if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}

function mc_question_store()
{
	$questions_file = "../../../tmp/mc_questions_".$_SESSION['username'].".csv";
	if (!$handle = fopen($questions_file, 'w'))
	{
		$questions_message="Cannot open file:  ".$questions_file."<br>"; //implicitly creates file
		echo $questions_message;
	}
	else
	{
		while($_SESSION['question_counter']<$_SESSION['question_count'])
		{
			if ($_SESSION['question_counter']==0)
			{
				fwrite($handle, 
				$_SESSION['subject_code']."~"
				.$_SESSION['topic_id']."~"
				.$_SESSION['question_number']."~"
				.$_SESSION['question_counter']."~"
				.$_SESSION['question_complete']."~"
				.$_SESSION['choices0']."~"
				.$_SESSION['choices1']."~"
				.$_SESSION['choices2']."~"
				.$_SESSION['choices3']."~"
				.$_SESSION['true_answer']."~"
				.$_SESSION['instruction']."~"
				.$_SESSION['story_id']."~"
				.$_SESSION['image_id']."~"
				.$_SESSION['table_based']."~"
				.$_SESSION['verdict']."~"
				.$_SESSION['submitted_answer']."~"
				.$_SESSION['complete']."~"
				.$_SESSION['username']."~"
				.$_SESSION['start_logtime']);
				//fclose ($handle);
			}
			else
			{
				fwrite($handle, 
				"\n".$_SESSION['subject_code']."~"
				.$_SESSION['topic_id']."~"
				.$_SESSION['question_number']."~"
				.$_SESSION['question_counter']."~"
				.$_SESSION['question_complete']."~"
				.$_SESSION['choices0']."~"
				.$_SESSION['choices1']."~"
				.$_SESSION['choices2']."~"
				.$_SESSION['choices3']."~"
				.$_SESSION['true_answer']."~"
				.$_SESSION['instruction']."~"
				.$_SESSION['story_id']."~"
				.$_SESSION['image_id']."~"
				.$_SESSION['table_based']."~"
				.$_SESSION['verdict']."~"
				.$_SESSION['submitted_answer']."~"
				.$_SESSION['complete']."~"
				.$_SESSION['username']."~"
				.$_SESSION['start_logtime']); 
			}
		}
	}
	fclose ($handle);
}
?>