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
foreach($_POST as $key => $value)
{
	//echo "$key = $value<br>";
}

//extract attempted subjects
$record_counter=0;
$record_count=0;
$performance_array="";
if (isset($_POST['pupil_name']))
{
	$_SESSION['pupil_name']=$_POST['pupil_name'];
	unset($_POST['pupil_name']);
	//echo "Pupil name is ".$_SESSION['pupil_name']."<br>";
	$pupil_length=strlen($_SESSION['pupil_name']);
	
	$full_name_query="SELECT forename,lastname FROM tblmembers 
						WHERE username='".$_SESSION['pupil_name']."'";
	//echo $full_name_query."<br>";
	if ($full_name_result=mysqli_query($cxn,$full_name_query))	
	{
		$row=mysqli_fetch_assoc($full_name_result);
		extract ($row);
		$pupil_fullname="$forename $lastname";
		//echo $pupil_fullname."<br>";
	}
	else 
	{
		//echo "Failed to retrieve full name ".mysqli_error($cxn);
	}
}
//echo $pupil_fullname." is the full name<br>";
$attempted_query="SELECT *
					FROM etutorsrv.tblperformance
					WHERE username='".$_SESSION['pupil_name']."'
					ORDER BY subject_name,start_time";
//echo $attempted_query."<br>";
if (!$attempted_result=mysqli_query($cxn,$attempted_query))
{
	$_SESSION['attempted_message']="No recorded tests exist for user <b>".$_SESSION['pupil_fullname']."</b><br>";
	header ("Location:./administrator_home.php");
	exit;
}
else
{
	while($row=mysqli_fetch_assoc($attempted_result))
	{
		extract ($row);
		$performance_array[$record_counter][0]=$username;
		$performance_array[$record_counter][1]=$subject_name;
		$performance_array[$record_counter][2]=$topic_name;
		$performance_array[$record_counter][3]=$test_type;
		$performance_array[$record_counter][4]=$score;
		$performance_array[$record_counter][5]=$start_time;
		$performance_array[$record_counter][6]=$end_time;
		$performance_array[$record_counter][7]=$origin;
		$record_counter++;
		$record_count++;
	}
}
//echo "Array loaded<br>";

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
								if ($pupil_length==0)
								{
									$_SESSION['array_msg']="No pupil selected. Please specify the desired pupil.<br>";
									echo $_SESSION['array_msg'];
								}
								else if ($pupil_length>0)
								{
									$_SESSION['array_msg']=$pupil_fullname." has no recorded tests<br>";
									echo $_SESSION['array_msg'];
								}
								header ("Location:administrator_home.php");
								exit;
							}
							else
							{
								reset ($performance_array);
								$record_counter=0;
								$subject_count=0;
								$subject_counter=0;
								echo "<table border='3' style='width:100%;align:center;'>";
										echo "<tr>";
											echo "<td style='align:center;'><b>Subject & Topic</td>";
											echo "<td style='align:center;'>Origin</td>";
											echo "<td style='align:center;'>Type</td>";
											echo "<td style='align:center;'>Score</td>";
											echo "<td style='align:center;'>Start Time</td>";
											echo "<td style='align:center;'>End Time</td></b>";
											echo "<td style='align:center;'>Details</td></b>";
										echo "</tr>";
								while ($record_counter<$record_count)
								{	
									$subject_label=$subject_counter+1;
									if ($subject_counter==0)
									{
										
										$subjects[$subject_counter]=$performance_array[$record_counter][1];
										echo "<tr>";
											echo "<td style='align:center;'><b>".$performance_array[$record_counter][1]."</b></td>";
											echo "<td style='align:center;'>&nbsp;</td>";
											echo "<td style='align:center;'>&nbsp;</td>";
											echo "<td style='align:center;'>&nbsp;</td>";
											echo "<td style='align:center;'>&nbsp;</td>";
											echo "<td style='align:center;'>&nbsp;</td>";
											echo "<td style='align:center;font:0000FF#;'><b>".$forename." ".$lastname."<b></td>";
										echo "</tr>";
									
										if ($performance_array[$record_counter][1]==$subjects[$subject_counter])
										{
											$subject_counter++;
											$subject_count++;
											continue;
											$record_key=$performance_array[$record_counter][0]."+".$performance_array[$record_counter][1].
										"+".$performance_array[$record_counter][2]."+".$performance_array[$record_counter][5];
										echo "<tr>";
											echo "<td style='align:center;'>".$performance_array[$record_counter][2]."</td>";
											echo "<td style='align:center;'>".$performance_array[$record_counter][7]."</td>";
											echo "<td style='align:center;'>".$performance_array[$record_counter][3]."</td>";
											echo "<td style='align:center;'>".$performance_array[$record_counter][4]."</td>";
											echo "<td style='align:center;'>".$performance_array[$record_counter][5]."</td>";
											echo "<td style='align:center;'>".$performance_array[$record_counter][6]."</td>";
											echo "<td style='align:center;'><input type='submit' name='$record_key' value='DetailedReport'/></td>";
										echo "</tr>";
										}
									}
									else if ($subject_counter>0)
									{
										if (!in_array($performance_array[$record_counter][1],$subjects))
										{	
											$subjects[$subject_counter]=$performance_array[$record_counter][1];
											//echo "Greater records<br>";
											//echo "Subject $subject_label is <b>".$subjects[$subject_counter]."</b><br>";
											echo "<tr>";
												echo "<td style='align:center;'><b>".$performance_array[$record_counter][1]."</b></td>";
												echo "<td style='align:center;'>&nbsp;</td>";
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
											}
										}
										$record_key=$performance_array[$record_counter][0]."+".$performance_array[$record_counter][1].
										"+".$performance_array[$record_counter][2]."+".$performance_array[$record_counter][5];
										echo "<tr>";
											echo "<td style='align:center;'>".$performance_array[$record_counter][2]."</td>";
											echo "<td style='align:center;'>".$performance_array[$record_counter][7]."</td>";
											echo "<td style='align:center;'>".$performance_array[$record_counter][3]."</td>";
											echo "<td style='align:center;'>".$performance_array[$record_counter][4]."</td>";
											echo "<td style='align:center;'>".$performance_array[$record_counter][5]."</td>";
											echo "<td style='align:center;'>".$performance_array[$record_counter][6]."</td>";
											echo "<td style='align:center;'><input type='submit' name='$record_key' value='DetailedReport'/></td>";
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