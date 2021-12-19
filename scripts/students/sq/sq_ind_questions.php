<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");

	if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}

	$subject_name=$_SESSION['subject_name'];
	$subject_code=$_SESSION['subject_code'];
	$grade=$_SESSION['grade'];
	$topic_id=$_SESSION['topic_id'];
	//echo "Topic ID is $topic_id<br/>";
	$_SESSION['paper_description']=$subject_name." GRADE ".$grade." - ".$_SESSION['topic_name']." TEST<br/>";
	//echo "Testing<br/>";
	function playsound(){
		echo"	
			<audio controls autoplay hidden>
				<source src='../../../sounds/groans.mp3' type='audio/mpeg'>
				Your browser does not support the audio element.
			</audio>
		";
	}
	if (isset($_POST['btnAbort'])){
		playsound();
		if($_SESSION['guest']==true){
			header ("Location:../../../index.php");
		}
		else{
			header ("Location:../student_home.php");
		}
		exit;
	}
	if (isset($_POST['btnNext'])){
		$_SESSION['question_counter']++;
		//echo "New question counter is ".$_SESSION['question_counter']."<br/>";
	}
	
	$_SESSION['paper_type']=2;
	$_SESSION['batch_size']=5;
	if ($_SESSION['question_counter']==0){ //record start time
			$current_day=date('l');
			$start_time=time('now');
			$start_timestamp=date("Y-m-d H:i:s",$start_time);
			$message_today="Today is $current_day<br/><br/>";
			$_SESSION['start_timestamp']=$start_timestamp;
			$_SESSION['start_time']=$start_time;
			$_SESSION['question_type']="SQ";
			$_SESSION['question_counter']++; 	//advance question
	}
	//echo "Question counter is ".$_SESSION['question_counter']."<br/>"; 
	//echo "Batch size is ".$_SESSION['batch_size']."<br/>";
	if ($_SESSION['question_counter']>$_SESSION['batch_size']){
		$end_time=time('now');
		$end_timestamp=date("Y-m-d H:i:s",$end_time);
		$_SESSION['end_timestamp']=$end_timestamp;
		$_SESSION['end_time']=$end_time;
		$_SESSION['duration']=$_SESSION['end_time']-$_SESSION['start_time'];
		$_SESSION['question_counter']=0;
		$_SESSION['message_batch_complete']= "Test completed<br/>"; 	//Signal batch completion
		$total_paper_marks_query="SELECT DISTINCT question_count, answer_field, marks 
								FROM tblstructuredanswers WHERE 
							subject_code=$subject_code 
							AND topic_id=$topic_id
							";
		//echo "$total_paper_marks_query<br/>";				
		$total_paper_marks = 0;
		/*if (!$result=mysqli_query($cxn,$total_paper_marks_query)){
			$message="Failed to retrieve question :\n".mysqli_error($cxn);
			echo "<script type = 'text/javascript'>alert('".$message."')</script>";
			exit;
		}*/
		echo "Calculating the score<br>";
		while($row=mysqli_fetch_assoc($result)){
			extract ($row);
			$total_paper_marks += $marks;
			echo "Marks now at ".$total_paper_marks."<br>" ;
			
		}
		if ($_SESSION['topic_id']==0){
			$_SESSION['total_paper_marks']= $total_paper_marks;
		}
		else {
			$_SESSION['total_paper_marks']=$_SESSION['batch_size'];
		}
		$_SESSION['size']=$_SESSION['batch_size'];
		//echo "Total paper marks = ".$_SESSION['total_paper_marks']."<br/>";
		header ("Location:../results_summary.php"); 
		exit;
	}

	function test_time_message(){
		echo "<p> ";
		if ($_SESSION['question_counter']==1) 
		{
			echo "<b>Test started at <font color='blue'>"; echo $_SESSION['start_timestamp']; echo "</b><font color='#900090'></p>";
		}

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
		Top Section
	</div>
	<div id="slogan_bar" >
		<marquee>The Technological High Table</marquee>
	</div>
	<div id="message_bar" >
		
		<?php
				//include($root_path."config/page_header.php");
			?>
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		
	</div>
	<div id="content" >
		<div id="message_banner">eCompanion - It's Just What the Teacher Ordered </div>
		<form  autocomplete="off" id="form1" name="form1" method="post" action="sq_ind_answers.php" autocomplete="off" spellcheck="false" >
					<?php     
							//Retrieve question
							//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
							//echo $_SESSION['batch_size']."<br/>";
							$answered_strings='';
						while($_SESSION['question_counter']<=$_SESSION['batch_size'])
						{
							$question_select_query="SELECT * FROM tblstructuredquestions WHERE 
													subject_code=$subject_code 
													AND lower_grade<=$grade
													AND upper_grade>=$grade
													AND topic_id=$topic_id
													ORDER BY rand() LIMIT 1";
							echo $question_select_query."<br/>";
							if (!$result=mysqli_query($cxn,$question_select_query)){
								$message="Failed to retrieve question : \n".mysqli_error($cxn);
								echo "<script type = 'text/javascript'>alert('".$message."')</script>";
							}
							$row=mysqli_fetch_assoc($result);
							extract ($row);
							
							//populate questions already answered
							
							$question_string=$subject_code.$topic_id.$question_number; //Generate string
							$question_string=strval($question_string);
							echo "Question string is $question_string<br/>";
							
							if ($_SESSION['question_counter']==1){	//first question
								$answered_counter=$_SESSION['question_counter'];
								$_SESSION['answered_counter']=$answered_counter;
								$answered_strings[$answered_counter]=$question_string;
								$_SESSION['answered_strings']=$answered_strings;
								$_SESSION['answered_counter']++;
							}
							else {
								if (in_array($question_string,$_SESSION['answered_strings'])){
									echo "$question_string already exists<br/>";
									continue;
								}
								else {
									echo "Processing string $question_string<br/>";
									$answered_counter=$_SESSION['answered_counter'];
									$answered_strings=$_SESSION['answered_strings'];
									$answered_strings[$answered_counter]=$question_string;
									$_SESSION['answered_strings']=$answered_strings;
									$_SESSION['answered_counter']++;
								}
							}
							
							//$_SESSION['question_code']=$question_number;
							$_SESSION['question_number']=$question_number;
							if(isset($question_number_section) && $question_number_section!=''&&$question_number_section!='0'){
								$_SESSION['question_code'].='('.$question_number_section.')';						
							}
												
							$_SESSION['question_story_id']=$question_story_id;
							$_SESSION['question_image_id']=$question_image_id;
							$field_counter=0;
							
							test_time_message(); //Display time
							echo "<p><h3>".$_SESSION['paper_description']."</h3></p>";
							echo "<br/>
								<h3 style='color: blue; font-weight: bolder'>
									Question ". $_SESSION['question_counter']."
								</h3><br/>
							";
							$_SESSION['header_path']="../../";
							$_SESSION['images_path']="../../../";						
							include ($_SESSION['header_path']."general/question_header.php");						
							$_SESSION['question']= $question;
							$segment = array();
							$i = 0;
							$dash ='________';
							while($i < $segments){
								$pos = strpos($question,$dash);	
								$pos =(String)$pos;
								if($pos == '0'){
									$segment[$i] = trim($dash);
									$pos += 8;
								}
								else if($pos > '0'){
									$segment[$i] = trim(strstr($question,$dash,true));
								}
								else {
									$segment[$i] = trim($question);
								}
								$question = trim(substr($question, (int)$pos));
								$i++;
							}
							$answer_field_count=1;
							$display_question='';
							for ($i=0;$i<$segments;$i++){
								if ($segment[$i]==$dash){
									$display_question .= "<input type='text' name='answer".$answer_field_count."' id = 'answer".$answer_field_count."'  /> "." ";
									$answer_field_count++;
								}
								else{
									$display_question .= $segment[$i]." ";
								}
							}
							$_SESSION['display_question'] = $display_question;
							//echo "The question number retrieved from the table is ".$_SESSION['question_number']."<br/>";
							echo '<p>'.$display_question."<br/></p>";	
							break;
						}					
					?>
					<hr/>
					<p>
						<table width='150%'>
							<tr>
								<td><input style="background-color: green; font-weight: bolder;" type="submit" name="btnAnswer" id="btnAnswer" value="Submit Answer" /></td>
								<td><input style="background-color: red; font-weight: bolder;" type="submit" name="btnAbort" id="btnAbort" value="Abort Test" /></td>
							</tr>
						</table>
					</p>
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