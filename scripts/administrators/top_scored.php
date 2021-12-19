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

//set threshold
$threshold=[];
$question_counter=0;
$question_count=0;

	//create success_log array				
$threshold_query="SELECT subject_code,topic_id,question_number,SUM(score_count) AS total_attempts
					FROM  tblsuccesslog
					GROUP BY subject_code,topic_id,question_number
					HAVING SUM(score_count)>1
					ORDER BY SUM(score_count) DESC
					LIMIT 10";
if (!$threshold_result=mysqli_query($cxn,$threshold_query))
{
	$_SESSION['threshold_message']="Failed to get questions".mysqli_error($cxn)."<br>";
}
else
{
	while ($row=mysqli_fetch_assoc($threshold_result))
	{
		extract ($row);
		$threshold[$question_counter][0]=$subject_code;
		$threshold[$question_counter][1]=$topic_id;
		$threshold[$question_counter][2]=$question_number;
		$threshold[$question_counter][3]=$total_attempts;
		$question_count++;
		$question_counter++;
	}
	$_SESSION['threshold_message']="Question logged<br>";
}

$question_counter=0;
//echo "Question count is ".$question_count."<br>";
//echo "Question counter is ".$question_counter."<br>";
//echo "++++++++++++++++++++++++++++++++++++++++++++++++++<br>";
reset ($threshold);
$successes=[];
while ($question_counter<$question_count)
{
	//echo $threshold[$question_counter][0]."  ";
	//echo $threshold[$question_counter][1]."  ";
	//echo $threshold[$question_counter][2]."  ";
	//echo $threshold[$question_counter][3]."  ";
	//echo "<br>Question count is ".$question_count."<br>";
	//echo "Question counter is ".$question_counter."<br>";

	//track score start
	$verdict_query="SELECT verdict,score_count
					FROM tblsuccesslog
					WHERE subject_code=".$threshold[$question_counter][0]."
					AND topic_id=".$threshold[$question_counter][1]."
					AND question_number=".$threshold[$question_counter][2];
	//echo $verdict_query."<br>";
	if (!$verdict_result=mysqli_query($cxn,$verdict_query))
	{
	}
	else
	{
		$verdict_counter=0;
		while ($row=mysqli_fetch_assoc($verdict_result))
		{
			extract ($row);
			//echo $verdict." ".$score_count."<br>";
			if ($verdict_counter==0)
			{
				$verdict_prev=$verdict;
				$score_count_prev=$score_count;
			}
			$verdict_counter++;
		}
		
		if ($verdict_counter==2)
		{
			$success_rate=($verdict_prev*$score_count_prev+$verdict*$score_count)/($score_count+$score_count_prev);
		}
		else
		{
			$success_rate=($verdict*$score_count)/$score_count;
		}
	}
	
	//end score track
	//echo "Success rate =".$success_rate."<br>";
	$successes[$question_counter][0]=$threshold[$question_counter][1];
	$successes[$question_counter][1]=$threshold[$question_counter][2];
	$successes[$question_counter][2]=$threshold[$question_counter][3];
	$successes[$question_counter][3]=number_format($success_rate,2);
	$question_counter++;
}
//echo "++++++++++++++++++++++++++++++++++++++++++++++++++";
//echo "Question count is ".$question_count."<br>";
//echo "Question counter is ".$question_counter."<br>";
//Read successes
//echo "Reading successes<br><br>";
reset ($successes);
$question_counter=0;
while($question_counter<$question_count)
{
	//echo $successes[$question_counter][0]."  ";
	//echo $successes[$question_counter][2]."  ";
	//echo $successes[$question_counter][1]."  ";
	//echo $successes[$question_counter][3]."  <br>";
	$question_counter++;
}
$question_count=$question_counter;
$question_counter=0;

//echo "Question count is ".$question_count."<br>";
//echo "Question counter is ".$question_counter."<br>";
//next sort successes by success_rate	
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
					<li><a href='./administrator_home.php'>Home</a></li>
					<li><a href='./reports.php'>Back</a></li>
			</ul>
		</div>		
	</div>
	<div id="content" >
		<div id="message_banner">  </div>
		<form id="form1" name="form1" method="post" action="./topscore_process.php">
					
						<?php
							if (!$successes)
							{
								$_SESSION['array_msg']="No test results have been logged<br>";
								header ("Location:./administrator_home.php");
								exit;
							}
							else
							{
							reset ($successes);
							$question_counter=0;
							$subject_count=0;
							$subject_counter=0;
							//echo "at table head";
							
							echo "<table border='3' style='width:100%;align:center;'>";
									echo "<tr>";
										echo "<td style='align:center;'><b>Subject Code</td>";
										echo "<td style='align:center;'>Topic ID</td>";
										echo "<td style='align:center;'>Question Number</td>";
										echo "<td style='align:center;'>Success Rate</td>";
									echo "</tr>";
							while ($question_counter<$question_count)
							{	
								$subject_label=$subject_counter+1;
								if ($subject_counter==0)
								{
									
									$subjects[$subject_counter]=$successes[$question_counter][1];
									
									echo "<tr>";
								
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
									echo "</tr>";
								
									if ($successes[$question_counter][1]==$subjects[$subject_counter])
									{
										$subject_counter++;
										$subject_count++;
										continue;
										
									echo "<tr>";
										echo "<td style='align:center;'>".$successes[$question_counter][0]."</td>";
										echo "<td style='align:center;'>".$successes[$question_counter][2]."</td>";
										echo "<td style='align:center;'>".$successes[$question_counter][1]."</td>";
										echo "<td style='align:center;'>".$successes[$question_counter][3]."</td>";
										echo "<td style='align:center;'><input type='submit' name='$record_key' value='View'/></td>";
									echo "</tr>";
									}
								}
								else if ($subject_counter>0)
								{
									if (!in_array($successes[$question_counter][1],$subjects))
									{	
										$subjects[$subject_counter]=$successes[$question_counter][1];
									
									echo "</tr>";
										if ($successes[$question_counter][1]==$subjects[$subject_counter])
										{	
											$subject_counter++;
											$subject_count++;
										}
									}
									$record_key=$successes[$question_counter][0]."+".$successes[$question_counter][1].
									"+".$successes[$question_counter][2];
									echo "<tr>";
										echo "<td style='align:center;'>".$successes[$question_counter][0]."</td>";
										echo "<td style='align:center;'>".$successes[$question_counter][2]."</td>";
										echo "<td style='align:center;'>".$successes[$question_counter][1]."</td>";
										echo "<td style='align:center;'>".$successes[$question_counter][3]."</td>";
										
										echo "<td style='align:center;'><input type='submit' name='$record_key' value='View'/></td>";
									echo "</tr>";
								}
								$question_counter++;
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
