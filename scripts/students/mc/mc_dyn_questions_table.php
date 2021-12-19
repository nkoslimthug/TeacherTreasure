<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
include ("../../../tmp/table.php");
include ("../../functions/draw_table33.php");

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
	/*function test_duration(){
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
	}*/
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
		<form id="form1" name="form1" method="post" action="mc_dyn_answers.php">
					<?php     
					//Retrieve question
					//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

						// Test time progress
						//test_time_message();
						echo "<br><p><h3>".$_SESSION['paper_description']."</h3></p>";
						//test_duration();
						echo "<b><p>".$_SESSION['instruction']."</b></p>";
						// Retrieve story
						if (isset ($_SESSION['story_id'])){
							$story_id=$_SESSION['story_id'];
						if ($story_id!=0) {
							$story_query="SELECT story_title,story_content FROM tblstories 
							WHERE subject_code=$subject_code AND story_id={$_SESSION['story_id']}";
							//echo "$story_query<br/>";
							$story_result=mysqli_query($cxn,$story_query);
							$row=mysqli_fetch_assoc($story_result);
							extract ($row);
							echo "<center><h3>$story_title</h3></center>";
							echo "<p>$story_content<br/></p>";
						}}
						//Display Image
						if ($_SESSION['image_id']!=0)
							{
								echo "<center>".$image_title."</center><br><br>";
								echo "<center><img src='$image_location'></img></center>";
							}
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
						echo "<p><b style='color:blue'>";
						draw_math_table();
						echo "</b><br/></p>";
						//echo $question."<br>";
						$question=$_SESSION['question_complete'];
					
					?>
					
					<?php  
						//$question;
						/*echo "Instruction is ".$_SESSION['instruction']."<br>";
								echo "First choice is ".$_SESSION['choices0']."<br>"; 
								echo "Second choice is ".$_SESSION['choices1']."<br>";
								echo "Third choice is ".$_SESSION['choices2']."<br>";
								echo "Fourth choice is ".$_SESSION['choices3']."<br>";*/
								$choices[0]=$_SESSION['choices0'];
								$choices[1]=$_SESSION['choices1'];
								$choices[2]=$_SESSION['choices2'];
								$choices[3]=$_SESSION['choices3'];
					?>
					<hr/>
					<p>
						<b style='color: red;'><u>Answers</u></b>
					</p>
					<p>
						<b>A</b>
						<label for="radio1">
							<input type="radio" name="answer" id="radio1" value="<?php echo $choices[0] ?>"/><?php echo $choices[0] ?></label>
					</p>
					<p>
						<b>B</b>
						<label for="radio2">
							<input type="radio" name="answer" id="radio2" value="<?php echo $choices[1] ?>"/><?php echo $choices[1] ?></label>
					</p>
					<p>
						<b>C</b>
						<label for="radio3">
							<input type="radio" name="answer" id="radio3" value="<?php echo $choices[2] ?>"/><?php echo $choices[2] ?></label>
					</p>
					<p>
						<b>D</b>
						<label for="radio4">
							<input type="radio" name="answer" id="radio4" value="<?php echo $choices[3] ?>"/><?php echo $choices[3] ?></label>
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
	
	<div id="footer" >
		<font="brown"></font>
	</div>
</body>
</html>