<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
include ("../../functions/draw_table33.php");
include ("../../functions/table_tmp_file_create.php");
include ("../../functions/table_tmp_file_update.php");
include ("./mc_table_load.php");
include ("./mc_logattempted.php");

	if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}

	function playsound()
	{
		echo "	
			<audio controls autoplay hidden>
				<source src='../../../sounds/groans.mp3' type='audio/mpeg'>
				Your browser does not support the audio element.
			</audio>
		";
	}
	
	function test_duration()
	{
		$end_time=time('now');
		$endtime_stamp=date("Y-m-d H:i:s",$endtime);
		$_SESSION['endtime_stamp']=$endtime_stamp;
		$_SESSION['endtime']=$endtime;
		$_SESSION['duration']=$_SESSION['endtime']-$_SESSION['start_time'];
		$totalTime =(int)$_SESSION['duration'];
		$days =0;
		$hours =(int)( $totalTime / 3600);
		$minutes =(int)(( $totalTime % 3600) / 60);
		$seconds =(int)(( $totalTime % 3600) % 60);
		$duration='';
		if($hours>0)
		{
			$duration.= $hours;
			if($hours>1)
			{
				$duration.= ' hours ';
			}
			else{
				$duration.= ' hour ';
			}
		}
		if($minutes >0)
		{
			$duration.= $minutes;
			if($minutes>1)
			{
				$duration.= ' minutes ';
			}
			else
			{
				$duration.= ' minute ';
			}
		}
		if($seconds>0)
		{
			$duration.= $seconds;
			if($seconds>1)
			{
				$duration.= ' seconds';
			}
			else
			{
				$duration.= ' second';
			}
		}
		echo "<p><font color='blue'><b>".$duration."</b></p>";
		return $duration;
	}
	
	function check_test($cxn)
	{
		include ("../../functions/sunungura.php");
		$testcheck_query="SELECT COUNT(*) AS test_counter 
							FROM tblteststart 
							WHERE username='".$_SESSION['username']."'";
		if (!$testcheck_result=mysqli_query($cxn,$testcheck_query))
		{
			$_SESSION['testcheck_message']="Test check failed: ".mysqli_error($cxn)."<br>";
		}
		else
		{
			$_SESSION['testcheck_message']="Test check succeeded<br>";
			$row=mysqli_fetch_assoc($testcheck_result);
			extract ($row);
			return $test_counter;
		}
	}
	
	function purge_user($cxn)		//remove previous test record
	{
		include ("../../functions/sunungura.php");
		$test_counter=check_test($cxn);
		if ($test_counter>0)
		{
			$testkill_query="DELETE FROM tblteststart 
							WHERE username='".$_SESSION['username']."'";
			if (!$testkill_result=mysqli_query($cxn,$testkill_query))
			{
				$_SESSION['testkill_query_message']="Failed to cleanup ".mysqli_error($cxn)."<br>";
			}
			else
			{
				$_SESSION['testkill_query_message']="Cleanup succeeded<br>";
			}
		}
	}
	
	
	
	if (isset($_POST['btnAbort']))
	{
		playsound();
		if($_SESSION['guest']==true)
		{
			header ("Location:../../../index.php");
		}
		else
		{
			header ("Location:../student_home.php");
		}
		exit;
	}	
	
	foreach ($_POST as $key=>$value)
	{
		//echo "$key = $value<br>";
		$test_details=$key;
	}
	
	//$subjects=$_SESSION['subjects'];
	if ($_SESSION['question_counter']==0)
	{
		//extract test details
		$test_owner="";
		$subject_code=0;
		$test_counter=0;
		$posted_date='';
		$plus_counter=0;
		$char_counter=0;
		//$table_file="";
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
						$subject_code="";
						continue;
					}
					else
					{
						$test_owner=$test_owner.$current_char;
					}
			
				case 1:
					if ($current_char=="+")
					{
						$plus_counter++;
						$test_counter="";
						continue;
					}
					else
					{
						$subject_code=$subject_code.$current_char;
					}
				
				case 2:
					if ($current_char=="+")
					{
						$plus_counter++;
						$posted_date="";
						continue;
					}
					else
					{
						if ($current_char=="_")
						{
							$current_char=" ";
						}
						$test_counter=$test_counter.$current_char;
					}
				
				case 3:
					if ($current_char=="+")
					{
						$plus_counter++;
						continue;
					}
					else
					{
						if ($current_char=='_')
						{
							$current_char=" ";
						}
						$posted_date=$posted_date.$current_char;
					}
			}
			$char_counter++;
		} 
	
		$_SESSION['test_owner']=$test_owner;
		$_SESSION['subject_code']=$subject_code;
		$_SESSION['test_counter']=$test_counter;
		$_SESSION['posted_date']=$posted_date;
		
	//	echo "Test owner is ".$_SESSION['test_owner']."<br>";
	//	echo "Subject code is ".$_SESSION['subject_code']."<br>";
	//	echo "Test counter is ".$_SESSION['test_counter']."<br>";
	//	echo "Test was posted on ".$_SESSION['posted_date']."<br>";
		
		$batch_size_query="SELECT test_size 
						FROM tblmctestsummary
						WHERE test_owner='".$_SESSION['test_owner']." '
						AND subject_code=".$_SESSION['subject_code']."
						AND subject_counter=".$_SESSION['test_counter'];
		//echo $batch_size_query."<br>";
		if (!$batch_size_result=mysqli_query($cxn,$batch_size_query))
		{
			$_SESSION['batch_size_message']="<br>";
		}
		else
		{
			$row=mysqli_fetch_assoc($batch_size_result);
			extract ($row);
			$_SESSION['batch_size']=$test_size;
		}
	//	echo "Batch size is ".$_SESSION['batch_size']."<br>";
	
	//determine subject
	$subjects=[];
	$subject_counter=1;
	$subject_query="SELECT * 
					FROM tblsubjects";
	$subject_result=mysqli_query($cxn,$subject_query);
	while ($row=mysqli_fetch_array($subject_result))
	{
		$subjects[$subject_counter][0]=$subject_counter;
		$subjects[$subject_counter][1]=$row[1];
		//echo $subjects[$subject_counter][0]." = ".$subjects[$subject_counter][1]."<br>";
		$subject_key=$subjects[$subject_counter][0];
		$subject_value=$subjects[$subject_counter][1];
		if ($subject_key==$subject_code)
		{
			$_SESSION['subject_name']=$subject_value;
		}
		$subject_counter++;
	}

	
	
	$_SESSION['test_description']=$_SESSION['test_owner']."_SCHEDULED_".$_SESSION['subject_name']."_".$_SESSION['test_counter'];
	//  batch size 
	
	}
	
	//Start timer
	if ($_SESSION['question_counter']==0)   //record start time
	{
		purge_user($cxn);
		$_SESSION['start_time']=date("Y-m-d H:i:s",time('now'));
		$_SESSION['question_type']="MC";
		
		//Log test start time
		$start_time_query="INSERT INTO tblteststart 
							(username,start_time)
							VALUES 
							('".$_SESSION['username']."','".$_SESSION['start_time']."')";
		//echo $start_time_query."<br>";
		if (!$start_time_result=mysqli_query($cxn,$start_time_query))
		{ 
			$start_time_message="Failed to update start time:".mysqli_error($cxn)."<br>"; 
		}
		else
		{ 
			$start_time_message="Start time logged"; 
		}
		//echo $starttime_message."<br>";
		
		
		
	}
	//Advance question
	
	$_SESSION['question_counter']++;
	//echo "The question counter is now ".$_SESSION['question_counter']."<br>";
	$question_counter=$_SESSION['question_counter'];
	if ($_SESSION['question_counter']>$_SESSION['batch_size'])		//batch complete 
	{ 
		mc_table_load();			//load question details for reporting
		log_attempted();			//track user attempts on test
		$_SESSION['duration']=test_duration();
		//initialise batch counter
		$_SESSION['batch_size']=$_SESSION['question_counter']-1;
		$_SESSION['size']=$_SESSION['batch_size'];
		//$_SESSION['question_counter']=0;
		$_SESSION['message_batch_complete']= "Test completed<br/>"; 	//Signal batch completion
		
		//flag completed test
								$completed_query="UPDATE tblmctestlog 
													SET complete=1
													WHERE subject_code=".$_SESSION['subject_code']." 
													AND topic_id=".$_SESSION['topic_id']." 
													AND username='".$_SESSION['username']."' 
													AND start_time='".$_SESSION['start_time']."'";
							//	echo $completed_query."<br>";
								if (!$completed_result=mysqli_query ($cxn,$completed_query))
								{
									$_SESSION['completed_message']="Failed to update questions: ".mysqli_error($cxn)."<br>";
								}
								else
								{
									$_SESSION['completed_message']="Questions confirmed<br>";
								}
		//Log test completion time
		$end_time_query="UPDATE tblteststart
						SET end_time='".$_SESSION['end_time']." 
						'WHERE username='".$_SESSION['username']."'";
		//echo $end_time_query;
		if (!$end_time_result=mysqli_query($cxn,$end_time_query))
		{
			$_SESSION['end_time_message']="Failed to update end time: ".mysqli_error($cxn)."<br>";
		}
		else
		{
			$_SESSION['end_time_message']="Test ended at ".$_SESSION['end_time']."<br>";
		}
		echo $_SESSION['end_time_message'];
		header ("Location:../results_summary.php"); //Display results
		exit;
	}
	function test_time_message()
	{
		echo "<p> ";
		if ($_SESSION['question_counter']==1) 
		{
			echo "<b>Test started at <font color='blue'>".$_SESSION['start_time']."</b><font color='#900090'>";
		}	
		echo "</p> ";
	}
	
	//Load random question
	
					//$answered_strings='';
					while($_SESSION['question_counter']<=$_SESSION['batch_size']) //change condition
					{
						$question_select_query="SELECT q.question_number,q.question,q.true_answer,q.option1,q.option2,q.option3,q.instruction,q.story_id,q.image_id,d.topic_id
												FROM tblmcquestions q,tblmctestdetail d
												WHERE q.subject_code=d.subject_code
												AND q.topic_id=d.topic_id
												AND q.question_number=d.question_number
												AND d.test_owner='".$_SESSION['test_owner']."' 
												AND d.subject_code=".$_SESSION['subject_code']." 
												AND d.subject_counter=".$_SESSION['test_counter']."
												ORDER BY rand() LIMIT 1";
						//echo $question_select_query."<br/>";
						
						if (!$question_select_result=mysqli_query($cxn,$question_select_query))
						{
							$message= "Error : \n".mysqli_error($cxn);
							//echo "<script type = 'text/javascript'>alert('".$message."')</script>";
						}
						$row=mysqli_fetch_assoc($question_select_result);
						extract ($row);
						$_SESSION['topic_id']=$topic_id;
						$_SESSION['true_answer']=$true_answer;
						//	echo "Image ID == $image_id <br>";
						$_SESSION['story_id']=$story_id;
						$_SESSION['image_id']=$image_id;
						$question_complete=$question;
						$_SESSION['instruction']=$instruction;
						$_SESSION['question_complete']=ucfirst($question_complete);
						$_SESSION['answer_T']=$true_answer;
						$_SESSION['option1']=$option1;
						$_SESSION['option2']=$option2;
						$_SESSION['option3']=$option3;
						$_SESSION['question_number']=$question_number;
						
						
						//echo "True answer as extracted from the database is $true_answer<br/>";
				
				//populate questions already answered
							
							$question_string=$_SESSION['test_owner'].$_SESSION['subject_code'].$_SESSION['test_counter'].$question_number; //Generate string
							$question_string=strval($question_string);
							//echo "Question string is $question_string<br/>";
							
							if ($_SESSION['question_counter']==1)
							{	//first question
								//echo "This is the first question<br>"; 
								$answered_counter=$_SESSION['question_counter'];
								$_SESSION['answered_counter']=$answered_counter;
								$answered_strings[$answered_counter]=$question_string;
								$_SESSION['answered_strings']=$answered_strings;
								$_SESSION['answered_counter']++;
								break;
							}
							else 
							{
								if (in_array($question_string,$_SESSION['answered_strings']))
								{
									//echo "$question_string already exists<br/>";
									continue;
								}
								else 
								{
									//echo "Processing string $question_string<br/>";
									$answered_counter=$_SESSION['answered_counter'];
									$answered_strings=$_SESSION['answered_strings'];
									$answered_strings[$answered_counter]=$question_string;
									$_SESSION['answered_strings']=$answered_strings;
									$_SESSION['answered_counter']++;
									//echo "This is the question ".$_SESSION['question_counter']."<br>"; 
									break;
								}
							}
							
							//$_SESSION['question_code']=$question_number;
							
							if(isset($question_number_section) && $question_number_section!=''&&$question_number_section!='0')
							{
								$_SESSION['question_code'].='('.$question_number_section.')';						
							}
					}
					//Process Image
								if ($_SESSION['image_id']!=0)
								{
									$image_query="SELECT * 
												  FROM tblimages 
												  WHERE image_id=".$_SESSION['image_id'];
									$image_result=mysqli_query($cxn,$image_query);
									$row=mysqli_fetch_assoc($image_result);
									extract($row);
								//	echo "Image ID is ".$_SESSION['image_id']."<br>";
								//	echo "Image title is $image_title <br>";
								//	echo "Image URL is $image_location <br>";
								}
								//echo "Image ID = $image_id <br>";
					
					//Randomise answers</p>
						//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
						$choice_ceiling=4;
						$choice_counter=0;
						$selector_counter=0;
						$selectors[$selector_counter]="";
						while ($choice_counter<$choice_ceiling)
						{
							$query="SELECT MOD(ROUND(100*TRUNCATE(RAND(),2),0),4)";
							$result2=mysqli_query($cxn,$query);
							$row=mysqli_fetch_row($result2);
							$selector=$row[0];
							$selector_counter++;
							if (in_array($selector,$selectors))
							{
								$choice_counter--;
							}
							else if (!in_array($selector,$selectors))	//this random number not already been generated
							{
								switch ($selector)
								{
									case 0:
										$choices[$choice_counter]=$true_answer;
										break;
									case 1:
										$choices[$choice_counter]=$option1;
										break;
									case 2:
										$choices[$choice_counter]=$option2;
										break;
									case 3:
										$choices[$choice_counter]=$option3;
										break;
								}
								$selectors[$selector_counter]=$selector;
								$selector_counter++;
							}
							$choice_counter++;
						}

?>
<!doctype html>
<html>
<head>
	<title>
		Multiple Choice Questions
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
		
		<?php
				//include($root_path."config/page_header.php");
			?>
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner">  </div>
		<form id="form1" name="form1" method="post" action="mc_scheduled_answers.php">
					<?php     
					//Retrieve question
					//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

						// Test time progress
						test_time_message();
						echo "<br><p><h3>".$_SESSION['test_description']."</h3></p>";
						//test_duration();
						echo "<b><p>".$instruction."</b></p>";
						
						// Retrieve story
						if (isset ($_SESSION['story_id']))
						{
							if ($story_id!=0) 
							{
								$story_query="SELECT story_title,story_content 
												FROM tblstories 
												WHERE subject_code=$subject_code AND story_id=".$_SESSION['story_id'];
								//echo "$story_query<br/>";
								$story_result=mysqli_query($cxn,$story_query);
								$row=mysqli_fetch_assoc($story_result);
								extract ($row);
								 
								echo "<center><h3>$story_title</h3></center>";
								echo "<p>$story_content<br/></p>";
							}
						}
						
						//Display Image
						 
							if ($image_id!=0)
							{
								echo "<center>".$image_title."</center><br><br>";
								echo "<center><img src='$image_location'></img></center>";
							}
							//display session values
							/*	echo "Instruction is ".$_SESSION['instruction']."<br>";
								echo "First choice is ".$_SESSION['choices0']."<br>";
								echo "Second choice is ".$_SESSION['choices1']."<br>";
								echo "Third choice is ".$_SESSION['choices2']."<br>";
								echo "Fourth choice is ".$_SESSION['choices3']."<br>";*/
							//Display current question
												
							echo "
							<h3 style='color: blue; font-weight: bolder'>
								Question ". $_SESSION['question_counter']."
							</h3>
							";
							//echo $question;
						$_SESSION['header_path']="../../";
						$_SESSION['images_path']="../../../";						
						include ($_SESSION['header_path']."general/question_header.php");
						echo "<p><b style='color:blue'>".$_SESSION['question_complete']."</b><br/></p>";
						//echo $question."<br>";
						$question=$_SESSION['question_complete'];
					
					?>
					
					<hr/>
					<p>
						<b style='color: red;'><u>Answers</u></b>
					</p>
					<p>
						<b>A</b>
						<label for="radio1">
							<input type="radio" name="answer" id="radio1" value="<?php echo $choices[0]; $_SESSION['choices0']=$choices[0]; ?>"/><?php echo $choices[0] ?></label>
					</p>
					<p>
						<b>B</b>
						<label for="radio2">
							<input type="radio" name="answer" id="radio2" value="<?php echo $choices[1]; $_SESSION['choices1']=$choices[1]; ?>"/><?php echo $choices[1] ?></label>
					</p>
					<p>
						<b>C</b>
						<label for="radio3">
							<input type="radio" name="answer" id="radio3" value="<?php echo $choices[2]; $_SESSION['choices2']=$choices[2]; ?>"/><?php echo $choices[2] ?></label>
					</p>
					<p>
						<b>D</b>
						<label for="radio4">
							<input type="radio" name="answer" id="radio4" value="<?php echo $choices[3]; $_SESSION['choices3']=$choices[3]; ?>"/><?php echo $choices[3] ?></label>
					</p>
					<hr/>
					<table >
						<tr>
							<td><input style="background-color: green; font-weight: bolder;" type="submit" name="btnAnswer" id="btnAnswer" value="Submit Answer" /></td>
						</tr>
					</table>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
				</form>
	</div>
	
	<div id="footer" >
		<font="brown"></font>
	</div>
</body>
</html>