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

//$_SESSION['paper_description']=$subject_name." GRADE ".$grade." - ".$_SESSION['topic_name']." TEST<br/>";
//echo "Testing<br/>";
function playsound()
{
	echo"	
		<audio controls autoplay hidden>
				<source src='../../../sounds/groans.mp3' type='audio/mpeg'>
				Your browser does not support the audio element.
			</audio>
		";
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

if (isset($_POST['btnNext']))
{
	//$_SESSION['question_counter']++;
	//echo "New question counter is ".$_SESSION['question_counter']."<br/>";
}
	
$_SESSION['paper_type']=2;
//$_SESSION['batch_size']=5;
if ($_SESSION['question_counter']==0)
{ //record start time
	$current_day=date('l');
	$start_time=time('now');
	$start_timestamp=date("Y-m-d H:i:s",$start_time);
	$message_today="Today is $current_day<br/><br/>";
	$_SESSION['start_timestamp']=$start_timestamp;
	$_SESSION['start_time']=$start_time;
	$_SESSION['question_type']="SQ";
    $section_count=0;
	$section_counter=0;	
	//$name_counter=0;
	if (isset($_SESSION['quota_tracker']))
	{
		echo "Quota tracker at the top = ". $_SESSION['quota_tracker']."<br>";
	}
			
		function test_time_message()
		{
		echo "<p> ";
		if ($_SESSION['question_counter']==1) 
		{
			echo "<b>Test started at <font color='blue'>"; echo $_SESSION['start_timestamp']; echo "</b><font color='#900090'></p>";
		}
		}
	//Manage paper question sections
	//Retrieve all sections for this subject and grade
	
	$section_query="SELECT * FROM tbltestsections WHERE subject_code=$subject_code AND grade=$grade";
	echo $section_query."<br>";
	$section_result=mysqli_query($cxn,$section_query);
	while($row=mysqli_fetch_assoc($section_result))
	{
		extract ($row);
		$section_counter++;
//		echo "Upon loading...<br>";
//		echo "+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++<br>";
		echo "Subject ".$subject_code." is ".$section_name." and has a quota of ".$section_quota." questions<br>";
//		echo "Grade is ".$grade." paper of subject ID ".$subject_code."<br>";
		$section_string=$subject_code.$grade.$section_id.$section_quota; //Generate string
		$section_string=strval($section_string); //convert to text string
//		echo "Section string is ".$section_string."<br/>";
		$record_length=strlen($section_string);
//		echo "String is $record_length characters long<br>";
		//building section names array
		$sections[$section_counter]=$section_string;
		$names[$section_counter]=$section_name;
//		echo "Section name is <b>$section_name</b><br>";
//		echo "End of loaded raw values<br>";
//		echo "---------------------------------------------------------------------------------<br>";
		
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
$_SESSION['quota_tracker']++;
$_SESSION['question_counter']++; 	//advance question
echo "Current section is ".$_SESSION['section_counter']."<br>";
echo "There are ".$_SESSION['section_count']." sections<br>";
//$_SESSION['section_tracker']=0;
   //initialise quota_tracker
   
echo "Quota tracker has just been reset to ".$_SESSION['quota_tracker']."<br>";
while ($_SESSION['section_counter']<$_SESSION['section_count'])
{
	$sections=$_SESSION['sections'];
	$names=$_SESSION['names'];
	$section_id=$_SESSION['section_counter'];
	echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++<br>";
	$section_string=$sections[$section_id];
	$record_length=strlen($section_string);
	$section_name=$names[$section_id];
	echo "Section string is $section_string<br>";
	
	if ($record_length==4)
	{
		$_SESSION['section_quota']=(int)substr($sections[$section_id],3,1); 
	}
	if ($record_length==5)
	{
		$_SESSION['section_quota']=(int)substr($sections[$section_id],3,2); 
	}
	echo "Section quota = ".$_SESSION['section_quota']."<br>";
	echo "Quota tracker = ".$_SESSION['quota_tracker']."<br>";
	$quota_tracker=$_SESSION['quota_tracker'];
	if ($_SESSION['quota_tracker']>$_SESSION['section_quota']) 
	{ 
		$_SESSION['section_counter']++; 
		$_SESSION['quota_tracker']=1;
		//$_SESSION['section_quota']=$section_quota;
		echo "Switching sections<br>";
	}
	$section_id=$_SESSION['section_counter'];
	$names=$_SESSION['names'];
	echo "Current section string is ".$sections[$section_id]."<br>";
	echo "----------------------------------------------------------------------<br>";
	while ($_SESSION['quota_tracker']<=$_SESSION['section_quota'])
	{
		echo "Quota tracker = ".$_SESSION['quota_tracker']."<br>";
		echo "Section quota = ".$_SESSION['section_quota']."<br>";
		echo "Section $section_id is ".$names[$section_id]." and has a quota of ".$_SESSION['section_quota']." and is currently at value ".$_SESSION['quota_tracker']." <br>";
		echo "Current quota value = ".$_SESSION['quota_tracker']."<br>"; 
		//Load random question
		//$answered_strings='';
			$answered_strings='';
						while($_SESSION['question_counter']<=$_SESSION['paper_size'])
						{
							$question_select_query="SELECT * FROM tblstructuredquestions WHERE 
													subject_code=$subject_code 
													AND lower_grade<=$grade
													AND upper_grade>=$grade
													AND topic_id IN (SELECT topic_id 
																	FROM tbltopics
																	WHERE section_id=$section_id)
													ORDER BY rand() LIMIT 1";
							echo $question_select_query."<br/>";
							$question_select_result=mysqli_query($cxn,$question_select_query);
							$row=mysqli_fetch_assoc($question_select_result);
							extract ($row);
							
							//populate questions already answered
							
							$question_string=$subject_code.$grade.$topic_id.$question_number; //Generate string
							$question_string=strval($question_string);
							echo "Question string in section is $question_string<br/>";
							
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
							if(isset($question_number_section) && $question_number_section!=''&&$question_number_section!='0')
							{
								$_SESSION['question_code'].='('.$question_number_section.')';						
							}
												
							$_SESSION['question_story_id']=$question_story_id;
							$_SESSION['question_image_id']=$question_image_id;
							$field_counter=0;
							
							$_SESSION['header_path']="../../";
							$_SESSION['images_path']="../../../";						
							//include ($_SESSION['header_path']."general/question_header.php");						
							$_SESSION['question']= $question;
							break;
						}


	if ($_SESSION['question_counter']>$_SESSION['batch_size']){
		echo "Question counter = ".$_SESSION['question_counter']."<br>";
		echo "Batch size = ".$_SESSION['batch_size']."<br>";
		$end_time=time('now');
		$end_timestamp=date("Y-m-d H:i:s",$end_time);
		$_SESSION['end_timestamp']=$end_timestamp;
		$_SESSION['end_time']=$end_time;
		$_SESSION['duration']=$_SESSION['end_time']-$_SESSION['start_time'];
		$_SESSION['question_counter']=0;
		$_SESSION['message_batch_complete']= "Test completed<br/>"; 	//Signal batch completion
		$total_paper_marks_query="SELECT DISTINCT question_count, answer_field, marks FROM tblStructuredAnswers WHERE 
							subject_code=$subject_code 
							";
		echo "$total_paper_marks_query<br/>";				
		$total_paper_marks = 0;
		while($row=mysqli_fetch_assoc($result))
		{
			extract ($row);
			$total_paper_marks += $marks;
			
		}
		if ($_SESSION['topic_id']==0){
			$_SESSION['total_paper_marks']= $total_paper_marks;
		}
		else {
			$_SESSION['total_paper_marks']=$_SESSION['batch_size'];
		}
		$_SESSION['size']=$_SESSION['paper_size'];
		//echo "Total paper marks = ".$_SESSION['total_paper_marks']."<br/>";
		header ("Location:../results_summary.php"); 
		exit;
	}
	break;
	}
	break;
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
		<marquee>eCompanion - It's Just What the Teacher Ordered</marquee>
	</div>
	<div id="message_bar" >
		
		<?php
				//include($root_path."config/page_header.php");
			?>
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			<ul>
				      
			</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner"> eCompanion - It's Just What the Teacher Ordered</div>
			<form  autocomplete="off" id="form1" name="form1" method="post" action="sq_dyn_answers.php" autocomplete="off" spellcheck="false" >
					<?php     
							//Retrieve question
							//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
							//echo $_SESSION['batch_size']."<br/>";
						foreach ($_SESSION['answered_strings'] AS $key=>$value)
						{
							echo "$key = $value<br>";
						}
						$answered_strings='';
						while($_SESSION['question_counter']<=$_SESSION['paper_size'])
						{
							$_SESSION['question_number']=$question_number;
							if(isset($question_number_section) && $question_number_section!=''&&$question_number_section!='0'){
								$_SESSION['question_code'].='('.$question_number_section.')';						
							}
												
							$_SESSION['question_story_id']=$question_story_id;
							$_SESSION['question_image_id']=$question_image_id;
							$field_counter=0;
							if ($_SESSION['question_counter']==1)
							{
								test_time_message(); //Display time
							}
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
									$display_question .= "<input type='text' name='answer".$answer_field_count."' id = 'answer".$answer_field_count."' /> "." ";
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