<?php
$root_path="../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
//Initialise counters
//$_SESSION['question_counter']==0;
unset ($_SESSION['question_type']);
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}


	if (isset($_SESSION['source_form']))
	{
		if ($_SESSION['source_form']=="login.php")
		{
			
			$last_login_query="UPDATE tblmembers
								SET last_login=now()
								WHERE username='".$_SESSION['username']."'";
			if (!$last_login_result=mysqli_query($cxn,$last_login_query))
			{
				$_SESSION['last_login_msg']="Failed to update time<br>";
			}
			else
			{
				$_SESSION['last_login_msg']="Login time updated<br>";
			}
			//echo $_SESSION['last_login_msg'];
		}
	}
	/*foreach ($_SESSION AS $key=>$value)
	{
		echo "$key = $value<br>";
	}*/
?>
<!doctype html>
<html>
<head>
	<title>
		Performance
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
</head>
<body>
	<div id="banner" >
		Top Section
	</div>
	<div id="slogan_bar" >
		<marquee>The Technological High Table</marquee>
	</div>
	<div id="message_bar" >
		<?php if (isset($_SESSION['answer_validate']))
						{
							echo $_SESSION['answer_validate'];
								unset ($_SESSION['answer_validate']);
						}
						?>
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			<ul id="nav">
                	<li><a href="./student_parameters.php">Pupil Reports</a></li>
					<li><a href="./subject_parameters.php">Subject Reports</a></li>
					<li><a href="./topic_parameters.php">Topic Reports</a></li>
					<li><a href="./general_parameters.php">GeneralReports</a></li>
					<li><a href="../../scripts/general/logout.php">Logout</a></li>
					<li><a href="./administrator_home.php">Back</a></li>
			</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner">  
		
		</div>
			<div id="user_banner">
				<?php
					if (isset($_SESSION['welcome_msg']))
					{
						echo $_SESSION['welcome_msg'];
						unset ($_SESSION['welcome_msg']);
					}
					else 
					{
						echo $_SESSION['fullname']."<br>";
					}			
				?>
			</div>
			
	</div>
	<div id="index_right_sidebar" >
		RIGHT INDEXING
	</div>
	<div id="footer" >
		<font="brown">The Citadel of Learning</font>
	</div>
	
</body>
</html>