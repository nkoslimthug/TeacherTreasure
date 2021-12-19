<?php
$root_path="../../";
include ($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
//include ("./student_cleanup.php");
	$_SESSION['source_form']='results_summary';
	if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
	//$_SESSION['totalTime']=$_SESSION['end_time']-$_SESSION['start_time'];
	//echo "Test duration is ".$_SESSION['totalTime']."<br>";
	function get_form()
	{
		$form ='';
		if($_SESSION['guest']==true){
			$form = '../../index.php';
		}
		else{
			$form = '../students/student_home.php';
		}
		echo $form;
	} 
	//echo "Question type is ".$_SESSION['question_type']."<br>";
	
	function playsound()
	{
		if($_SESSION['percentage']>=50){
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
	
	function score_grading()
	{
		if ($_SESSION['question_type']=='MC')
		{
			$score=$_SESSION['question_score'];
			$size=$_SESSION['size'];	
		}	
		else if($_SESSION['question_type']='SQ'){
			//$size=$_SESSION['total_paper_marks'];
			$size=$_SESSION['size'];
			$score=$_SESSION['score_counter'];
		}
		$percentage=number_format(($score/$size)*100,1);
		$_SESSION['percentage']=$percentage;
		echo '<p>';
		echo "You have scored <b style='color:blue'>$score </b><font color='#900090'>out of <b style='color:blue'>$size </b><font color='#900090'><br/>";
		if  ($percentage>=90){
			  echo "You got <font color='green'><b>$percentage</b><font color='#900090'> &#37<br/>";
			//  echo "<font color='green'><b>1</b><font color='#900090'> unit. Excellent Performance. 5-Star Effort<br/>";
		  }
		  else if ($percentage>=80){
			  echo "You got <font color='green'><b>$percentage</b><font color='#900090'> &#37<br/>";
			//  echo "<font color='green'><b>2</b><font color='#900090'> units. Great Performance<br/>";
		  }
		  else if ($percentage>=70){
			  echo "You got <font color='green'><b>$percentage</b><font color='#900090'> &#37<br/>";
			//  echo "<font color='green'><b>3</b><font color='#900090'> units. Very good<br/>";
		  }
		  else if ($percentage>=60) {
			  echo "You got <font color='orange'><b>$percentage</b><font color='#900090'> &#37<br/>";
			  //echo "<font color='orange'><b>4</b><font color='#900090'> units. Good<br/>";
		  }
		   else if ($percentage>=50){
			  echo "You got <font color='orange'><b>$percentage</b><font color='#900090'> &#37<br/>";
			 // echo "<font color='orange'><b>5</b><font color='#900090'> units. Fair<br/>";
		  }
		   else if ($percentage>=40){
			  echo "You got <font color='red'><b>$percentage</b><font color='#900090'> &#37<br/>";
			  //echo "<font color='red'><b>6</b><font color='#900090'> units. Try harder<br/>";
		  }
		   else if ($percentage>=30){
			  echo "You got <font color='red'><b>$percentage</b><font color='#900090'> &#37<br/>";
			//  echo "<font color='red'><b>7</b><font color='#900090'> units. Put more effort<br/>";
		  }
		   else if ($percentage>=20){
			  echo "You got <font color='red'><b>$percentage</b><font color='#900090'> &#37<br/>";
			 // echo "<font color='red'><b>8</b><font color='#900090'> units. Work harder next<br/>";
		  }
		  else{
			  echo "You got <font color='red'><b>$percentage</b><font color='#900090'> &#37<br/>";
			  //echo "<font color='red'><b>9</b><font color='#900090'> units. Pull up your socks<br/>";
		  }
		echo '</p>';		
	}
	function test_duration()
	{
		include ("../functions/sunungura.php");
		$duration_query="SELECT end_time-start_time AS totalTime 
						FROM tblteststart
						WHERE username='".$_SESSION['username']."'";
		//echo $duration_query."<br>";
		$duration_results=mysqli_query($cxn,$duration_query);
		$row=mysqli_fetch_assoc($duration_results);
		extract ($row);
		$totalTime=$_SESSION['duration'];
		//echo "Test took ".$totalTime." seconds<br>";
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
		echo "The test started at <span>".$_SESSION['start_time']."</span> and finished at <span>".$_SESSION['end_time']."</span><br>"; 
		//echo "<p>You took <font color='blue'><b>".$duration."<font color='#900090'></b> to complete the test.";
	}
	
	//Performance Log
	$username=$_SESSION['username'];
	
	//echo "Current user is $username<br>";
	$subject=$_SESSION['subject_name'];
	if (isset($_SESSION['topic_name']))
	{
		$topic=$_SESSION['topic_name'];
	}
	else if ($subject=='Shona')
	{
		$topic="Bvunzo";
	}
	else 
	{
		$topic='General Test';
	}
	$question_type=$_SESSION['question_type'];
	//$percentage=$_SESSION['percentage'];
	function performance_log($username,$subject,$topic,$question_type)
	{
		$root_path="../../";
		include($root_path."config/paths.php");
		include ("../functions/sunungura.php");
		$username=$_SESSION['username'];
		//include ($root_path."config/sungunura.php");
		/*echo "Current user is ".$username."<br>";
		echo "Current subject is ".$subject."<br>";
		echo "Chosen topic is ".$topic."<br>";
		echo "Question type is ".$question_type."<br>";
		echo "Score is ".$_SESSION['percentage']."<br>";*/
		$forename=$_SESSION['forename'];
		$lastname=$_SESSION['lastname'];
		$percentage=$_SESSION['percentage'];
		$start_date=$_SESSION['start_time'];
		$end_date=$_SESSION['end_time'];
		$score_log_query="INSERT INTO tblperformance
							VALUES
							('$username',
							 '$subject',
							'$topic',
							'".$_SESSION['origin']."',
							'$question_type',
							$percentage,
							'".$_SESSION['start_time']."',
							'".$_SESSION['end_time']."')";
		//echo $score_log_query."<br>";
		if (!$score_log_result=mysqli_query($cxn,$score_log_query))
		{
			echo "Current database is $dbname";
			$_SESSION['score_log_msg']="Failed to log performance ".mysqli_error($cxn)."<br>";
			//header ("Location:student_home.php");
			//exit;
		}
		else
		{
			$_SESSION['score_log_msg']="Score recorded<br>";
		}	
		//echo $_SESSION['score_log_msg']."<br>";
	}
	//echo "Start logtime before insert is ".$_SESSION['start_logtime']."<br>";
	
		$test_key=$username.'+'.$subject.'+'.$topic; 			//collect test attributes
					//	echo $test_key."<br>";
	//Logged user questions
	$logged_query="SELECT username,start_time,COUNT(*) AS question_count 
					FROM tblmctestlog 
					GROUP BY username,start_time";
	//echo $logged_query."<br>";
	$logged_result=mysqli_query($cxn,$logged_query);
	while($row=mysqli_fetch_assoc($logged_result))
	{
		extract ($row);
		//echo $username." ".$start_date." ".$question_count."<br>";
	}
	
	function kill_test($cxn)
	{
		include ("../functions/sunungura.php");
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
?>
<!doctype html>
<html>
<head>
	<title>
		Results Summary
	</title>
	<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
	<style>
		span {
			font-weight:	bold;
			color:	#0000FF;
		}
	</style>		
</head>
<body>
	<div id="banner" >
		
	</div>
	<div id="slogan_bar" >
		<center>eCompanion - It's Just What the Teacher Ordered</center>
	</div>
	<div id="message_bar" >
		
		
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner">
			<?php
				/*if (isset($_SESSION['attempt_log_query']))
				{
					echo $_SESSION['attempt_log_query']."<br>";
					unset ($_SESSION['attempt_log_query']);
				}*/
				if (isset($_SESSION['attempt_log_message']))
				{
					echo $_SESSION['attempt_log_message'];
					unset ($_SESSION['attempt_log_message']);
				}
			?>
		</div>
	<!--	<form id="form1" name="form1" method="post" action="./start_time_marker.php"> -->
		<form id="form1" name="form1" method="post" action="./start_time_marker.php">
					<p>
						<h3 style='color:red'>Results Summary</h3>
					</p>
					<hr/>
					<?php
						test_duration();
						//echo "<br>";
						score_grading();
						playsound();
						performance_log($username,$subject,$topic,$question_type);
					?>
					<hr/>
					<p><input style="background-color: green; font-weight: bolder;" type="submit" name="end_test" id="end_test" value="End Test" /></p>
					<p><input style="background-color: green; font-weight: bolder;" type="submit" name="<?php echo $test_key; ?>" id="detailed_results" value="Detailed Results" /></p>
				</form>
	</div>
	<div id="index_right_sidebar" >
	
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>