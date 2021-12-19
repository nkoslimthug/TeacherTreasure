<?php
include ("../../../config/sungunura.php");
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
	
foreach ($_POST as $key => $value)
	{
		echo "$key = $value<br>";
	}

echo "Source form is ".$_SESSION['source_form']."<br>";
if (isset($_POST['pass_question']))
{
	if ($_SESSION['source_form']=="scores_process")
	{
		$_SESSION['source_form_message']="Source form is ".$_SESSION['source_form']."<br>";
		unset ($_SESSION['source_form']);
		header ("Location:../administrator_home.php");
	}
	if ($_SESSION['source_form']=="mc_all_questions")
	{
		$_SESSION['source_form_message']="Source form is ".$_SESSION['source_form']."<br>";
		unset ($_SESSION['source_form']);
		header ("Location:./mc_all_questions.php");
	}
}
	
if (isset($_POST['abort_report']))
{
	$_SESSION['abort_message']="Flaw Report aborted<br>";
}
		
if (isset($_POST['report_question']))
{
	foreach ($_POST as $key => $value)
	{
		echo "$key = $value<br>";
		$question_string=$key;
		if ($key=='subject_code') 
		{
			$subject_code=(int)$value;
		}
		if ($key=='topic_id')
		{
			$topic_id=(int)$value;
		}
		if ($key=='question_number')
		{
			$question_number=(int)$value;
		}
		if ($key=='flaw_type')
		{
			$flaw_type=trim(strtoupper($value));
		}
		if ($key=='flaw_description')
		{
			$flaw_description=trim(strtoupper($value));
		}
	}
	unset ($_POST['report_question']);
}
$flaw_length=strlen($flaw_type);
echo "Flaw length is $flaw_length<br>";
if ($flaw_length==0)
{
	$_SESSION['flaw_message']="No selection made on <b>flaw type</b><br>";
	echo $_SESSION['flaw_message'];
	header ("Location:../administrator_home.php");
	exit;
}
else
{
	$flaw_query="INSERT INTO tblquestionflaws
				(subject_code,topic_id,question_number,flaw_type,flaw_description)
				VALUES
				($subject_code,$topic_id,$question_number,'$flaw_type','$flaw_description')";
	if (!$flaw_result=mysqli_query($cxn,$flaw_query))
	{
		$_SESSION['flaw_message']="Failed to log flaw:".mysqli_error($cxn)."<br>";
	}
	else
	{
		$_SESSION['flaw_message']="Flaw successfully logged<br>";
	}
	echo $_SESSION['flaw_message']."<br>";
	if ($_SESSION['source_form']=="scores_process")
	{
		unset ($_SESSION['source_form']);
		header ("Location:../administrator_home.php");
	}
	if ($_SESSION['source_form']=="mc_all_questions")
	{
		unset ($_SESSION['source_form']);
		header ("Location:./mc_all_questions.php");
	}
	exit;
}
?>