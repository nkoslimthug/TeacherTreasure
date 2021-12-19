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

//test parameters in database
$test_count=0;
$test_counter=0;
$tests_array=[];             //stores shcheduled tests that have been written by this student

$test_query="SELECT * 
			FROM tblscheduledwritten";
if (!$test_result=mysqli_query($cxn,$test_query))
{
	$_SESSION['test_message']="Failed to extract parameters from DB :".mysqli_error($cxn)."<br>";
	//echo $_SESSION['test_message'];
}
else
{
	while ($row=mysqli_fetch_assoc($test_result))
	{
		extract ($row);
		$test_parametersdb=$test_owner."_".$subject_code."_".$grade."_".$subject_counter."_".$student;
		$tests_array[$test_counter]=$test_parametersdb;
		//echo $tests_array[$test_counter]."<br>";
		$test_count++;
		$test_counter++;
	}
}
//echo "Array elements present are ".count(tests_array)."<br>";
//extract attempted subjects
$record_counter=0;
$record_count=0;
$scheduled_array=[];     //info on scheduled tests that are relevant to current student


//Determine today's date
$today_query="SELECT current_date() AS today";
$today_result=mysqli_query($cxn,$today_query);
$row=mysqli_fetch_assoc($today_result);
extract ($row);

$scheduled_query="SELECT m.test_owner,m.subject_code,m.subject_counter,m.posted_date,m.test_deadline,s.subject_name
					FROM tblmctestsummary m,tblsubjects s
					WHERE s.subject_code=m.subject_code
					AND m.grade=".$_SESSION['student_grade']."
					AND m.test_deadline>='".$today."'
					ORDER BY m.test_owner,m.subject_code,m.posted_date";
//echo $scheduled_query."<br>";
if (!$scheduled_result=mysqli_query($cxn,$scheduled_query))
{
	$_SESSION['scheduled_message']="No recorded tests exist for user <b>".$_SESSION['fullname']."</b><br>";
	//header ("Location:./student_home.php");
	//exit;
}
else
{
	while($row=mysqli_fetch_assoc($scheduled_result))
	{
		extract ($row);
		$scheduled_array[$record_counter][0]=$test_owner;
		$scheduled_array[$record_counter][1]=$subject_code;
		$scheduled_array[$record_counter][2]=$subject_counter;
		$scheduled_array[$record_counter][3]=$posted_date;
		$scheduled_array[$record_counter][4]=$subject_name;
		$scheduled_array[$record_counter][5]=$test_deadline;
		$record_counter++;
		$record_count++;
	}
}
//echo "Array loaded<br>";
$_SESSION['question_counter']=0;
$_SESSION['question_score']=0;
$_SESSION['origin']='SCH';
//test in tblmctestsummary for this user and NOT expired
//test NOT in tblscheduledwritten by this student
?>
<!doctype html>
<html>
<head>
	<title>
		Scores
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
			<div id="menu">
			<ul class="nav">
					<br><br><br><br>
					<li><a href='./student_home.php'>Home</a></li>
					<li><a href='./student_home.php'>Back</a></li>
			</ul>
		</div>		
	</div>
	<div id="content" >
		<div id="message_banner">  </div>
		<form id="form1" name="form1" method="post" action="./mc/mc_scheduled_test.php">
					
						<?php
							
							//database array
							if (!$tests_array)
							{
								$_SESSION['test_message']="No scheduled tests have been logged for ".$_SESSION['fullname']."<br>";
								header ("Location:./student_home.php");
								exit;
							}
							else
							{
								reset ($tests_array);
								$test_counter=0;
							}
						
							if (!$scheduled_array)
							{
								$_SESSION['array_msg']="There are no test results logged for ".$_SESSION['fullname']."<br>";
								header ("Location:./student_home.php");
								exit;
							}
							else
							{
							reset ($scheduled_array);
							$record_counter=0;
							$subject_count=0;
							$subject_counter=0;
							//echo "at table head";
							
							echo "<table border='3' style='width:100%;align:center;'>";
									echo "<tr>";
										echo "<td style='align:center;'><b></td>";
										echo "<td style='align:center;'></td>";
										echo "<td style='align:center;'></td>";
										echo "<td style='align:center;'></td>";
										echo "<td style='align:center;'></td></b>";
										echo "<td style='align:center;'></td></b>";
									echo "</tr>";
									echo "<tr>";
										echo "<td style='align:center;'><b>Subject</td>";
										echo "<td style='align:center;'>Test Owner</td>";
										echo "<td style='align:center;'>Test Counter</td>";
										echo "<td style='align:center;'>Posted</td>";
										echo "<td style='align:center;'>Deadline</td></b>";
										echo "<td style='align:center;'>Details</td></b>";
									echo "</tr>";
							while ($record_counter<$record_count)
							{	
								$subject_label=$subject_counter+1;
								if ($subject_counter==0)
								{
									//test parameters (in memory)
									//echo "Test owner is ".$scheduled_array[$record_counter][0]."<br>";
									//echo "Current subject is ".$scheduled_array[$record_counter][1]."<br>";
									//echo "Current grade is ".$_SESSION['student_grade']."<br>";
									//echo "Subject counter is ".$scheduled_array[$record_counter][2]."<br>";
									//echo "Current student is ".$_SESSION['username']."<br>";
									$combined_parameters=$scheduled_array[$record_counter][0]."_".$scheduled_array[$record_counter][1]."_".$_SESSION['student_grade']."_".$scheduled_array[$record_counter][2]."_".$_SESSION['username'];
									//echo $combined_parameters."<br>";
									$subjects[$subject_counter]=$scheduled_array[$record_counter][1];
									//echo "Zero record<br>";
									//echo "Subject $subject_label is <b>".$subjects[$subject_counter]."</b><br>";
									//start subject table
									
									//end subject table
									//Start Headings table
									//echo "<table border='2' width='80%' style='align:center;'>";
									
									echo "<tr>";
										echo "<td style='align:center;'><b>".$scheduled_array[$record_counter][4]."</b></td>";
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
									echo "</tr>";
								
									if ($scheduled_array[$record_counter][1]==$subjects[$subject_counter])
									{
										$subject_counter++;
										$subject_count++;
										continue;
										//$record_key=$scheduled_array[$record_counter][0]."+".$scheduled_array[$record_counter][1]."+".$_SESSION['student_grade'].
									//"+".$scheduled_array[$record_counter][2];
										$record_key=$scheduled_array[$record_counter][0]."+".$scheduled_array[$record_counter][1]."+".$scheduled_array[$record_counter][3].
										"+".$scheduled_array[$record_counter][3];
										if (!in_array($combined_parameters,$tests_array))
										{
											echo "<tr>";
											echo "<td style='align:center;'></td>";
												echo "<td style='align:center;'>".$scheduled_array[$record_counter][0]."</td>";
												echo "<td style='align:center;'>".$scheduled_array[$record_counter][2]."</td>";
												echo "<td style='align:center;'>".$scheduled_array[$record_counter][3]."</td>";
												echo "<td style='align:center;'>".$scheduled_array[$record_counter][5]."</td>";
												echo "<td style='align:center;'><input type='submit' name='$record_key' value='Open Test'/></td>";
											echo "</tr>";
										}
									}
								}
								else if ($subject_counter>0)
								{
									//test parameters (in memory)
									//echo "Test owner is ".$scheduled_array[$record_counter][0]."<br>";
									//echo "Current subject is ".$scheduled_array[$record_counter][1]."<br>";
									//echo "Current grade is ".$_SESSION['student_grade']."<br>";
									//echo "Subject counter is ".$scheduled_array[$record_counter][2]."<br>";
									//echo "Current student is ".$_SESSION['username']."<br>";
									$combined_parameters=$scheduled_array[$record_counter][0]."_".$scheduled_array[$record_counter][1]."_".$_SESSION['student_grade']."_".$scheduled_array[$record_counter][2]."_".$_SESSION['username'];
									//echo $combined_parameters."<br>";
									if (!in_array($scheduled_array[$record_counter][1],$subjects))
									{	
										$subjects[$subject_counter]=$scheduled_array[$record_counter][1];
										//echo "Greater records<br>";
										//echo "Subject $subject_label is <b>".$subjects[$subject_counter]."</b><br>";
										echo "<tr>";
										echo "<td style='align:center;'><b>".$scheduled_array[$record_counter][4]."</b></td>";
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
									echo "</tr>";
										if ($scheduled_array[$record_counter][1]==$subjects[$subject_counter])
										{	
											$subject_counter++;
											$subject_count++;
										}
									}
									//$record_key=$scheduled_array[$record_counter][0]."+".$scheduled_array[$record_counter][1]."+".$_SESSION['student_grade'].
									//"+".$scheduled_array[$record_counter][2];
									$record_key=$scheduled_array[$record_counter][0]."+".$scheduled_array[$record_counter][1]."+".$scheduled_array[$record_counter][2].
									"+".$scheduled_array[$record_counter][3];
									if (!in_array($combined_parameters,$tests_array))
									{
										echo "<tr>";
										echo "<td style='align:center;'></td>";
											echo "<td style='align:center;'>".$scheduled_array[$record_counter][0]."</td>";
											echo "<td style='align:center;'>".$scheduled_array[$record_counter][2]."</td>";
											echo "<td style='align:center;'>".$scheduled_array[$record_counter][3]."</td>";
											echo "<td style='align:center;'>".$scheduled_array[$record_counter][5]."</td>";
											echo "<td style='align:center;'><input type='submit' name='$record_key' value='Open Test'/></td>";
										echo "</tr>";
									}
								}
								$record_counter++;
							}
							echo "</table><br>";
						}
						?>
						
				</form>
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>