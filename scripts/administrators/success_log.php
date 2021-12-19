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

//extract attempted subjects
$record_counter=0;
$record_count=0;
$performance_array="";
$attempted_query="SELECT subject_code,topic_id,question_number,verdict,score_count
				  FROM tblsuccesslog
				  GROUP BY subject_code,topic_id,question_number,verdict
				  ORDER BY score_count,subject_code,topic_id,question_number,verdict";
/*$attempted_query="SELECT subject_code,topic_id,question_number,verdict,score_count
				  FROM tblsuccesslog
				  GROUP BY subject_code,topic_id,question_number,verdict
				  ORDER BY score_count,subject_code,topic_id,question_number,verdict";	*/			  
//echo $attempted_query."<br>";
if (!$attempted_result=mysqli_query($cxn,$attempted_query))
{
	$_SESSION['attempted_message']="No recorded tests exist</b><br>";
	header ("Location:./administrator_home.php");
	exit;
}
else
{
	while($row=mysqli_fetch_assoc($attempted_result))
	{
		extract ($row);
		$performance_array[$record_counter][0]=$subject_code;
		$performance_array[$record_counter][1]=$topic_id;
		$performance_array[$record_counter][2]=$question_number;
		$performance_array[$record_counter][3]=$verdict;
		$performance_array[$record_counter][4]=$score_count;
		if ($record_counter>=1)
		{
			$previous_counter=$record_counter-1;
			if ($performance_array[$record_counter][0]==$performance_array[$previous_counter][0]&&
				$performance_array[$record_counter][1]==$performance_array[$previous_counter][1]&&
				$performance_array[$record_counter][2]==$performance_array[$previous_counter][2])		//both verdicts
				{
					//echo "matching zero-one<br>";
					$success_rate=$performance_array[$record_counter][4]/($performance_array[$record_counter][4]+$performance_array[$previous_counter][4]);
					//echo "Success rate is for question ".$subject_code.$topic_id.$question_number." is ".number_format($success_rate,2)."<br>";
					//echo "===========================================<br>";
					//sort multidimentional array by specific column
				}
				else if ($score_count>1) //similar verdict
				{
					$success_rate=($performance_array[$record_counter][3]*$performance_array[$record_counter][4])/$performance_array[$record_counter][4];
					//echo "Success rate for question ".$subject_code.$topic_id.$question_number." is ".number_format($success_rate,2)."<br>";
				}
		}
		$record_counter++;
		$record_count++;
	}
}

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
					<li><a href='./administrator_home.php'>Back</a></li>
			</ul>
		</div>		
	</div>
	<div id="content" >
		<div id="message_banner">  </div>
		<form id="form1" name="form1" method="post" action="./mc/scores_process.php">
					
						<?php
							if (!$performance_array)
							{
								$_SESSION['array_msg']="No test results have been logged<br>";
								header ("Location:./administrator_home.php");
								exit;
							}
							else
							{
							reset ($performance_array);
							$record_counter=0;
							$subject_count=0;
							$subject_counter=0;
							//echo "at table head";
							
							echo "<table border='3' style='width:100%;align:center;'>";
									echo "<tr>";
										echo "<td style='align:center;'><b>Subject Code</td>";
										echo "<td style='align:center;'>Topic ID</td>";
										echo "<td style='align:center;'>Question Number</td>";
										echo "<td style='align:center;'>Verdict</td>";
										echo "<td style='align:center;'>Score Count</td></b>";
									echo "</tr>";
							while ($record_counter<$record_count)
							{	
								$subject_label=$subject_counter+1;
								if ($subject_counter==0)
								{
									
									$subjects[$subject_counter]=$performance_array[$record_counter][1];
									
									echo "<tr>";
								
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
										echo "<td style='align:center;'>&nbsp;</td>";
									echo "</tr>";
								
									if ($performance_array[$record_counter][1]==$subjects[$subject_counter])
									{
										$subject_counter++;
										$subject_count++;
										continue;
										
									echo "<tr>";
										echo "<td style='align:center;'>".$performance_array[$record_counter][0]."</td>";
										echo "<td style='align:center;'>".$performance_array[$record_counter][1]."</td>";
										echo "<td style='align:center;'>".$performance_array[$record_counter][2]."</td>";
										echo "<td style='align:center;'>".$performance_array[$record_counter][3]."</td>";
										echo "<td style='align:center;'>".$performance_array[$record_counter][4]."</td>";
										//echo "<td style='align:center;'><input type='submit' name='$record_key' value=''/></td>";
									echo "</tr>";
									}
								}
								else if ($subject_counter>0)
								{
									if (!in_array($performance_array[$record_counter][1],$subjects))
									{	
										$subjects[$subject_counter]=$performance_array[$record_counter][1];
									
									echo "</tr>";
										if ($performance_array[$record_counter][1]==$subjects[$subject_counter])
										{	
											$subject_counter++;
											$subject_count++;
										}
									}
									$record_key=$performance_array[$record_counter][0]."+".$performance_array[$record_counter][1].
									"+".$performance_array[$record_counter][2];
									echo "<tr>";
										echo "<td style='align:center;'>".$performance_array[$record_counter][0]."</td>";
										echo "<td style='align:center;'>".$performance_array[$record_counter][1]."</td>";
										echo "<td style='align:center;'>".$performance_array[$record_counter][2]."</td>";
										echo "<td style='align:center;'>".$performance_array[$record_counter][3]."</td>";
										echo "<td style='align:center;'>".$performance_array[$record_counter][4]."</td>";
										//echo "<td style='align:center;'><input type='submit' name='$record_key' value=''/></td>";
									echo "</tr>";
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