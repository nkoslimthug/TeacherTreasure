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
	//$grade=$_SESSION['grade'];
	$topic_id=$_SESSION['topic_id'];
	$question_counter=$_SESSION['question_counter'];
	$question_number=(int)$_SESSION['question_number'];
	//echo "New question counter is ".$_SESSION['question_counter']."<br/>";
	//echo "The question number retrieved from the table is ".$_SESSION['question_number']."<br/>";
	$char_counter=0;
	foreach ($_POST AS $key => $value)
	{
		if ($key!=='btnAnswer')
		{
			echo "$key = $value<br>";
			$char_count=strlen($value);
			while ($char_counter<$char_count)
			{
				$current_char=substr($value,$char_counter,1);
				echo "Character $char_counter is ".$current_char."<br>";
				//Validate each character
				$current_outcome=preg_match("~[A-Za-z0-9 ]~",$current_char);
				echo "Current outcome is $current_outcome<br/>";
				if ($current_outcome==0)
				{
					$_SESSION['answer_validate']="Invalid character entered<br>";
					//echo $_SESSION['answer_validate'];
					header("Location:../student_home.php");
					exit;
				}
				$char_counter++;
			}
		}
	/*	*/
	}
	
	$other_answers = array();
	function playsound(){ //plays audio response to a submitted answer
		if(isset($_SESSION['correct_answer'])){
			if($_SESSION['correct_answer']==1){
				echo"	
					<audio controls autoplay>
						<source src='../../../sounds/app-5.mp3' type='audio/mpeg'>
						Your browser does not support the audio element.
					</audio>
				";
			}
			else {
				echo"	
				
					<audio controls autoplay>
						<source src='../../../sounds/groans.mp3' type='audio/mpeg'>
						Your browser does not support the audio element.
					</audio>
				";
			}
		}
	}
	function next_question(){
		if ($_SESSION['question_counter']< $_SESSION['batch_size']){
			$button_caption="Next Question";
		}
		else{
			$button_caption="Get Your Result";
		}
		echo $button_caption;
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
	function answers_pool($cxn, $subject_code, $topic_id, $question_number,$a){
		$answer_select_query="SELECT * FROM tblstructuredanswers WHERE 
							subject_code=$subject_code 
							AND topic_id=$topic_id
							AND question_number={$_SESSION['question_number']}"							
							;
		//echo "$answer_select_query<br/>";					
		if (!$result=mysqli_query($cxn,$answer_select_query)){
			$message="Failed to retrieve question: \n".mysqli_error($cxn);
			echo "<script type = 'text/javascript'>alert('".$message."')</script>";
			exit;
		}
		while($row=mysqli_fetch_assoc($result)){
			extract ($row);		
			if($answer_field==$a){
				$other_answers[]=$answer;
			}
		}
		return $other_answers;
	}
	
	$i = 0;
	$x=0;
	$answer= array();
	$other_answers = array();
	$answer_count = 0;
	$answer_found = false;
	$total_unsubmitted_answers =0;
	foreach ($_POST as $field =>$value){
		if(substr($field,0,6)=='answer'){
			$submitted_answer_field[$i] =  substr(substr($field,6),0,1);
			if($value!=''){
				$submitted_answer[$i] = $value;					
			}
			else {
				$submitted_answer[$i] = '________';
				$total_unsubmitted_answers++;
			}
			$i++;
		}
	//echo "$field = $value<br>";
	}
	$total_answers_submitted=$i;
	echo "There are $i TOTAL submitted answers<br/>";
	
	$interchangeable_query="SELECT * FROM tblstructuredquestions WHERE 
						subject_code=$subject_code 
						AND topic_id=$topic_id
						AND question_number=$question_number"							
						;
	echo $interchangeable_query."<br>";
	if (!$result=mysqli_query($cxn,$interchangeable_query)){
		$message="Failed to retrieve question :\n".mysqli_error($cxn);
		echo "<script type = 'text/javascript'>alert('".$message."')</script>";
		exit;
	}
	else{
		$row=mysqli_fetch_assoc($result);
		extract($row);	
		if (isset($interchangeable)){
		$_SESSION['interchangeable']=$interchangeable;	
		}
	}
	
	for($i = 0; $i < $total_answers_submitted; $i++){
		$answer_found = false;
		$_SESSION['correct_answer']=0;
		$answer_select_query="SELECT * FROM tblstructuredanswers WHERE 
							subject_code=$subject_code 
							AND topic_id=$topic_id
							AND question_number=$question_number"							
							;
		//echo "$answer_select_query<br/>";
		if (!$result=mysqli_query($cxn,$answer_select_query)){
			$message="Failed to retrieve question:\n".mysqli_error($cxn);
			echo "<script type = 'text/javascript'>alert('".$message."')</script>";
			exit;
		}
		if($total_answers_submitted==1){
			$strAnswer=	"<b>The answer ";
		}
		else {
			$strAnswer=	"<b>Part ".($i+1)." of the answer";
		}
		
		while($row=mysqli_fetch_assoc($result)){
			$_SESSION['correct_answer']==0;
			extract ($row);	
			$expected_answer[$i] = $answer;
			//echo "The answers<br/>";
			//echo "Expected answer is $expected_answer[$i]<br/>";
			//echo "Submitted answer is $submitted_answer[$i]<br/>";
			$answer_count++;
			if($submitted_answer[$i] == $expected_answer[$i]){				
				if($_SESSION['interchangeable']=='Yes'){
					$found =false;
					for($j=0; $j < $i ;$j++){
						if ($submitted_answer[$j] == $submitted_answer[$i]){
							$found=true;
						}
					}
					if($found==false){
					
						$correct[] = $strAnswer." is correct !!!<br/>Answer entered was <i style='color:green'>'".$submitted_answer[$i]."'.</i>";
						$_SESSION['score_counter'] += $marks;
						$_SESSION['correct_answer']=1;
						$answer_found = true;
					}
				}		
				else{
					if($submitted_answer_field[$i]==$answer_field ){				
						$correct[] = $strAnswer." is correct !!!<br/>Answer entered was <i style='color:green'>'".$submitted_answer[$i]."'.</i>";
						$_SESSION['score_counter'] += $marks;
						$_SESSION['correct_answer']=1;
						$answer_found = true;
					}
				}
			}
		}
		if(!$answer_found){
			$incorrect[] = $strAnswer." was incorrect !!!<br/>Answer entered was <i style='color:red'>'".$submitted_answer[$i]."'.</i>";
			$_SESSION['correct_answer']==0;
		}
	}
	
	if($total_answers_submitted==$total_unsubmitted_answers){
		unset($incorrect);
		$incorrect[] = "<b><i style='color:red'>No answer was submitted.</i>";
		$_SESSION['correct_answer']==0;
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
		
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner">eCompanion - It's Just What the Teacher Ordered </div>
			<?php
								echo "<p><h3>".$_SESSION['paper_description']."</h3></p>";
								echo "
									<h3 style='color: blue; font-weight: bolder'>
										Question ". $_SESSION['question_counter']."
									</h3>
								";
								$_SESSION['header_path']="../../";
								$_SESSION['images_path']="../../../";						
								include ($_SESSION['header_path']."general/question_header.php");
								echo "<p><b style='color:#900090'>".$_SESSION['question']."</b><br/></p>";
								echo '<hr/>';
								echo "
									<h3 style='color: red; font-weight: bolder'>
										Answer Analysis:
									</h3>
								";
								if(isset($correct)){
										
									foreach($correct as $correct){
										echo "<p style='color:blue'>".$correct."</p>";
									}
									echo "<b>Answer pool is:</b><br/>";
								}
								if(isset($incorrect)){
									foreach($incorrect as $incorrect){
										echo "<p style='color:blue'>".$incorrect."</p>";
									}
									if($answer_count==1){
										echo "The correct answer is:<br>";
									}
									else{
										echo "The pool of correct answers is:<br>";
									}
								}
								echo '<p>';
								
								$total_expected_answers=0;
								$count_query="SELECT MAX(answer_field) as total_expected_answers FROM tblstructuredsnswers WHERE 
													subject_code=$subject_code 
													AND topic_id=$topic_id
													AND question_number=$question_number"							
													;
								//echo "$count_query<br/>";
								if ($result=mysqli_query($cxn,$count_query)){
									$row=mysqli_fetch_assoc($result);
									extract ($row);	
									for($i=1;$i<=$total_expected_answers;$i++){
										$count =1;
										$subject_code=$_SESSION['subject_code'];
										//$grade=$_SESSION['grade'];
										$topic_id=$_SESSION['topic_id'];
										$question_counter=$_SESSION['question_counter'];
										$other_answers = answers_pool($cxn, $subject_code, $topic_id, $question_number,$i);
										if($total_expected_answers > 1){
											echo "Part $i<br/>";
										}										
										foreach($other_answers as $other_answer){
											echo "-  <b style='color:green'>".$other_answer."</b><br/>";
										}
									}
								}
								//playsound();
								echo '</p>';								
							?>
								<hr/>
							<form  autocomplete="off" id="form1" name="form1" method="post" action="sq_ind_questions.php">
								<table width="150%" border="0">
									<tr>
										<td><input style="background-color: green; font-weight: bolder;" type="submit" name="btnNext" id="btnNext" value="<?php next_question() ?>"/></td>
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