<?php
include ("../../../config/sungunura.php");
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
	}

if ($_SESSION['source_form']=='scores_process')
{
	foreach ($_POST as $key => $value)
	{
		echo "$key = $value<br>";
		$question_string=$key;
	}
	echo $question_string."<br>";
	$subject_code="";
	$topic_id="";
	$question_number="";
	$char_counter=0;
	$pipe_counter=0;
	$char_count=strlen($question_string);

	while ($char_counter<$char_count)
	{
		$current_char=substr($question_string,$char_counter,1);
		if ($pipe_counter==0)
		{
			if ($current_char=="|")
			{
				$pipe_counter++;
				$char_counter++;
				continue;
			}
			$subject_code=$subject_code.$current_char;
		}
		if ($pipe_counter==1)
		{
			if ($current_char=="|")
			{
				$pipe_counter++;
				$char_counter++;
				continue;
			}
			$topic_id=$topic_id.$current_char;
			
		}
		if ($pipe_counter==2)
		{
			$question_number=$question_number.$current_char;
			
		}
		$char_counter++;
	}
	echo "Subject code is $subject_code<br>";
	echo "Topic ID is $topic_id<br>";
	echo "Question number is $question_number<br>";
	$_SESSION['subject_code']=(int)$subject_code;
	$_SESSION['topic_id']=(int)$topic_id;
	$_SESSION['question_number']=(int)$question_number;
}
else
{
	$subject_code=$_SESSION['subject_code'];
	$topic_id=$_SESSION['topic_id'];
	$question_number=$_SESSION['question_number'];
}

#spelling or typing mistake
#clarity
#too deep
#no correct answer
#multiple correct answers
?>
<!doctype html>
<html>
<head>
	<title>
		Pupil Selection
	</title>
	<link rel="stylesheet"
			type="text/css"
			<link rel="stylesheet"	type="text/css"	href="../../../style/college.css" />
</head>
<body>
	<div id="banner" style="text-align:center";>
	
	</div>
	<div id="slogan_bar" style="background:white;color:purple;font-style:bold;" >
		<i>eCompanion - It's Just What the Teacher Ordered</i>
	</div>
	<div id="index_left_sidebar" style="background:white";>
		<br><br><br>
		<div id="menu">
			<ul>
				<li><a href="../administrator_home.php" >Home</a></li>
			</ul>
		</div>	
	</div>
	<div id="content" style="color:purple";>
			<div id="message">
					<?php 
						if (isset($_SESSION['person_insert_message']))
						{
							echo $_SESSION['person_insert_message']."<br/>";
							unset ($_SESSION['person_insert_message']);
						}
					?>
				</div>
				<form method="post" id="student_self_reg_frm.php" action="report_question_process.php">
					<table width="50%" border="2">
						<th id="self_reg_head" colspan="4">Pupil Selection</th>
						<tr>
							<td colspan="4">&nbsp</td>
						</tr>
						<tr>
							<td width="20%">Subject Code:</td>
							<td width="30%">
								<input type="text" name="subject_code" id="subject_code" readonly="readonly" value="<?php echo $subject_code; ?>" />									
							</td>
						</tr>
						<tr>
							<td colspan="4">&nbsp</td>
						</tr>
						<tr>
							<td width="20%">Topic ID:</td>
							<td width="30%">
								<input type="text" name="topic_id" id="topic_id" readonly="readonly" value="<?php echo $topic_id; ?>" />									
							</td>
						</tr>
						<tr>
							<td colspan="4">&nbsp</td>
						</tr>
						<tr>
							<td width="20%">Question Number:</td>
							<td width="30%">
								<input type="text" name="question_number" id="question_number" readonly="readonly" value="<?php echo $question_number; ?>" />									
							</td>
						</tr>
						<tr>
							<td colspan="4">&nbsp</td>
						</tr>
						
						<tr>
							<td width="20%">Flaw Type:</td>
							<td width="30%">
								<select name="flaw_type" id="flaw_type" >
									<option value="">Select flaw type</option>
									<option value="spelling">SPELLING</option>
									<option value="grammar">INCORRECT GRAMMAR</option>
									<option value="wrong_answer">WRONG ANSWER MARKED AS CORRECT</option>
									<option value="no_answer">NO CORRECT ANSWER SUPPLIED</option>
									<option value="multiple_answers">MULTIPLE CORRECT ANSWERS SUPPLIED</option>
									<option value="clarity">QUESTION NOT CLEAR</option>
									<option value="too_deep">QUESTION TOO DIFFICULT</option>
									<option value="inappropriate">INAPPROPRIATE QUESTION OR ANSWERS</option>
									<option value="other">OTHER</option>									
								</select>
							</td>
						</tr>
						
						<tr>
							<td colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td width="20%">Flaw Description: (optional)</td>
							<td width="30%">
								<textarea name="flaw_description" id="flaw_description" cols="50" rows="10" >		</textarea>							
							</td>
						</tr>
						<tr>
							<td><input  type="submit" name="report_question" id="report_question" value="Report Question" /></td>
						
							<td><input  type="submit" name="abort_report" id="abort_report" value="Abort" /></td>
							
							<!--<td><input  type="submit" name="pass_question" id="pass_question" value="Pass" /></td> -->
						</tr>
					</table>
				</form>
		
	</div>
	<div id="index_right_sidebar" style="background:white";>

	</div>
	<div id="footer">

	</div>
	
</body>
</html>