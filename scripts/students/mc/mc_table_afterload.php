<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
include ("../../../tmp/table.php");
include ("../../functions/draw_table33.php");
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}

$_SESSION['questions_counter']++;
$_SESSION['questions_count']++;
	
?>
<!doctype html>
<html>
<head>
	<title>
		Multiple Choice Questions
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../../style/college.css" />
</head>
<body>
	<div id="banner" >
		
	</div>
	<div id="slogan_bar" >
		<center>eCompanion - It's Just What the Teacher Ordered</center>
	</div>
	<div id="message_bar" >
		
		<?php
				//include($root_path."config/page_header.php");
			?>
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		
	</div>
	<div id="content" >
		<div id="message_banner">  </div>
		<form id="form1" name="form1" method="post" action="mc_ind_questions.php">
					<table border="0" style="color:#900090;">
						<tr>
						<td colspan ='2'>
						<?php
							echo "<p><h2>".$_SESSION['paper_description']."</h2></p>";
							echo "
								<h3 style='color: purple; font-weight: bolder'>
									".$_SESSION['instruction']."
								</h3>
								<h2 style='color: blue; font-weight: bolder'>
									Question ".$_SESSION['question_counter']."
								</h2>
							";
							$_SESSION['header_path']="../../";
							$_SESSION['images_path']="../../../";						
							include ($_SESSION['header_path']."general/question_header.php");
							echo "<p><b style='color:blue'>";
							//echo "Table based flagged is ".$_SESSION['table_based']."<br>";
							if (isset($_SESSION['table_based']))
							{
								if ($_SESSION['table_based']==1)
								{
									echo "<h3 style='color: purple; font-weight: bolder'>";
									draw_math_table();
									echo "</h3>";
								}
								else 
								{
									echo $_SESSION['question_complete'];
								}
							}
							echo "</b><br/></p>";
							echo "<hr/>";
							echo "<p>";
								echo "<b style='color: red;'><u>Answer Analysis</u></b>";
							echo "</p>";
							if(isset($correct)){
								foreach($correct as $correct){
									echo "<p style='color:blue'>".$correct."</p>";
								}
							}
							if(isset($incorrect)){
								foreach($incorrect as $incorrect){
									echo "<p style='color:blue'>".$incorrect."</p>";
								}
							}
							$_SESSION['table_based']=0;  //restore default value
							//$_SESSION['start_date']=$start_date; //start time for this test
							echo "Logtime before update is ".$_SESSION['start_logtime']."<br>";
							
							/*if ($_SESSION['question_counter']==$_SESSION['batch_size'])			//confirm completed tests
							{
								echo "Question counter now equals batch size<br>";
								$completed_query="UPDATE tblmctestlog SET complete=1,
								start_date='{$_SESSION['start_logtime']}'
								WHERE subject_code=".$_SESSION['subject_code'].
								" AND topic_id=".$_SESSION['topic_id'].
								" AND username='".$_SESSION['username'].
								"' AND start_date='".$_SESSION['start_logtime']."'";
								echo $completed_query."<br>";
								if (!$completed_result=mysqli_query ($cxn,$completed_query))
								{
									$_SESSION['completed_message']="Failed to update questions: ".mysqli_error($cxn)."<br>";
								}
								else
								{
									$_SESSION['completed_message']="Questions confirmed<br>";
								}
								echo $_SESSION['completed_message'];
								echo "Logtime after last question is ".$_SESSION['start_logtime']." <br>";
							}*/
						?>
						</td>
						</tr>
						<hr/>				
						<tr>
							<td>
							<input style="background-color: green; font-weight: bolder;" type="submit" name="btnQuestion" id="btnQuestion" value= "<?php next_button()?>"/></td>
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