<?php
session_start();
?>
<!doctype html>
<html>
<head>
	<title>
		Teacher Treasure
	</title>
			<link rel="stylesheet"	type="text/css"	href="./style/college.css" />
			<style type="text/css">
			div#first{
				background-image: url('/images/logos/chosen.png');
			}
			</style>	
</head>
<body>
	<div id="banner" >
		
	</div>
		<div id="slogan_bar" >
		<center><i>eCompanion - It's Just What the Teacher Ordered</i> </center>
	</div>
	<div id="message_bar" >
	
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			<ul>
				<li><a href="./scripts/general/login.php">Login</a></li>
				<li><a href="./scripts/general/registration.php">Register</a></li>
				<li><a href="./scripts/general/our_services.php">Our Services</a></li>
				<li><a href="./scripts/general/about_us.php">About Us</a></li>               
			</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner"> 
			<?php
				if (isset($_SESSION['member_message']))
				{
					echo $_SESSION['member_message'];
					unset ($_SESSION['member_message']);
				}
				if(isset($_SESSION['licence_status_message']))
				{
					echo $_SESSION['licence_status_message'];
					unset ($_SESSION['licence_status_message']);
				}
				if (isset ($_SESSION['expiry_message']))
				{
					echo $_SESSION['expiry_message'];
					unset ($_SESSION['expiry_message']);
				}
				if (isset($_SESSION['max_counter_message']))
				{
					echo $_SESSION['max_counter_message'];
					unset ($_SESSION['max_counter_message']);
				}
				if (isset($_SESSION['member_message']))
				{
					echo $_SESSION['member_message'];
					unset ($_SESSION['member_message']);
				}
				/*if (isset($_SESSION['purge_users_message']))
				{
					echo $_SESSION['purge_users_message'];
					unset ($_SESSION['purge_users_message']);
				}
				if (isset($_SESSION['purge_performance_message']))
				{
					echo $_SESSION['purge_performance_message'];
					unset ($_SESSION['purge_performance_message']);
				}
				if (isset($_SESSION['clear_test_log_message']))
				{
					echo $_SESSION['clear_test_log_message'];
					unset ($_SESSION['clear_test_log_message']);
				}*/
				if (isset($_SESSION['users_query_msg']))
				{
					echo $_SESSION['users_query_msg'];
					unset ($_SESSION['users_query_msg']);
				}
				if (isset($_SESSION['first_day_message']))
				{
					echo $_SESSION['first_day_message'];
					unset ($_SESSION['first_day_message']);
				}
				if (isset($_SESSION['multiuser_message']))
				{
					echo $_SESSION['multiuser_message'];
					unset ($_SESSION['multiuser_message']);
				}
				if (isset($_SESSION['id_check_message']))
				{
					echo $_SESSION['id_check_message'];
					unset($_SESSION['id_check_message']);
				}
			?> 
		</div>
			
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>