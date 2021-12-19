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
?>
<!doctype html>
<html>
<head>
	<title>
		My Reports
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../style/new_colours.css" />
</head>
<body>
	<div id="banner" >
		
	</div>
	<div id="slogan_bar" >
		<center>eCompanion - It's Just What the Teacher Ordered</center>
	</div>
	<div id="message_bar" >
		
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu" >
			<ul>
				<li><a href="./scores.php" target="_blank">MyScores</a></li>
				<li><a href="../../scripts/students/student_home.php">Home</a></li>
			</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner">  </div>
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>