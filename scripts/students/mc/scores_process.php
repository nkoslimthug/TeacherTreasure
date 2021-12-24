<?php
//include($root_path."../../config/paths.php");
include ("../../../config/sungunura.php");
include ("../../functions/table_tmp_file_create.php");
include ("../../functions/table_tmp_file_update.php");
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
if (isset($_SESSION['test_key']))          //coming directly from a test
{
	$test_details=$_SESSION['test_key'];
	unset ($_SESSION['test_key']);
	//echo $test_details."<br>";
}
else										//coming via reports menu
{	
	foreach ($_POST as $key=>$value)      
	{
		//echo "$key=$value<br>";
		$test_details="$key";
			//echo $test_details."<br>";
	}
}
//echo $test_details."<br>";

//Unbundling test attributes
$pupil="";
$subject="";
$topic="";
$start_time="";
$plus_counter=0;
$char_counter=0;
$table_file="";
$char_count=strlen($test_details);

while ($char_counter<$char_count)
{
	$current_char=substr($test_details,$char_counter,1);
	switch ($plus_counter)
	{
		case 0:
			if ($current_char=="+")
			{
				$plus_counter++;
				$subject="";
				continue;
			}
			else
			{
				$pupil=$pupil.$current_char;
			}
	
		case 1:
			if ($current_char=="+")
			{
				$plus_counter++;
				$topic="";
				continue;
			}
			else
			{
				$subject=$subject.$current_char;
			}
		
		case 2:
			if ($current_char=="+")
			{
				$plus_counter++;
				$start_time="";
				continue;
			}
			else
			{
				if ($current_char=="_")
				{
					$current_char=" ";
				}
				$topic=$topic.$current_char;
			}
		
		case 3:
			if ($current_char=="+")
			{
				$plus_counter++;
				continue;
			}
			else
			{
				$start_time=$start_time.$current_char;
			}
	}
	$char_counter++;
} 
//echo "Pupil name is $pupil<br>";
//echo "Subject name is $subject<br>";
//echo "Topic name is $topic<br>";
//echo "Start date is $start_time<br>";

//Get Score
$score_query="SELECT score
			FROM tblperformance
			WHERE username='$pupil'
			AND	subject_name='$subject'
			AND topic_name='$topic'
			AND start_time='$start_time'"; 
//echo $score_query.'<br>';
$score_result=mysqli_query($cxn,$score_query);
if (!$row=mysqli_fetch_assoc($score_result))
{
	$_SESSION['no_score']="Failed to extract score ".mysqli_error($cxn)."<br/>";
}
else
{
	extract ($row);
}
//echo $_SESSION['question_score']=$score;

?>
<!doctype html>
<html>
<head>
	<title>
		Detailed Reports
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../../style/college.css" />
</head>
<body>
	<div id="banner" >
		
	</div>
	<div id="slogan_bar" >
		<center><i>eCompanion - It's Just What the Teacher Ordered</i></center>
	</div>
	<div id="message_bar" >
		
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			<ul class="nav">
					<li><a href='../student_home.php'>Home</a></li>
					<li><a href='../scores.php'>Back</a></li>
			</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner"> 
		<?php
			/*if (isset($_SESSION['no_score']))
			{
				echo $_SESSION['no_score'];
				unset ($_SESSION['no_score']);
			}
			
			if (isset($_SESSION['blank_subject_message']))
				{
					echo $_SESSION['blank_subject_message'];
					unset ($_SESSION['blank_subject_message']);
				}
				if (isset($_SESSION['blank_paper_message']))
				{
					echo $_SESSION['blank_paper_message'];
					unset ($_SESSION['blank_paper_message']);
				}
				if (isset($_SESSION['dataload_query']))
				{
					echo $_SESSION['dataload_query'];
					unset ($_SESSION['dataload_query']);
				}
				if (isset($_SESSION['dataload_message']))
				{
					echo $_SESSION['dataload_message'];
					unset ($_SESSION['dataload_message']);
				}
			*/
		?>
		
		</div>
		<form id="form1" name="form1" method="post" action="topic_select_frm.php">
								
								
												<?php
													
													if ($_SESSION['source_form']=='results_summary')
													{
														echo "<br><br>";
														echo "You have scored <b style='color:blue'>".$_SESSION['question_score']."</b><font color='#900090'> out of <b style='color:blue'>".$_SESSION['size']."</b><font color='#900090'><br/>";
														echo "<hr>";
														echo "<br><br>";
													}
													else if ($_SESSION['source_form']=='scores')
													{
														echo "<br><br>";
														if (isset($score))
															echo "Your score for this test is <b style='color:blue'>".$score."</b><font color='#900090'> percent <b style='color:blue'></b><font color='#900090'><br/>";
														echo "<hr>";
														echo "<br><br>";
													}
													echo "<h3>".strtoupper($subject." - ".$topic)."<br><br></h3>";
													echo "<hr>";
													$shade_colour="black";
													//Get topic
													
													/*$test_query="SELECT * FROM tblmctestlog 
																WHERE username='$pupil' 
																AND start_time='$start_time' 
																ORDER BY question_counter" ;*/
													$test_query="SELECT q.subject_code,q.topic_id,q.question_number,q.question,q.true_answer,q.instruction,t.question_counter,t.verdict,
																t.submitted_answer,q.story_id,q.image_id,q.question,t.choice0,t.choice1,t.choice2,t.choice3
																FROM tblmctestlog t,tblmcquestions q
																WHERE q.subject_code=t.subject_code
																AND q.topic_id=t.topic_id
																AND q.question_number=t.question_number
																AND t.username='$pupil' 
																AND t.start_time='$start_time' 
																ORDER BY t.question_counter";
													//echo $test_query."<br>";
													if (!$test_result=mysqli_query($cxn,$test_query))
													{
														$_SESSION['test_message']="Failed to retrive test :".mysqli_error($cxn)."<br>";
														exit;
													}
													else
													{
														$_SESSION['test_message']="Test retrieved<br>";
														$correct_choice="green";
														$wrong_choice="red";
														$answer_message="";
														$submitted_message="";
														$green_message="          <font='green'>(Correct Answer submitted)";
														$red_message="       <font='red'>(Incorrect Answer submitted)";
														$correct_message="<b>       (Correct answer but not chosen)</b>";
														$answer_colour;
														$story_counter=1;
														$image_counter=1;
														while ($row=mysqli_fetch_assoc($test_result))
														{	
															extract ($row);
															//Get story
															
															if ($story_id!=0)
															{
																if ($story_counter==1)
																{	
																	$story_query="SELECT story_title,story_content 
																				FROM tblstories 
																				WHERE story_id=$story_id AND subject_code=$subject_code";
																	if (!$story_result=mysqli_query($cxn,$story_query))
																	{ 
																		$_SESSION['story_message']="Failed to retrieve story :".mysqli_error($cxn)."<br>"; 
																		
																		exit;
																	}
																	else
																	{ 
																		$_SESSION['story_message']="Story retrieved<br>"; 
																	}
																	$row=mysqli_fetch_assoc($story_result);
																	extract ($row);
																	echo "<center><h3>$story_title</h3></center>";
																	echo "<p>$story_content<br/></p>";
																	$story_counter++;
																	//echo "Story counter is $story_counter<br>";
																}
															}
															
															//Get image
															if ($image_id!=0)
															{
																$image_counter=1;
																if ($image_counter==1)
																$image_query="SELECT image_location 
																			FROM tblimages 
																			WHERE image_id=$image_id";
																//echo "<br>".$image_query."<br>";
																if (!$image_result=mysqli_query($cxn,$image_query))
																{ 
																	$_SESSION['image_message']="Failed to retrieve image :".mysqli_error($cxn)."<br>"; 
																	
																	exit;
																}
																else
																{ 
																	$_SESSION['image_message']="image retrieved<br>"; 
																}
																$row=mysqli_fetch_assoc($image_result);
																extract ($row);
																echo "<center><img src='$image_location'></img></center>";
																//echo "Image location is $image_location<br>";
															}
															
															/*if ($table_based!=0)
															{
																create_table_file($table_file);
																update_table();
																header ("Location:mc_disp_questions_table.php");
																exit;
																
																echo "Table based question<br>";
																echo $question_complete."<br>";
															}*/
															echo "<strong><span style='color:#0000FF;'>Question ".$question_counter."<span style='color:#900090;'></strong><br>";
															//echo "+++++++++++++++++++++++++++++++++++++++++++++++";
															//echo "Question is ".$question."<br>";
															$question_complete=$question;
															echo "<br><b>".$instruction."</b><br>";
															echo "<br><font='$shade_colour'>".$question_complete."<br>";
															
															//choice zero
															if ($choice0==$true_answer)
															{
																if ($choice0==$submitted_answer)
																{
																	$shade_colour="green";
																	$answer_message=$green_message;
																}
																else
																{
																	$shade_colour="red";
																	$answer_message=$correct_message;
																}
																
															}
															else if ($choice0!=$true_answer)
															{
																if ($choice0==$submitted_answer)
																{
																	$shade_colour="red";
																	$submitted_message=" (Submitted this answer)";
																}
																else
																{
																	$submitted_message="";
																}
															}				
															
															echo "<style='font: $shade_colour;>";
															//echo $shade_colour."  ";
															echo "<br>A: ".$choice0;
															$shade_colour="black";
															//echo "<style='font: $shade_colour;>";
															//echo $shade_colour."  ";
															echo $submitted_message;
															$submitted_message="";
															echo $answer_message;
															$answer_message="";
															
															
															//choice one
															if ($choice1==$true_answer)
															{
																if ($choice1==$submitted_answer)
																{
																	$shade_colour="green";
																	$answer_message=$green_message;
																}
																else
																{
																	$shade_colour="red";
																	$answer_message=$correct_message;
																	
																}
															}
															else if ($choice1!=$true_answer)
															{
																if ($choice1==$submitted_answer)
																{
																	$shade_colour="red";
																	$submitted_message="(Submitted this answer)";
																}
																else
																{
																	$submitted_message="";
																}
															}																
															//echo "<style='font: $shade_colour;>";
															//echo $shade_colour."  ";
															echo "<br>B: ".$choice1;
															$shade_colour="black";
															//echo "<style='font: $shade_colour;>";
															//echo $shade_colour."  ";
															echo $answer_message;
															$answer_message="";
															echo $submitted_message;
															$submitted_message="";
															
															//choice 2		
															if ($choice2==$true_answer)
															{
																if ($choice2==$submitted_answer)
																{
																	$shade_colour="green";
																	$answer_message=$green_message;
																}
																else
																{
																	$shade_colour="red";
																	$answer_message=$correct_message;
																}
															}
															else if ($choice2!=$true_answer)
															{
																if ($choice2==$submitted_answer)
																{
																	$shade_colour="red";
																	$submitted_message="(Submitted this answer)";
																}
																else
																{
																	$submitted_message="";
																}
															}
															
																	//echo "<style='font: $shade_colour;>";
																	//echo $shade_colour."  ";
																	echo "<br>C:   ".$choice2;
																	$shade_colour="black";
																	//echo "<style='font: $shade_colour;>";
																	//echo $shade_colour."  ";
																	echo $answer_message;
																	$answer_message="";
																	echo $submitted_message;
																	$submitted_message="";
															
															//choice 3
															if ($choice3==$true_answer)
															{
																if ($choice3==$submitted_answer)
																{
																	$shade_colour="green";
																	$answer_message=$green_message;
																}
																else
																{
																	$shade_colour="red";
																	$answer_message=$correct_message;
																	
																}
															}
															else if ($choice3!=$true_answer)
															{
																if ($choice3==$submitted_answer)
																{
																	$shade_colour="red";
																	$submitted_message="(Submitted this answer)";
																}
																else
																{
																	$submitted_message="";
																}
															}
															
																	//echo "<style='font: $shade_colour;>";
																	//echo $shade_colour."  ";
																	echo "<br>D: ".$choice3;
																	$shade_colour="black";
																	//echo "<style='font: $shade_colour;>";
																	//echo $shade_colour."  ";
																	echo $answer_message;
																	$answer_message="";
																	echo $submitted_message;
																	$submitted_message="";
																	echo "<table style='align:right;width:100%;'>";
																	echo "<tr width='100%'>";
																	//echo "<td style='align:right;'><input type='submit' name='' value='Report Question' /></td>";
																	echo "</tr>";
																	echo "</table>";
																	echo "<hr>";
																	//echo "<hr/>";
																	//echo "True answer is ".$true_answer."<br>";
																	//echo "Submitted answer is ".$submitted_answer."<br>";
														}
													}
	//LOG QUESTION OUTCOMES												
	//read current test array
/*if ($_SESSION['source_form']=='results_summary')
{
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
}*/
?>
											             
								<p>&nbsp;</p>
								
							</form>
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>