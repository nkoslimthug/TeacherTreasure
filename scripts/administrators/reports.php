<?php
//session_start();
include ("../../config/sungunura.php");
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
	$_SESSION['source_form']="reports.php";
?>
<!doctype html>
<html>
<head>
	<title>
		Pupil Selection
	</title>
	<link rel="stylesheet"
			type="text/css"
			<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
</head>
<body>
	<div id="banner" style="text-align:center";>
	
	</div>
	<div id="slogan_bar" style="background:white;color:purple;font-style:bold;" >
		<i>eCompanion - It's Just What the Teacher Ordered</i>
	</div>
	<div id="index_left_sidebar" style="background:white";>
		<br><br><br>
		<div id="menu">
			<ul>
				<li><a href="./pupil_select.php">Pupil Reports</a></li>
				<li><a href="./success_log.php">Success_Log</a></li>
				<li><a href="./top_scored.php">Top_Scored</a></li>
				<li><a href="../administrators/administrator_home.php" >Back</a></li>
			</ul>
		</div>	
	</div>
	<div id="content" style="color:purple";>
			<div id="message">
					<?php 
						if (isset($_SESSION['person_insert_message']))
						{
							echo $_SESSION['person_insert_message']."<br/>";
							unset ($_SESSION['person_insert_message']);
						}
					?>
				</div>
				
		
	</div>
	<div id="index_right_sidebar" style="background:white";>

	</div>
	<div id="footer">

	</div>
	
</body>
</html>