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

function draw_table($table_heading)
{
	$root_path="../../";
	include($root_path."config/paths.php");
	$user="root";
	$host="localhost";
	$password="";
	$dbname="dbchampions";
	$cxn=mysqli_connect($host,$user,$password,$dbname);
	//Table Heading
	$table_heading="Performance Statistics";
	$frame_width=25;
	echo "<table border='6' width='100%' style='align:center;'>";
		echo "<th colspan='6' width='100%' align='center'>";
			echo $table_heading;
		echo "</th>";
		echo "<tr>";
			echo "<td colspan='6'>&nbsp;</td>";
		echo "</tr>";
		
			echo "<tr><b>";
			echo "<td style='align:center;font:bold;'>Subject Name</td>";
			echo "<td style='align:center;'>Topic</td>";
			echo "<td style='align:center;'>Question Type</td>";
			echo "<td style='align:center;'>Score</td>";
			echo "<td style='align:center;'>Start Date</td>";
			echo "<td style='align:center;'>End Date</td>";
		echo "</tr></b>";
		echo "<tr>";
			echo "<td colspan='6'>&nbsp;</td>";
		echo "</tr>";
	
	//Retrieve Data
	$report_query="SELECT * FROM tblperformance";
	$report_result=mysqli_query($cxn,$report_query);
	while ($row=mysqli_fetch_assoc($report_result))
	{
		extract ($row);
		echo "<tr>";
			echo "<td style='align:center;'>$subject_name</td>";
			echo "<td style='align:center;'>$topic_name</td>";
			echo "<td style='align:center;'>$question_type</td>";
			echo "<td style='align:center;'>$score</td>";
			echo "<td style='align:center;'>$start_date</td>";
			echo "<td style='align:center;'>$end_date</td>";
		echo "</tr>";
	}
	//End Table
	echo "</table><br><br>";
	
}
?>
<!doctype html>
<html>
<head>
	<title>
		My Reports
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
</head>
<body>
	<div id="banner" >
			</div>
	<div id="slogan_bar" >
		<marquee>The Technological High Table</marquee>
	</div>
	<div id="message_bar" >
		
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			<ul>
				<li><a href="./student_home.php">Home</a></li>
			</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner"> </div>
		<?php
			$table_heading="Performance Statistics";
			draw_table($table_heading);
		?>
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>