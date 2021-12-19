<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
include ("../../functions/draw_table33.php");
include ("../../functions/table_tmp_file_create.php");
include ("../../functions/table_tmp_file_update.php");
include ("./mc_table_load.php");

	if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}

	function playsound(){
		echo"	
			<audio controls autoplay hidden>
				<source src='../../../sounds/groans.mp3' type='audio/mpeg'>
				Your browser does not support the audio element.
			</audio>
		";
	}
	function test_duration()
	{
		$end_time=time('now');
		$end_timestamp=date("Y-m-d H:i:s",$end_time);
		$_SESSION['end_timestamp']=$end_timestamp;
		$_SESSION['end_time']=$end_time;
		$_SESSION['duration']=$_SESSION['end_time']-$_SESSION['start_time'];
		$totalTime =(int)$_SESSION['duration'];
		$days =0;
		$hours =(int)( $totalTime / 3600);
		$minutes =(int)(( $totalTime % 3600) / 60);
		$seconds =(int)(( $totalTime % 3600) % 60);
		$duration='';
		if($hours>0){
			$duration.= $hours;
			if($hours>1){
				$duration.= ' hours ';
			}
			else{
				$duration.= ' hour ';
			}
		}
		if($minutes >0){
			$duration.= $minutes;
			if($minutes>1){
				$duration.= ' minutes ';
			}
			else{
				$duration.= ' minute ';
			}
		}
		if($seconds>0){
			$duration.= $seconds;
			if($seconds>1){
				$duration.= ' seconds';
			}
			else{
				$duration.= ' second';
			}
		}
		echo "<p><font color='blue'><b>".$duration."</b></p>";
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
		//echo $_SESSION['testkill_query_message'];
	}
	
	if (isset($_POST['btnAbort']))
	{
		playsound();
		if($_SESSION['guest']==true){
			header ("Location:../../../index.php");
		}
		else{
			header ("Location:../student_home.php");
		}
		exit;
	}	
	
	$subject_name=$_SESSION['subject_name'];
	$subject_code=$_SESSION['subject_code'];
	$grade=$_SESSION['grade'];
	$page_title=$_SESSION['paper_description'];
	
//Start timer - First question being loaded
if ($_SESSION['question_counter']==0) 
{
	purge_user($cxn); //clear previous record
	//record start time
	$_SESSION['origin']="RDM";
	$_SESSION['start_time']=date("Y-m-d H:i:s",time('now'));
	$_SESSION['question_type']="MC";
	$section_count=0;
	$section_counter=0;	
	//Log test start time
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
	//	echo $starttime_message."<br>";
	//echo "Start timestamp is ".$_SESSION['start_timestamp']."<br>";
	//echo "Start time is ".$_SESSION['start_time']."<br>";
	//$name_counter=0;
	if (isset($_SESSION['quota_tracker']))
	{
		//echo "Quota tracker at the top = ". $_SESSION['quota_tracker']."<br>";
	}
	//Manage paper question sections
	//Retrieve all sections for this subject
	
	$section_query="SELECT * FROM 
					tbltestsections 
					WHERE subject_code=$subject_code";
	//echo $section_query."<br>";
	$section_result=mysqli_query($cxn,$section_query);
	while($row=mysqli_fetch_assoc($section_result))
	{
		extract ($row);
		$section_counter++;
		/*echo "Upon loading...<br>";
		echo "+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++<br>";
		echo "Subject ".$subject_code." is ".$subject_name."<br>";
		echo "Section ".$section_id." is ".$section_name." and has a quota of ".$section_quota." questions<br>";
		echo "Grade is ".$grade." paper of subject ID ".$subject_code."<br>";*/
		$section_string=$subject_code.$grade.$section_id.$section_quota; //Generate string
		$section_string=strval($section_string); //convert to text string
		//echo "Section string is ".$section_string."<br/>";
		$record_length=strlen($section_string);
		//echo "String is $record_length characters long<br>";
		//building section names array
		$sections[$section_counter]=$section_string;
		$names[$section_counter]=$section_name;
		/*echo "Section name is <b>$section_name</b><br>";
		echo "End of loaded raw values<br>";
		echo "---------------------------------------------------------------------------------<br>";*/
		
		$section_count++;
		$_SESSION['sections']=$sections;
		$_SESSION['names']=$names;
		$section_string="";
		/**/
	}
	$_SESSION['section_counter']=$section_counter;
	$_SESSION['section_count']=$section_count;
	//reading sections array
	//echo "there are $section_counter sections for this paper<br>";
	$_SESSION['$section_count']=$section_count;
	$_SESSION['section_counter']=1;
	$_SESSION['quota_tracker']=0;
}

//Advance question
//echo "Paper size is ".$_SESSION['paper_size']."<br>";
//echo "Initial question counter is ".$_SESSION['question_counter']."<br>";
$_SESSION['question_counter']++;
$_SESSION['quota_tracker']++;

//Track section questions
//echo "Current section is ".$_SESSION['section_counter']."<br>";
//echo "There are ".$_SESSION['section_count']." sections<br>";
//$_SESSION['section_tracker']=0;
   //initialise quota_tracker
   
//echo "Quota tracker has just been reset to ".$_SESSION['quota_tracker']."<br>";

//echo  "Section  counter is ".$_SESSION['section_counter']." while section count is at ".$_SESSION['section_count']."<br>";
while ($_SESSION['section_counter']<=$_SESSION['section_count'])
{
	/*echo "Section counter is ".$_SESSION['section_counter']."<br>";
	echo "Section count is ".$_SESSION['section_count']."<br>";*/

	$sections=$_SESSION['sections'];
	$names=$_SESSION['names'];
	$section_id=$_SESSION['section_counter'];
//	echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++<br>";
	$section_string=$sections[$section_id];
	$record_length=strlen($section_string);
	$section_name=$names[$section_id];
	//echo "Record length is ".$record_length."<br>";
		
	if ($record_length==3)
	{
		$_SESSION['section_quota']=(int)substr($sections[$section_id],2,1); 
	}
	if ($record_length==4)
	{
		$_SESSION['section_quota']=(int)substr($sections[$section_id],3,2); 
	}
	/*echo "Current string is $section_string<br>";
	echo "Section quota = ".$_SESSION['section_quota']."<br>";
	echo "Quota tracker = ".$_SESSION['quota_tracker']."<br>";*/
	$quota_tracker=$_SESSION['quota_tracker'];
	if ($_SESSION['quota_tracker']>$_SESSION['section_quota']) 
	{ 
		$_SESSION['section_counter']++; 
		$_SESSION['quota_tracker']=1;
		//$_SESSION['section_quota']=$section_quota;
		//echo "Switching sections<br>";
	}
	$section_id=$_SESSION['section_counter'];
	$names=$_SESSION['names'];
	/*echo "Current section string is ".$sections[$section_id]."<br>";
	echo "----------------------------------------------------------------------<br>";
	echo "About to check if section has already been exhausted<br>";*/
	while ($_SESSION['quota_tracker']<=$_SESSION['section_quota']) //If section is still to be exhausted
	{
		if ($_SESSION['quota_tracker']==1)
		{
			//echo "Section name is ".$names[$section_id]."<br>";
			//Retrieve story
			if ($names[$section_id]=="NZWISISO"||$names[$section_id]=="COMPREHENSION")
			{
				
				$story_selector="SELECT story_id,story_title,story_content FROM tblstories 
													WHERE subject_code=$subject_code
													ORDER BY RAND() LIMIT 1";                                        
				//echo $story_selector."<br>";
				$story_result=mysqli_query($cxn,$story_selector);
				$row=mysqli_fetch_assoc($story_result);
				extract ($row);
				$_SESSION['story_id']=$story_id;
				$_SESSION['story_title']=$story_title;
				$_SESSION['story_content']=$story_content;
				$_SESSION['section_id']=$section_id;
				if ($names[$section_id]=='NZWISISO')
				{
					$_SESSION['instruction']="Verenga ndima ugopingura mibvunzo<br>";
				}
				if ($names[$section_id]=='COMPREHENSION')
				{
					$_SESSION['instruction']="Read the passage and answer the questions that follow<br>";
				}
			}
		}
		$_SESSION['section_name']=$names[$section_id];
		/*echo "Quota tracker = ".$_SESSION['quota_tracker']."<br>";
		echo "Section quota = ".$_SESSION['section_quota']."<br>";
		echo "Section $section_id is ".$names[$section_id]." and has a quota of ".$_SESSION['section_quota']." and is currently at value ".$_SESSION['quota_tracker']." <br>";
		echo "Current quota value = ".$_SESSION['quota_tracker']."<br>"; 
		echo "About to load next question<br>";*/
		//Load random question
		while($_SESSION['question_counter']<=$_SESSION['paper_size']) //change condition
		{
			$question_select_query="SELECT * FROM tblmcquestions 
									WHERE subject_code=$subject_code 
									AND lower_grade<=".$_SESSION['student_grade']. 
									" AND upper_grade>=".$_SESSION['student_grade'].
									" AND topic_id IN (SELECT topic_id 
													FROM tbltopics
													WHERE section_id=$section_id AND subject_code=$subject_code)
									AND story_id={$_SESSION['story_id']}
									ORDER BY rand() LIMIT 1";
			//echo "$question_select_query<br/>";
			if (!$question_select_result=mysqli_query($cxn,$question_select_query))
			{
				$message= "Error : \n".mysqli_error($cxn);
				//echo "<script type = 'text/javascript'>alert('".$message."')</script>";
			}
			$row=mysqli_fetch_assoc($question_select_result);
			extract ($row);
			//echo "Extracted question number ".$question_number."<br>";
			//echo "The correct answer is ".$true_answer."<br>";
			$_SESSION['true_answer']=$true_answer;
			//echo "Image ID == $image_id <br>";
			$_SESSION['story_id']=$story_id;
			$_SESSION['image_id']=$image_id;
			$_SESSION['question_number']=$question_number;
			$question_complete=$question;
			$_SESSION['question_complete']=ucfirst($question_complete); //initialise capital
			$_SESSION['answer_T']=$true_answer;
			$_SESSION['topic_id']=$topic_id;
			$_SESSION['instruction']=$instruction;
			$_SESSION['table_based']=$table_based;
			
			
			//echo "True answer as extracted from the database is $true_answer<br/>";
							
			//populate questions already answered
			$question_string=$subject_code.$grade.$topic_id.$question_number; //Generate string
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
			$_SESSION['question_number']=$question_number;
			if(isset($question_number_section) && $question_number_section!=''&&$question_number_section!='0')
			{
				$_SESSION['question_code'].='('.$question_number_section.')';						
			}
		}
		//$_SESSION['quota_tracker']++;
		//echo "On completion of processing quota traching is at value ".$quota_tracker."<br>";
		break;
		
	}
	break; //process next section if it  exists
}

//Process Image
								if ($_SESSION['image_id']!=0)
								{
									$image_query="SELECT * FROM tblimages WHERE image_id={$_SESSION['image_id']}";
									$image_result=mysqli_query($cxn,$image_query);
									$row=mysqli_fetch_assoc($image_result);
									extract($row);
							/*		echo "Image ID is ".$_SESSION['image_id']."<br>";
									echo "Image title is $image_title <br>";
									echo "Image URL is $image_location <br>";*/
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
		
//Batch complete	
if ($_SESSION['question_counter']>$_SESSION['paper_size']){ //batch complete 
	//echo "Paper complete<br>";
	mc_table_load();
	$_SESSION['end_time']=date("Y-m-d H:i:s",time('now'));
	$question_counter=$_SESSION['question_counter'];
	$_SESSION['duration']=$_SESSION['end_time']-$_SESSION['start_time'];
	//initialise batch counter
	$_SESSION['paper_size']=$_SESSION['question_counter']-1;
	$_SESSION['question_counter']=0;
	$_SESSION['message_batch_complete']= "Test completed<br/>"; 	//Signal batch completion
	$_SESSION['size']=$_SESSION['paper_size'];
	
	//flag completed test
	$completed_query="UPDATE tblmctestlog 
						SET complete=1
						WHERE subject_code=".$_SESSION['subject_code']." 
						AND username='".$_SESSION['username']."' 
						AND start_time='".$_SESSION['start_time']."'";
	echo $completed_query."<br>";
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
		echo $end_time_query;
		if (!$end_time_result=mysqli_query($cxn,$end_time_query))
		{
			$_SESSION['end_time_message']="Failed to update end time: ".mysqli_error($cxn)."<br>";
		}
		else
		{
			$_SESSION['end_time_message']="Test ended at ".$_SESSION['end_time']."<br>";
		}
		echo $_SESSION['end_time_message'];
	//echo "Test started at ".$_SESSION['start_time']." and ended at ".$_SESSION['end_time']."<br>";
	header ("Location:../results_summary.php"); //Display results
	exit;
}
function test_time_message(){
	echo "<p> ";
	if ($_SESSION['question_counter']==1) {
		echo "<b>Test started at <font color='blue'>"; echo $_SESSION['start_time']; echo "</b><font color='#900090'>";
	}	
		echo "</p> ";
}
?>
<!doctype html>
<html>
<head>
	<title>
		Cyber Home
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
		<form id="form1" name="form1" method="post" action="mc_dyn_answers.php">
					<?php     
					//Retrieve question
					//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
					// Test time progress
					foreach($_SESSION['answered_strings'] AS $key=>$value)
					{
						//echo "$key = $value<br>";
					}
						test_time_message();
						echo "<p><h3>".$_SESSION['paper_description']."</h3></p>";
						//test_duration();
						echo "<p><h4>Section $section_id - ".$_SESSION['section_name']."</h4></p>";
						$_SESSION['section_id_answers']=$section_id;
						echo "<b><br>$instruction</b><p>";
						// Display story
						if (isset($_SESSION['story_id']))
						{
							if ($_SESSION['story_id']!=0)
							{
								echo "<center><b>".$_SESSION['story_title']."</b></center><br>";
								echo "<br>".$_SESSION['story_content']."<br>";
							}
						}
						//Display Image
							if ($_SESSION['image_id']!=0)
							{
								//echo "<center>".$image_title."</center><br><br>";
								
								echo "<center><img src='$image_location'></img></center>";
							}
							//Display current question
							if ($table_based!=0) //table based question
							{
								//store session variables
								//$instruction=$_SESSION['instruction'];
								$story_id=$_SESSION['story_id'];
								$_SESSION['table_based']=$table_based;
								$_SESSION['choices0']=$choices[0];
								$_SESSION['choices1']=$choices[1];
								$_SESSION['choices2']=$choices[2];
								$_SESSION['choices3']=$choices[3];
								//dislay session values
								echo "Instruction is ".$_SESSION['instruction']."<br>";
								echo "First choice is ".$_SESSION['choices0']."<br>";
								echo "Second choice is ".$_SESSION['choices1']."<br>";
								echo "Third choice is ".$_SESSION['choices2']."<br>";
								echo "Fourth choice is ".$_SESSION['choices3']."<br>";
								
								echo "Diverting <br>";
								//write temp file
								create_table_file();
								update_table();
								header ("Location:mc_dyn_questions_table.php");
								exit;
							 }
						echo "
							<h3 style='color: blue; font-weight: bolder'>
								Question ". $_SESSION['question_counter']."
							</h3>
						";
						$_SESSION['header_path']="../../";
						$_SESSION['images_path']="../../../";						
						include ($_SESSION['header_path']."general/question_header.php");
						echo "<p><b style='color:blue'>".$_SESSION['question_complete']."</b><br/></p>";
					
					?>
					<hr/>
					<p>
						<b style='color: red;'><u>Answers</u></b>
						<b style='color: #900090;'></b>
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
							<td><input style="background-color: red; font-weight: bolder;" type="submit" name="btnAbort" id="btnAbort" value="Abort Test" /></td>
						</tr>
					</table>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
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