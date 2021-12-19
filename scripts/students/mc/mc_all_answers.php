<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
//include ("../../../tmp/table.php");
include ("../../functions/draw_table33.php");
include ("./mc_table_dump.php");
include ("./mc_table_load.php");

	if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}

	function playsound(){
		if($_SESSION['correct_answer']==1){
			echo"	
				<audio controls autoplay hidden>
					<source src='../../../sounds/app-5.mp3' type='audio/mpeg'>
					Your browser does not support the audio element.
				</audio>
			";
		}
		else {
			echo"	
				<audio controls autoplay hidden>
					<source src='../../../sounds/groans.mp3' type='audio/mpeg'>
					Your browser does not support the audio element.
				</audio>
			";
		}
	}
	function next_button(){
		if($_SESSION['question_counter']==$_SESSION['question_counter']) {
			echo "End Review"; 
		}
		else{  
			echo "Next Question -->>";
		}
	}
	if (isset($_POST['btnAbort'])){
		if($_SESSION['guest']==true){
			header ("Location:../../../index.php");
		}
		else{
			header ("Location:../student_home.php");
		}
		exit;
	}
	if (isset($_POST['btnAnswer']))
	{
		$subject_name = $_SESSION['subject_name'];
		$subject_code = $_SESSION['subject_code'];
		$grade = $_SESSION['grade'];
		$question_counter = $_SESSION['question_counter'];
		$topic_id=$_SESSION['topic_id'];
		if(isset($_POST['answer'])){
			$answer = $_POST['answer'];
		}
		else{
			$answer = '________';
		}
		$answer_select_query="SELECT * FROM tblmcquestions WHERE subject_code=$subject_code
								AND topic_id=$topic_id
								AND question_number=$question_counter";
		if (!$answer_select_result=mysqli_query($cxn,$answer_select_query))
		{
			$message= "Error:\n".mysqli_error($cxn);
			echo "<script type = 'text/javascript'>alert('".$message."')</script>";
			exit;
		}
		$row=mysqli_fetch_assoc($answer_select_result);
		extract ($row);
		if(strcmp($answer,$_SESSION['true_answer'])==0)	//correct answer
		{
			$correct[] = "<b>You are correct!!!<br>The answer is <i style='color:green'>".$answer."</i>.<br>";
			$style_colour="green";
			$_SESSION['question_score']++;
			$_SESSION['correct_answer']=1;
		}
		else{
			if($answer == '________')		//No answer
			{
				$incorrect[] = "<b><br/><i style='color:red'>'No answer was submitted!!!'</i>.<br/>Expected answer was <i style='color:green'>'".$_SESSION['true_answer']."'</i>.</b><br/>";
				$style_colour="red";
			}
			else							//Incorrect answer
			{
				$incorrect[] = "<b>Incorrect answer given !!!<br/>Answer entered is <i style='color:red'>'".$answer."</i>.<br/>Expected answer is <i style='color:green'>'".$_SESSION['true_answer']."'</i>.</b><br/>";
				$style_colour="red";
			}
			$_SESSION['correct_answer']=0;
		}
		playsound();
		$_SESSION['verdict']=$_SESSION['correct_answer'];
		$_SESSION['submitted_answer']=$answer;
	}
	//	echo "Logtime after last insert ".$_SESSION['start_logtime']."<br>";
	
	
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
		
	</div>
	<div id="content" >
		<div id="message_banner">  </div>
		<form id="form1" name="form1" method="post" action="mc_all_questions.php">
					<table border="0" style="color:#900090;">
						<tr>
						<td colspan ='2'>
						<?php
							echo "<p><h2>".$_SESSION['paper_description']."</h2></p>";
							echo "
								<h3 style='color: purple; font-weight: bolder'>
									".$_SESSION['instruction']."
								</h3>
								<h2 style='color: blue; font-weight: bolder'>
									Question ".$_SESSION['question_counter']."
								</h2>
							";
							$_SESSION['header_path']="../../";
							$_SESSION['images_path']="../../../";						
							include ($_SESSION['header_path']."general/question_header.php");
							echo "<p><b style='color:blue'>";
							//echo "Table based flagged is ".$_SESSION['table_based']."<br>";
							/*if (isset($_SESSION['table_based']))
							{
								if ($_SESSION['table_based']==1)
								{
									echo "<h3 style='color: purple; font-weight: bolder'>";
									draw_math_table();
									echo "</h3>";
								}
							*/
									echo $_SESSION['question_complete'];
			
							
							echo "</b><br/></p>";
							echo "<hr/>";
							echo "<p>";
								echo "<b style='color: red;'><u>Answer Analysis</u></b>";
							echo "</p>";
							if(isset($correct)){
								foreach($correct as $correct){
									echo "<p style='color:blue'>".$correct."</p>";
								}
							}
							if(isset($incorrect)){
								foreach($incorrect as $incorrect){
									echo "<p style='color:blue'>".$incorrect."</p>";
								}
							}
							$_SESSION['table_based']=0;  //restore default value
							//$_SESSION['start_date']=$start_date; //start time for this test
						//	echo "Logtime before update is ".$_SESSION['start_logtime']."<br>";
							
						/*	if ($_SESSION['question_counter']==$_SESSION['batch_size'])			//confirm completed tests
							{
								//echo "Question counter now equals batch size<br>";
								$completed_query="UPDATE tblmctestlog SET complete=1,
								start_date='{$_SESSION['start_logtime']}'
								WHERE subject_code=".$_SESSION['subject_code'].
								" AND topic_id=".$_SESSION['topic_id'].
								" AND username='".$_SESSION['username'].
								"' AND start_date='".$_SESSION['start_logtime']."'";
								//echo $completed_query."<br>";
								if (!$completed_result=mysqli_query ($cxn,$completed_query))
								{
									$_SESSION['completed_message']="Failed to update questions: ".mysqli_error($cxn)."<br>";
								}
								else
								{
									$_SESSION['completed_message']="Questions confirmed<br>";
								}
								//echo $_SESSION['completed_message'];
								//echo "Logtime after last question is ".$_SESSION['start_logtime']." <br>";
							}*/
						?>
						</td>
						</tr>
						<hr/>				
						<tr>
							<td>
							<input style="background-color: green; font-weight: bolder;" type="submit" name="btnQuestion" id="btnQuestion" value= "Next Question"/></td>
							<td><input style="background-color: red; font-weight: bolder;" type="submit" name="btnAbort" id="btnAbort" value="Abort Test" /></td>
						</tr>
					</table>
				</form>
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>