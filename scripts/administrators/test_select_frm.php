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
	foreach ($_POST AS $key => $value)
	{
		//echo "$key = $value<br>";
	}
//session_start();
//$_SESSION['question_number']=120;
//$_SESSION['question_counter']=120;
//$_SESSION['batch_size']=32;
$question_counter=10;
$test_step=5;
$_SESSION['test_cap']=30;
$_SESSION['story_id']=0;
$_SESSION['image_id']=0;
$_SESSION['story_title']='';
$_SESSION['story_content']='';

//count incomplete tests
	$incomplete_summ_count="SELECT COUNT(*) incomplete_summary
								FROM tblmctestsummary
								WHERE test_owner='".$_SESSION['username']."'
								AND test_status=0";
	//echo $incomplete_summ_count."<br>";
	if (!$incomplete_summ_result=mysqli_query($cxn,$incomplete_summ_count))
	{
		$_SESSION['incomplete_summ_messsage']="Failed to count incomplete tests :".mysqli_error($cxn)."<br>";
		//header ("Location:./administrator_home.php");
	}
	else
	{
		$row=mysqli_fetch_assoc($incomplete_summ_result);
		extract ($row);
		if ($incomplete_summary>0)
		{
			//display incomplete tests
		//	echo $incomplete_summary." incomplete tests exist for user ".$_SESSION['username']."<br>";
			header ("Location:./mc/mc_incomplete_summary.php");    //retrieve incomplete tests
		}
		else
		{
			//proceed to create new test
			$_SESSION['new_test_message']="Click <b>Submit Request</b> to create a new test<br>"; 
			
		}
	}
?>
<!doctype html>
<html>
<head>
	<title>
		Cyber Home
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
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
					<li><a href='test_schedule.php'>Back</a></li>
					<li><a href='administrator_home.php'>Home</a></li>
					<li><a href="<?php echo $root_path;?>/scripts/general/logout.php">Logout</a></li>
     			</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner"> 
		<?php
			
			if (isset($_SESSION['blank_subject_message']))
			{
				echo $_SESSION['blank_subject_message'];
				unset ($_SESSION['blank_subject_message']);
			}
			if (isset($_SESSION['blank_paper_message']))
			{
				echo $_SESSION['blank_paper_message'];
				unset ($_SESSION['blank_paper_message']);
			}
			if (isset($_SESSION['new_test_message']))
			{
				echo $_SESSION['new_test_message'];
				unset ($_SESSION['new_test_message']);
			}
			
		?>
		
		</div>
		<!--<form id="form1" name="form1" method="post" action="testtopic_select_frm.php">  -->
								<form id="form1" name="form1" method="post" action="testtopic_select_frm.php">
								<table width="600" >
									<tr>
										<td>
										<?php
											if(isset($error))
											{
												echo "<b style='color:red'>".$error."</b>";
											}
											if(isset($_SESSION['error']))
											{
												echo "<b style='color:red'>".$_SESSION['error']."</b>";
											}
											 
										?>
										</td>
								</table>
								<table width="600" >
									<tr>
										<td colspan="2"><div align="center"><strong><h3>Test Creation Form</h3></strong></div></td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td width="50%"><strong>Subject:</strong></td>
										<td width="50%"><label for="select"></label>
											<select name="paper_subject" id="paper_subject" required="required" class "select-css">
												<option value="blank">Click dropdown to select Subject</option>
												<?php
													$subject_query="SELECT * 
																	FROM tblsubjects";
													if (!$subject_result=mysqli_query($cxn,$subject_query))
													{
														$message="Failed to retrieve paper\n".mysqli_error($cxn);  
														echo "<script type = 'text/javascript'>alert('".$message."')</script>";					
													}
													else
													{
														$message="Paper retrieval successful<br/>";
														while ($row=mysqli_fetch_assoc($subject_result))
														{
															extract ($row);
															$testPaper = $paper_grade."-".$paper_subject_id."-".$paper_type_id."-".$paper_number;
															echo "<option value='$subject_name'>$subject_name</option>";
														}
													}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td width="177"><strong>Paper Type:</strong></td>
										<td width="178">
											<select name="paper_type" id="paper_type" required="required">
												<option value="blank">Click dropdown to select Paper Type</option>
												<option value="1">Multiple Choice Questions</option>
												<?php //echo '<option value="2">Structured Questions</option>	'?>
											</select>     						
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td width="177"><strong>Question Count:</strong></td>
										<td width="178">
											<select name="max_questions" id="max_questions" required="required">
												<option value="blank">Click dropdown to set Number of Desired Questions</option>
												<?php 
													while($question_counter<=$_SESSION['test_cap'])
													{
														echo "<option value='$question_counter'>$question_counter</option>";
														$question_counter=$question_counter+$test_step;
													}
												?>
											</select>     						
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td><strong>Grade:</strong></td>
										<td>
											<select name="grade" id="grade">
												<option value="blank">Click dropdown to select grade</option>
												<option value="0">0</option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td><strong>Grace Period (days):</strong></td>
										<td>
											<select name="grace_period" id="grace_period">
												<option value="blank">Click dropdown to grace period</option>
												<option value="0">0</option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
												<option value="0">8</option>
												<option value="1">9</option>
												<option value="2">10</option>
												<option value="3">11</option>
												<option value="4">12</option>
												<option value="5">13</option>
												<option value="6">14</option>
												<option value="7">15</option>
											</select>
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