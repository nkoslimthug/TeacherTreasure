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
	
//echo "The multiple choice status is <b>".$_SESSION['mc_status']."</b><br>";	
$_SESSION['topic_name'] = $_POST['topic_name'];
$_SESSION['paper_description']=$_SESSION['subject_name']." ".$_SESSION['topic_name'];

foreach ($_POST as $field =>$value)
{
	echo "$field = $value<br/>";
}
//Check blank fields
foreach ($_POST as $field =>$value)
{
	echo "$field = $value<br/>";
	if ($value=="blank")
	{
		$_SESSION['blank_paper_subject']="Please specify the desired <b>subject</b> and <b>paper type</b><br>";
		$_SESSION['blank_topic_message']="Please specify the desired <b>topic</b><br>";
		echo $_SESSION['blank_message'];
		header("Location:exercise_select_frm.php");
		exit;
	}
}
//	echo "Batch size is ".$_SESSION['batch_size']."<br>";	
//Fetch TopicID
$topic_query="SELECT topic_id FROM tbltopics WHERE topic_name='".$_SESSION['topic_name']."'";
$topic_result=mysqli_query($cxn,$topic_query);
$row=mysqli_fetch_assoc($topic_result);
extract ($row);
echo "Topic ID is $topic_id<br/>";
$_SESSION['topic_id']=$topic_id;
$_SESSION['pass_counter']=0;
$_SESSION['test_counter']=0;
$_SESSION['incomplete_flag']=0;

//Total questions
$total_questions_query="SELECT COUNT(*) AS total_questions
						FROM tblmcquestions 
						WHERE subject_code=".$_SESSION['subject_code']."
						AND topic_id=".$_SESSION['topic_id']."
						AND lower_grade<=".$_SESSION['grade']."
						AND upper_grade>=".$_SESSION['grade'];
echo $total_questions_query."<br>";
if (!$total_questions_result = mysqli_query($cxn,$total_questions_query))
{
	$_SESSION['total_questions_mesage']="Failed to get total questions :".mysqli_error($cxn)."<br>";
}
else
{
	$row=mysqli_fetch_assoc($total_questions_result);
	extract ($row);
	$_SESSION['total_questions']=$total_questions;
	$_SESSION['total_questions_mesage']="Total questions obtained<br>";
}

if ($_SESSION['topic_name']=="NZWISISO" || $_SESSION['topic_name']=="COMPREHENSION")  //list stories
{
	header ("Location:./story_select.php");
	exit;
}
			
//Determine target form
if ($_SESSION['paper_type']==1)
{
	//$_SESSION['question_counter']=120;
	$_SESSION['question_type']="MC";
	header ("Location:mc/mc_maketest.php");
	exit;
}

if ($_SESSION['paper_type']==2)
{
	//$_SESSION['question_counter']=120;
	$_SESSION['score_counter']=0;
	$_SESSION['question_type']="SQ";
	header ("Location:sq/sq_maketest.php");
	exit;			
}
?>
