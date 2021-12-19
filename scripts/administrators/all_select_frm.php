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
//$_SESSION['question_number']=120;
//$_SESSION['question_counter']=120;
//$_SESSION['batch_size']=32;
$_SESSION['story_id']=0;
$_SESSION['image_id']=0;
$_SESSION['story_title']='';
$_SESSION['story_content']='';
//$minimum_questions=1;
//echo "Batch size is ".$_SESSION['batch_size']."<br>";
?>
<!doctype html>
<html>
<head>
	<title>
		Subject Selection
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
					<li><a href='content_manage.php'>Back</a></li>
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
			
		?>
		
		</div>
		<form id="form1" name="form1" method="post" action="alltopic_select_frm.php">
								
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
										<td colspan="2"><div align="center"><strong><h3>Subject Selection Form</h3></strong></div></td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td width="25%"><strong>Subject:</strong></td>
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
										<td width="177"><strong>Grade:</strong></td>
										<td width="178">
											<select name="grade" id="grade" >
												<option value="blank">Click dropdown to select Grade</option>
												<option value="0">0</option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
												<?php //echo '<option value="2">Structured Questions</option>	'?>
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