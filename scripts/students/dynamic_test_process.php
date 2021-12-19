<?php 
$root_path="../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
	if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
	foreach ($_POST as $field =>$value)
	{
		echo "$field = $value<br/>";
		if ($value=="blank")
		{
			if ($field=="subject_name")
			{
				$_SESSION['blank_subject_message']="Please specify the desired <b>subject</b><br>";
			}
			if ($field="paper_type")
			{
				$_SESSION['blank_paper_message']="Please specify the desired <b>paper type</b><br>";
			}
			echo $_SESSION['blank_paper_subject'];
			echo $_SESSION['blank_topic_message'];
			header("Location:dynamic_test.php");
			exit;
		}
	}
	
	$_SESSION['subject_name'] =$_POST['subject_name']; 
	$_SESSION['grade'] = $_POST['grade'];
	$_SESSION['story_id']=0;
	$_SESSION['image_id']=0;
	$_SESSION['story_title']='';
	$_SESSION['story_content']='';
	
	if ($_POST['paper_type']==1)
	{
		$_SESSION['paper_type']="Multiple Choice Paper";
		$_SESSION['question_type']="MC";
	}
	else if ($_POST['paper_type']==2)
	{
		$_SESSION['paper_type']="Structured Paper";
		$_SESSION['question_type']="SQ";
	}
	echo "Question type is ".$_SESSION['question_type']."<br>";
	$_SESSION['paper_description']="GRADE"." ".$_SESSION['student_grade']." ".$_SESSION['subject_name']." ".$_SESSION['paper_type'];
	echo $_SESSION['paper_description']."<br>";
	foreach ($_POST as $field =>$value)
	{
		echo "$field = $value<br/>";
	}
	
//Fetch subject ID
$subject_query="SELECT subject_code FROM tblsubjects WHERE subject_name='".$_SESSION['subject_name']."'";
if (!$subject_result=mysqli_query($cxn,$subject_query))	{
	$subject_message="Failed to retrieve subject:\n".mysqli_error($cxn)."<br/>";
	echo "<script type = 'text/javascript'>alert('".$subject_message."')</script>";		
}	
else{
	$row=mysqli_fetch_row($subject_result);
	$_SESSION['subject_code']=$row[0];
	$_SESSION['question_score']=0;
	echo "Subject ID is ".$_SESSION['subject_code']."<br/>";
}
			
echo "We have reached this far<br>";		
//Determine target form
if ($_SESSION['question_type']=='MC'){
	echo "We also reached one<br>";
	$_SESSION['question_counter']=0;
	header ("Location:mc/mc_dyn_questions.php");
	exit;
}
if ($_SESSION['question_type']=='SQ'){
	echo "We also reached two<br>";
	$_SESSION['question_counter']=0;
	$_SESSION['score_counter']=0;
	header ("Location:sq/sq_dyn_questions.php");
	exit;			
}

?>
