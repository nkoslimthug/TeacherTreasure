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
//session_start();
$_SESSION['question_number']=0;
$_SESSION['question_counter']=0;
//$_SESSION['batch_size']=5;
$_SESSION['story_id']=0;
$_SESSION['image_id']=0;
$_SESSION['story_title']='';
$_SESSION['story_content']='';

$_SESSION['subject_name'] =$_POST['paper_subject']; 
$_SESSION['paper_type'] = $_POST['paper_type'];
//echo "Paper type is ".$_SESSION['paper_type']."<br>";
//echo "Batch size is ".$_SESSION['batch_size']."<br>";
$_SESSION['max_questions']=$_POST['max_questions'];
$_SESSION['chosen_grade']=$_POST['grade'];
$_SESSION['grade']=$_POST['grade'];
foreach ($_POST as $field =>$value)
{
	//echo "$field = $value<br/>";
}
foreach ($_POST as $field =>$value)
{
	//echo "$field = $value<br/>";
	if ($value=="blank")
	{
		if ($field=='paper_subject')	
		{
			$_SESSION['blank_subject_message']="Please specify desired subject</b><br>";
		}
		if ($field=='paper_type')	
		{
			$_SESSION['blank_paper_message']="Please specify desired paper type</b><br>";
		}
		header("Location:exercise_select_frm.php");
		exit;
	}
}
//echo $_SESSION['blank_subject_message'];
//echo $_SESSION['blank_paper_message'];
//determine current date

$current_date_query="SELECT current_date() AS today ";
$current_date_result=mysqli_query($cxn,$current_date_query);
$row=mysqli_fetch_assoc($current_date_result);
extract ($row);
//echo "The date today is ".$today."<br>";



//set due date

$grace_period=$_POST['grace_period'];
$_SESSION['deadline']=date("Y-m-d",strtotime($today.'+ '.$grace_period.' days'));

//echo "Deadline is ".$_SESSION['deadline']."<br>";
//Fetch subject ID
$subject_query="SELECT subject_code FROM tblsubjects WHERE subject_name='".$_SESSION['subject_name']."'";
if (!$subject_result=mysqli_query($cxn,$subject_query))	
{
	$subject_message="Failed to retrieve subject:\n".mysqli_error($cxn)."<br/>";
	echo "<script type = 'text/javascript'>alert('".$subject_message."')</script>";		
}	
else
{
	$row=mysqli_fetch_row($subject_result);
	$_SESSION['subject_code']=$row[0];
	$_SESSION['question_score']=0;
	//echo "Subject ID is ".$_SESSION['subject_code']."<br/>";
}

?>
<!doctype html>
<html>
<head>
	<title>
		Topic Selection
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
			<style>
				
			</style>
</head>
<body>
	<div id="banner" >
		
	</div>
	<div id="slogan_bar" >
		<center><i>eCompanion - It's Just What the Teacher Ordered</i></center>
	</div>
	<div id="message_bar" >
		
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			<ul class="nav">
					<li><a href='administrator_home.php'>Back</a></li>
					<li><a href='administrator_home.php'>Home</a></li>
					<li><a href="<?php echo $root_path;?>/scripts/general/logout.php">Logout</a></li>
     			</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner">
			<?php
				if (isset($_SESSION['blank_topic_message']))
				{
					echo $_SESSION['blank_topic_message'];
					unset ($_SESSION['blank_topic_message']);
				}
				
			?>

		</div>
		<form id="form1" name="form1" method="post" action="test_select_process.php">
								
								<table width="600" >
									<tr>
										<td>
										<?php
											if(isset($error)){
												echo "<b style='color:red'>".$error."</b>";
											}
											if(isset($_SESSION['error'])){
												echo "<b style='color:red'>".$_SESSION['error']."</b>";
											}
											
										?>
										</td>
								</table>
								<table style="width:600;color:#900090;" >
									<tr>
										<td colspan="2" align="center" ><div ><strong><h3>Topic Selection Form</h3></strong></div></td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td width="25%" ><strong>Topic:</strong></td>
										<td width="75%" >
										<select name="topic_name" id="topic_name">
											<option value="blank">Click dropdown to select Topic</option>
											<?php
												if ($_SESSION['paper_type']==1)
												{
													$topic_query="SELECT t.topic_name AS topic_name
													FROM tblmcquestions m, tbltopics t
													WHERE t.topic_id=m.topic_id AND t.subject_code=m.subject_code 
													AND m.subject_code=".$_SESSION['subject_code'].
													" GROUP BY m.subject_code,t.topic_name HAVING COUNT(m.topic_id)>0";
													if ($topic_result=mysqli_query($cxn,$topic_query))
													{
														//$_SESSION['mc_status']="FOUND";
														while ($row=mysqli_fetch_assoc($topic_result))
														{
															extract ($row);
															echo "<option value='$topic_name'>$topic_name</option>";
														}
													}
												}
												if ($_SESSION['paper_type']==2)
												{
													//$_SESSION['mc_status']="NOT_FOUND";
													$topic_query="SELECT t.topic_name,COUNT(s.topic_id) 
													FROM tblstructuredquestions s, tbltopics t
													WHERE t.topic_id=s.topic_id AND t.subject_code=s.subject_code
													AND s.subject_code=".$_SESSION['subject_code'].
													" GROUP BY s.subject_code,t.topic_name 
													HAVING COUNT(s.topic_id)>0";
													if ($topic_result=mysqli_query($cxn,$topic_query))
													{
														//$_SESSION['mc_status']="FOUND";
														while ($row=mysqli_fetch_assoc($topic_result))
														{
															extract ($row);
															echo "<option value='$topic_name'>$topic_name</option>";
														}
													}
												}
											?>
										</td>
									</tr>
								</table>                    
								<p>&nbsp;</p>
								<p>
									<input type="submit" name="btnPaperSubmission" id="btnPaperSubmission" value="Submit Request" />	
								</p>
							</form>
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>