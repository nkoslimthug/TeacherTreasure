<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");

foreach ($_POST as $key => $value)
{
	echo "$key = $value<br>";
	
	//C+terrence+Mathematics+1+6+3 = Continue
	//unbundle key
	$dummy="";
	$test_owner="";
	$subject="";
	$topic_id=0;
	$grade=0;
	$subject_counter=0;
	$plus_counter=0;
	$char_counter=0;
	$table_file="";
	$char_count=strlen($key);
	while ($char_counter<$char_count)
	{
		$current_char=substr($key,$char_counter,1);
		//echo "Character $char_counter is ".$current_char."<br>";
		//$char_counter++;
		switch ($plus_counter)
		{
			case 0:
				if ($current_char=="+")
				{
					$plus_counter++;
					$test_owner=""; 
					continue;
				}
				else
				{
					$dummy=$dummy.$current_char;
				}
		
			case 1:
				if ($current_char=="+")
				{
					$plus_counter++;
					$subject="";
					continue;
				}
				else
				{
					$test_owner=$test_owner.$current_char;
				}
			
			case 2:
				if ($current_char=="+")
				{
					$plus_counter++;
					$topic_id="";
					continue;
				}
				else
				{
					if ($current_char=="_")
					{
						$current_char=" ";
					}
					$subject=$subject.$current_char;
				}
			case 3:
				if ($current_char=="+")
				{
					$plus_counter++;
					$grade="";
					continue;
				}
				else
				{
					if ($current_char=="_")
					{
						$current_char=" ";
					}
					$topic_id=$topic_id.$current_char;
				}
			case 4:
				if ($current_char=="+")
				{
					$plus_counter++;
					$subject_counter="";
					continue;
				}
				else
				{
					if ($current_char=="_")
					{
						$current_char=" ";
					}
					$grade=$grade.$current_char;
				}
			
			case 5:
				if ($current_char=="+")
				{
					$plus_counter++;
					continue;
				}
				else
				{
					$subject_counter=$subject_counter.$current_char;
				}
		}
		$char_counter++;
	} 
	echo "Dummy is ".$dummy="<br>";
	echo "Test owner is ".$test_owner."<br>";
	echo "Subject is ".$subject."<br>";
	echo "Topic ID is ".$topic_id."<br>";
	echo "Grade is ".$grade."<br>";
	echo "Subject counter is ".$subject_counter."<br>";
	$_SESSION['subject_name']=$subject;
	$_SESSION['topic_id']=$topic_id;
	$_SESSION['grade']=$grade;
	
	
	//Total questions in current topic
		$count_query="SELECT COUNT(*) AS question_count 
					FROM tblmcquestions 
					WHERE subject_code=".$_SESSION['subject_code']." AND topic_id=".$_SESSION['topic_id'];
		//echo $count_query."<br>";
		$count_result=mysqli_query($cxn,$count_query);
		$row=mysqli_fetch_assoc($count_result);
		extract ($row);
		$_SESSION['total_questions']=$question_count;
		
	
	
	if ($value=='Continue')
	{
		echo "Proceeding to capture<br>";                //determine number of questions captured so far
		$test_counter_query="SELECT COUNT(*) AS test_counter   
							FROM tblmctestdetail
							WHERE 	test_owner='".$test_owner."'
								AND subject_code=".$_SESSION['subject_code']."
								AND topic_id=".$topic_id."
								AND grade=".$grade."
								AND subject_counter=".$subject_counter;
		echo $test_counter_query."<br>";
		if (!$test_counter_result=mysqli_query($cxn,$test_counter_query))
		{
			$_SESSION['$test_counter_message']="Failed to count questions :".mysqli_query($cxn)."<br>";
		}
		else
		{
			$row=mysqli_fetch_assoc($test_counter_result);
			extract ($row);
			$_SESSION['question_counter']=$test_counter;
			$_SESSION['test_counter']=$test_counter;
			echo "Test counter =".$_SESSION['test_counter']."<br>";
			//header ("Location:./mc_maketest.php");			
		}
		//create array of accepted questions
		//populate questions already accepted
		$accepted_query="SELECT * 
							FROM tblmctestdetail
							WHERE 	test_owner='".$test_owner."'
								AND subject_code=".$_SESSION['subject_code']."
								AND topic_id=".$topic_id."
								AND grade=".$grade."
								AND subject_counter=".$subject_counter;
		echo $accepted_query."<br>";
		if (!$accepted_result=mysqli_query($cxn,$accepted_query))
		{
			$_SESSION['accepted_message']="Failed to retrieve accepted questions".mysqli_error($cxn)."<br>";
		}
		$answered_counter=0;
		while ($row=mysqli_fetch_assoc($accepted_result))
		{
			$question_string=$subject_code.$grade.$topic_id.$question_number; //Generate string
			$question_string=strval($question_string);
			//echo "Question string is $question_string<br/>";
			$answered_strings[$answered_counter]=$question_string;
			$_SESSION['answered_strings']=$answered_strings;
			$answered_counter++;
		}
		$_SESSION['answered_counter']=$answered_counter;
		header ("Location:./mc_maketest.php");	
		
	}
	if ($value=='Delete')
	{
		echo "Proceeding to delete<br>";
		$summary_delete_query="DELETE FROM tblmctestsummary
								WHERE test_owner='".$test_owner."'
								AND subject_code=".$_SESSION['subject_code']."
								AND topic_id=".$topic_id."
								AND grade=".$grade."
								AND subject_counter=".$subject_counter
								;
		echo $summary_delete_query."<br>";
		if (!$summary_delete_result=mysqli_query($cxn,$summary_delete_query))
		{
			$_SESSION['summary_delete_message']="Failed to delete incomplete summary :".mysqli_error($cxn)."<br>";
		}
		else
		{
			$_SESSION['summary_delete_message']="Incomplete summary successfully deleted<br>";
		}
		
		$detail_delete_query="DELETE FROM tblmctestdetail
							WHERE test_owner='".$test_owner."'
							AND subject_code=".$_SESSION['subject_code']."
							AND topic_id=".$topic_id."
							AND grade=".$grade."
							AND subject_counter=".$subject_counter
							;
		echo $detail_delete_query."<br>";
		if (!$detail_delete_result=mysqli_query($cxn,$detail_delete_query))
		{
			$_SESSION['detail_delete_message']="Failed to delete incomplete test :".mysqli_error($cxn)."<br>";
		}
		else
		{
			$_SESSION['detail_delete_message']="Incomplete test successfully deleted<br>";
			header ("Location:../administrator_home.php");
		}
	}
}

?>