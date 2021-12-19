<?php
$root_path="../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
//Initialise counters
//$_SESSION['question_counter']==0;
unset ($_SESSION['question_type']);
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}

	if (isset($_SESSION['source_form']))
	{
		if ($_SESSION['source_form']=="login.php")
		{
			
			$last_login_query="UPDATE tblmembers
								SET last_login=now()
								WHERE username='".$_SESSION['username']."'";
			if (!$last_login_result=mysqli_query($cxn,$last_login_query))
			{
				$_SESSION['last_login_msg']="Failed to update time<br>";
			}
			else
			{
				$_SESSION['last_login_msg']="Login time updated<br>";
			}
			//echo $_SESSION['last_login_msg'];
		}
	}
	/*foreach ($_SESSION AS $key=>$value)
	{
		echo "$key = $value<br>";
	}*/
?>
<!doctype html>
<html>
<head>
	<title>
		Student Home
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
</head>
<body>
	<div id="banner" >
		Top Section
	</div>
	<div id="slogan_bar" >
		<marquee>The Technological High Table</marquee>
	</div>
	<div id="message_bar" >
		<?php if (isset($_SESSION['answer_validate']))
						{
							echo $_SESSION['answer_validate'];
								unset ($_SESSION['answer_validate']);
						}
						?>
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			<ul id="nav">
                	<li><a href="./reports/student.php">Pupil Reports</a></li>
					<li><a href="./reports/subject.php">Subject Reports</a></li>
					<li><a href="./reports/topic.php">Topic Reports</a></li>
					<li><a href="./reports/general.php">GeneralReports</a></li>
					<li><a href="../../scripts/general/logout.php">Logout</a></li>
					<li><a href="./administrator_home.php">Back</a></li>
			</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner">  
		
		</div>
			<div id="user_banner">
				<?php
					if (isset($_SESSION['welcome_msg']))
					{
						echo $_SESSION['welcome_msg'];
						unset ($_SESSION['welcome_msg']);
					}
					else 
					{
						echo $_SESSION['fullname']."<br>";
					}			
				?>
			</div>
			<form id="form1" name="form1" method="post" action="topic_select_frm.php">
								
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
								<table width="600" >
									<tr>
										<td colspan="2"><div align="center"><strong><h3>Pupil Performance Form</h3></strong></div></td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td width="25%"><strong>Pupil:</strong></td>
										<td width="75%"><label for="select"></label>
											<select name="paper_subject" id="paper_subject" required="required" class "select-css">
												<option value="blank">Click dropdown to select Subject</option>
												<?php
													$subject_query="SELECT * FROM tblsubjects";
													if (!$subject_result=mysqli_query($cxn,$subject_query)){
														$message="Failed to retrieve paper\n".mysqli_error($cxn);  
														echo "<script type = 'text/javascript'>alert('".$message."')</script>";					
													}
													else{
														$message="Paper retrieval successful<br/>";
														while ($row=mysqli_fetch_assoc($subject_result)){
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
										<td width="177"><strong>Start Date:</strong></td>
										<td width="178">
											<select name="grade" id="grade" required="required">
												<option value="blank" selected="selected">Click dropdown to select Grade</option> 
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
										<td width="177"><strong>End Date:</strong></td>
										<td width="178">
											<select name="grade" id="grade" required="required">
												<option value="blank" selected="selected">Click dropdown to select Grade</option> 
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
									
									
								</table>                    
								<p>&nbsp;</p>
								<p>
									<input type="submit" name="btnPaperSubmission" id="btnPaperSubmission" value="Submit Request" />	
								</p>
							</form>
	</div>
	<div id="index_right_sidebar" >
		RIGHT INDEXING
	</div>
	<div id="footer" >
		<font="brown">The Citadel of Learning</font>
	</div>
	
</body>
</html>