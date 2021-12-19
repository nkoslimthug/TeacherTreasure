<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ("../../functions/sunungura.php");
//include ("../../functions/draw_table33.php");
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}

function mc_table_dump()
{
	$table_file = "../../../tmp/".$_SESSION['username']."_table_dump.csv";
	if ($_SESSION['question_counter']==1)
	{
		$handle = fopen($table_file, 'w') or die('Cannot open file:  '.$table_file); //implicitly creates file
		fwrite($handle, 
		$_SESSION['subject_code']."~"
		.$_SESSION['topic_id']."~"
		.$_SESSION['question_number']."~"
		.$_SESSION['question_counter']."~"
		.$_SESSION['choices0']."~"
		.$_SESSION['choices1']."~"
		.$_SESSION['choices2']."~"
		.$_SESSION['choices3']."~"
		.$_SESSION['true_answer']."~"
		.$_SESSION['instruction']."~"
		.$_SESSION['story_id']."~"
		.$_SESSION['image_id']."~"
		.$_SESSION['question_type']."~"
		.$_SESSION['verdict']."~"
		.$_SESSION['submitted_answer']."~"
		.$_SESSION['complete']."~"
		.$_SESSION['username']."~"
		.$_SESSION['start_time']); //store table function
		fclose ($handle);

	}
	else
	{
		$handle = fopen($table_file, 'a') or die('Cannot open file:  '.$table_file); //implicitly creates file
		fwrite($handle, 
		"\n".$_SESSION['subject_code']."~"
		.$_SESSION['topic_id']."~"
		.$_SESSION['question_number']."~"
		.$_SESSION['question_counter']."~"
		.$_SESSION['choices0']."~"
		.$_SESSION['choices1']."~"
		.$_SESSION['choices2']."~"
		.$_SESSION['choices3']."~"
		.$_SESSION['true_answer']."~"
		.$_SESSION['instruction']."~"
		.$_SESSION['story_id']."~"
		.$_SESSION['image_id']."~"
		.$_SESSION['question_type']."~"
		.$_SESSION['verdict']."~"
		.$_SESSION['submitted_answer']."~"
		.$_SESSION['complete']."~"
		.$_SESSION['username']."~"
		.$_SESSION['start_time']); //store table function
		fclose ($handle);
	}
}
?>