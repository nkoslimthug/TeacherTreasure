<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
//include ("../../../tmp/table.php");
include ("../../functions/draw_table33.php");
//include ("./mc_question_store.php");
//include ("./mc_questions_load.php");
include ("./mc_table_dump.php");
include ("./mc_table_load.php");

	if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
	
		
	if ($_SESSION['question_counter']==$_SESSION['batch_size']) 
	{
		$_SESSION['end_time']=date("Y-m-d H:i:s",time('now'));
		$_SESSION['duration']=$_SESSION['end_time']-$_SESSION['start_time'];
	}

	function playsound()
	{
		if ($_SESSION['correct_answer']==1)
		{
			echo"	
				<audio controls autoplay hidden>
					<source src='../../../sounds/app-5.mp3' type='audio/mpeg'>
					Your browser does not support the audio element.
				</audio>
			";
		}
		else 
		{
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
		if($_SESSION['question_counter']==$_SESSION['batch_size']) 
		{
			echo "Get Result"; 
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
			header ("Location:../student_home.php");
		}
		exit;
	}
	if (isset($_POST['btnAnswer']))		//Answer button pressed
	{
		$subject_name = $_SESSION['subject_name'];
		$subject_code = $_SESSION['subject_code'];
		$question_counter = $_SESSION['question_counter'];
		
		if(isset($_POST['answer']))
		{
			$answer = $_POST['answer'];
		}
		else
		{
			$answer = '________';
		}
		$answer_select_query="SELECT q.true_answer,q.option1,q.option2,q.option3
								FROM tblmcquestions q
								WHERE subject_code=$subject_code
								AND topic_id=".$_SESSION['topic_id']." 
								AND question_number=".$_SESSION['question_number'];
		//echo $answer_select_query."<br>";
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
		else
		{
			if($answer == '________')		//No answer
			{
				$incorrect[] = "<b><br/><i style='color:red'>'No answer was submitted!!!'</i>.<br/>Expected answer is <i style='color:green'>'".$_SESSION['true_answer']."'</i>.</b><br/>";
				$style_colour="red";
			}
			else							//Incorrect answer
			{
				$incorrect[] = "<b>Incorrect answer given !!!<br/>Answer entered is <i style='color:red'>'".$answer."</i>.<br/>Expected answer is <i style='color:green'>'".$_SESSION['true_answer']."'</i>.</b><br/>";
				$style_colour="red";
			}
			$_SESSION['correct_answer']=0;
		}
		//playsound();
		$_SESSION['verdict']=$_SESSION['correct_answer'];
		$_SESSION['submitted_answer']=$answer;
		//store questions
		//current time
		
		
		if ($_SESSION['question_counter']==1)
		{
			$questions_counter=0;
			$questions_count=0;
			$_SESSION['questions_counter']=$questions_counter;
			$_SESSION['questions_count']=$questions_count;
			$questions=[];
			$successes=[];
		}
		else
		{
			$successes=$_SESSION['successes'];
			$questions_counter=$_SESSION['questions_counter'];
			$questions_count=$_SESSION['questions_count'];
		}
		
			while ($_SESSION['questions_counter']<=$_SESSION['batch_size'])
			{
				$questions_counter=$_SESSION['questions_counter'];
				$questions[$questions_counter][0]=$_SESSION['subject_code'];
				$questions[$questions_counter][1]=$_SESSION['topic_id'];
				$questions[$questions_counter][2]=$_SESSION['question_number'];
				$questions[$questions_counter][3]=$_SESSION['question_counter'];
				$questions[$questions_counter][4]=$_SESSION['question_complete'];
				$questions[$questions_counter][5]=$_SESSION['choices0'];
				$questions[$questions_counter][6]=$_SESSION['choices1'];
				$questions[$questions_counter][7]=$_SESSION['choices2'];
				$questions[$questions_counter][8]=$_SESSION['choices3'];
				$questions[$questions_counter][9]=$_SESSION['true_answer'];
				$questions[$questions_counter][10]=$_SESSION['instruction'];
				if (isset($_SESSION['story_id'])) { $questions[$questions_counter][11]=$_SESSION['story_id']; }
				if (isset($_SESSION['image_id'])) { $questions[$questions_counter][12]=$_SESSION['image_id']; }
				$questions[$questions_counter][13]=$_SESSION['submitted_answer'];
				$questions[$questions_counter][14]=$_SESSION['verdict'];
				$questions[$questions_counter][15]=$_SESSION['username'];
				$questions[$questions_counter][16]=$_SESSION['start_time'];
				//$questions[$questions_counter][17]=$_SESSION['end_time'];
				//-----------------------------------------------			
				
						
				//success log
				//success_log array
				$successes[$questions_counter][0]=$_SESSION['subject_code'];
				$successes[$questions_counter][1]=$_SESSION['topic_id'];
				$successes[$questions_counter][2]=$_SESSION['question_number'];
				$successes[$questions_counter][3]=$_SESSION['verdict'];
				//$successes[$questions_counter][4]=$_SESSION['score_count'];
				
				//echo $successes[$questions_counter][0]." ";
				//echo $successes[$questions_counter][1]." ";
				//echo $successes[$questions_counter][2]." ";
				//echo $successes[$questions_counter][3]." ";
				//echo $_SESSION['questions_counter']."<br>";
				//echo "<br>Test size is ".$_SESSION['batch_size']."<br>";
						$_SESSION['complete']=0;
						mc_table_dump();
						//
				$_SESSION['questions_counter']++;
				$_SESSION['questions_count']++;
				break;  //leave loop
			}
			
		}
		$_SESSION['successes']=$successes;
		//echo "Question counter is ".$_SESSION['questions_counter']."<br>";
		//echo "Question count is ".$_SESSION['questions_count']."<br>";
		if ($_SESSION['questions_counter']==$_SESSION['batch_size'])
		{
			$questions_count=$_SESSION['questions_count'];
			$questions_counter=$_SESSION['questions_counter'];
			$questions_counter=0;
			while ($questions_counter<$questions_count)
			{
				//echo $successes[$questions_counter][0]." ";
				//echo $successes[$questions_counter][1]." ";
				//echo $successes[$questions_counter][2]." ";
				//echo $successes[$questions_counter][3]." ";
				//echo $questions_counter."<br>";
				$questions_counter++;
			}
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
		
	</div>
	<div id="content" >
		<div id="message_banner">  </div>
		<form id="form1" name="form1" method="post" action="mc_scheduled_test.php">
					<table border="0" style="color:#900090;">
						<tr>
						<td colspan ='2'>
						<?php
							echo "<p><h2>".$_SESSION['test_description']."</h2></p>";
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
							echo "<p><b style='color:blue'>".$_SESSION['question_complete']."</b><br/></p>";
							echo "</b><br/></p>";
							echo "<hr/>";
							echo "<p>";
								echo "<b style='color: red;'><u>Answer Analysis</u></b>";
							echo "</p>";
							if ($answer == '________')
							{
								echo "No answer submitted<br>";
							}
							else
							{
								echo "<b color='blue'> Submitted answer is <br><i color='purple' >\t".$answer."</i></b><br>";
							}
														
							if ($_SESSION['question_counter']==$_SESSION['batch_size'])			//confirm completed tests
							{
								//extract start time
								$start_time_query="SELECT start_time AS logged_time
													FROM tblteststart
													WHERE username='".$_SESSION['username']."'";
								//echo $start_time_query."<br>";
								$start_time_result=mysqli_query($cxn,$start_time_query);
								$row=mysqli_fetch_assoc($start_time_result);
								extract ($row);
								//$_SESSION['start_time']=$start_time;
								
								
								//echo $_SESSION['completed_message'];
								//echo "Logtime after last question is ".$_SESSION['start_logtime']." <br>";
							}
						?>
						</td>
						</tr>
						<hr/>				
						<tr>
							<td>
							<input style="background-color: green; font-weight: bolder;" type="submit" name="btnQuestion" id="btnQuestion" value= "<?php next_button()?>"/></td>
							
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