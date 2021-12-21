<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
//include ("../../../tmp/table.php");
include ("../../functions/draw_table33.php");

//include ("./mc_table_dump.php");
//include ("./mc_table_load.php");

	if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
	
	if (isset($_SESSION['source_form']))
	{
		if ($_SESSION['source_form']=="mc_all_questions")
		{
			header ('Location:./report_question.php');
		}
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
	function next_button()
	{
		if($_SESSION['question_counter']==$_SESSION['question_counter']) 
		{
			echo "End Review"; 
		}
		else
		{  
			echo "Next Question -->>";
		}
	}
	if (isset($_POST['btnAbort']))
	{
		if($_SESSION['guest']==true)
		{
			header ("Location:../../../index.php");
		}
		else
		{
			header ("Location:../administrator_home.php");
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
		if(isset($_POST['answer']))
		{
			$answer = $_POST['answer'];
		}
		else
		{
			$answer = '________';
		}
		$answer_select_query="SELECT * 
								FROM tblmcquestions 
								WHERE subject_code=$subject_code
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
		if($_SESSION['question_counter']!=$_SESSION['question_count'])
		{
			header ('Location:./mc_all_questions.php');
		}
		else
		{	
			header ('Location:../all_select_frm.php');
		}
	}
	else if (!isset($_POST['btnAnswer']))
	{
		$_SESSION['source_form']="mc_all_questions";
		header ('Location:./report_question.php');
	}
?>
