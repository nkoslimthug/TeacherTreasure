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
$attempted_query="SELECT * FROM tblperformance 
					WHERE username='".$_SESSION['username']."' 
					AND subject_name!=''
					ORDER BY subject_name,start_date";
//echo $attempted_query."<br>";
if (!$attempted_result=mysqli_query($cxn,$attempted_query))
{
	$_SESSION['attempted_message']="No recorded tests exist for user <b>".$_SESSION['fullname']."</b><br>";
	header ("Location:./student_home.php");
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
		$performance_array[$record_counter][3]=$question_type;
		$performance_array[$record_counter][4]=$score;
		$performance_array[$record_counter][5]=$start_date;
		$performance_array[$record_counter][6]=$end_date;
		$record_counter++;
		$record_count++;
	}
}
//echo "Array loaded<br>";
//echo $_SESSION['fullname']." made ".$record_count." attempts";
reset ($performance_array);
$record_counter=0;
$subject_count=0;
$subject_counter=0;

while ($record_counter<$record_count)
{	
	$subject_label=$subject_counter+1;
	if ($subject_counter==0)
	{
		
		$subjects[$subject_counter]=$performance_array[$record_counter][1];
		//echo "Zero record<br>";
		//echo "Subject $subject_label is <b>".$subjects[$subject_counter]."</b><br>";
		//start subject table
		echo "<table border='2' width='80%' style='align:center;'>";
		echo "<tr>";
			echo "<td style='align:center;'><b>Subject & Topic</td>";
			echo "<td style='align:center;'>Type</td>";
			echo "<td style='align:center;'>Score</td>";
			echo "<td style='align:center;'>Start_Date</td>";
			echo "<td style='align:center;'>End Date</td><br></b>";
			echo "<td style='align:center;'>Details</td><br></b>";
		echo "</tr>";
		//end subject table
		//Start Headings table
		//echo "<table border='2' width='80%' style='align:center;'>";
		echo "<tr>";
	
			echo "<td style='align:center;'><b>".$performance_array[$record_counter][1]."</b></td>";
			echo "<td style='align:center;'>&nbsp;</td>";
			echo "<td style='align:center;'>&nbsp;</td>";
			echo "<td style='align:center;'>&nbsp;</td>";
			echo "<td style='align:center;'>&nbsp;</td>";
			echo "<td style='align:center;'>&nbsp;</td><br>";
		echo "</tr>";
	
		if ($performance_array[$record_counter][1]==$subjects[$subject_counter])
		{
			$subject_counter++;
			$subject_count++;
			continue;
			$record_key=$performance_array[$record_counter][0]."_"$performance_array[$record_counter][1].
		"_".$performance_array[$record_counter][2]."_".$performance_array[$record_counter][5];
		echo "<tr>";
		echo "<td style='align:center;'>".$performance_array[$record_counter][2]."</td>";
			echo "<td style='align:center;'>".$performance_array[$record_counter][3]."</td>";
			echo "<td style='align:center;'>".$performance_array[$record_counter][4]."</td>";
			echo "<td style='align:center;'>".$performance_array[$record_counter][5]."</td>";
			echo "<td style='align:center;'>".$performance_array[$record_counter][6]."</td>";
			echo "<td style='align:center;'>".$performance_array[$record_counter][7]."</td><br>";
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
			echo "<td style='align:center;'>&nbsp;</td><br>";
		echo "</tr>";
			if ($performance_array[$record_counter][1]==$subjects[$subject_counter])
			{	
				$subject_counter++;
				$subject_count++;
			}
		}
		$record_key=$performance_array[$record_counter][0]."_"$performance_array[$record_counter][1].
		"_".$performance_array[$record_counter][2]."_".$performance_array[$record_counter][5];
		echo "<tr>";
		echo "<td style='align:center;'>".$performance_array[$record_counter][2]."</td>";
			echo "<td style='align:center;'>".$performance_array[$record_counter][3]."</td>";
			echo "<td style='align:center;'>".$performance_array[$record_counter][4]."</td>";
			echo "<td style='align:center;'>".$performance_array[$record_counter][5]."</td>";
			echo "<td style='align:center;'>".$performance_array[$record_counter][6]."</td>";
			echo "<td style='align:center;'><input type='submit' name='$record_key' /></td><br>";
		echo "</tr>";
	}
	$record_counter++;
}
echo "</table><br>";
//echo $subject_count." DISTINCT subjects have been attempted<br>";

?>