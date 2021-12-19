<?php
$root_path="../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");

?>
<!doctype html>
<html>
<head>
	<title>
		Registration
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
</head>
<body>
	<div id="banner" >
		
	</div>
	<div id="slogan_bar" >
		<center><i>CyBerPlateau - The Technological High Table</i></center>
	</div>
	<div id="message_bar" >
		
		<?php
							/*if (isset($_SESSION['password_message']))
							{
								echo $_SESSION['password_message'];
								unset ($_SESSION['password_message']);
							}
							if (isset($_SESSION['invalid_fields']))
							{
								foreach ($_SESSION['invalid_fields'] as $key=>$value)
								{
									echo "$key = $value<br>";	
								}
								unset ($_SESSION['invalid_fields']);
							}
							
							unset ($_SESSION['invalid_answers']);*/
						?>
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			<ul id="nav">
			<br>
					<?php
					if(!isset($_SESSION['loggedin'])){
						echo "<li><a href='".$root_path."scripts/general/logout.php'>Back</a></li>";					
					}
					else{
						if(isset($admin_add)){		
							echo "<li><a href='".$root_path."scripts/administrators/services/view-users.php'>Back</a></li>";
						}
						else{
							echo "<li><a href='".$root_path."scripts/students/student_home.php'>Back</a></li>";	
						}	
						echo "<li><a href='".$root_path."scripts/general/logout.php'>Logout</a></li>";											
					}
					?>
				</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner"> 
			<?php
							if (isset($_SESSION['password_message']))
							{
								echo $_SESSION['password_message'];
								unset ($_SESSION['password_message']);
							}
							if (isset($_SESSION['invalid_fields']))
							{
								if (isset($_SESSION['invalid_counter']))
								{
									foreach ($_SESSION['invalid_fields'] as $key => $value)
									{
										echo $value."<br>";
									}
									unset ($_SESSION['invalid_fields']);
									unset ($_SESSION['invalid_counter']);
									session_destroy();
								}
							}
							if (isset($_SESSION['member_message']))
							{
								echo $_SESSION['member_message'];
								unset ($_SESSION['member_message']);
							}
							//unset ($_SESSION['invalid_answers']);
							/*foreach ($_SESSION as $key=>$value)
							{
								echo "$key = $value<br>";
							}*/
							
						?>
		</div>
			<form autocomplete='off' id='form1' name='capture' method='post' action='registration_process.php'>
				<br><br><br>
				<table border='2'>
					<tr>
						<td colspan='3'>
							<div align='center'>
								<strong><h3>Professional Training Services</h3></strong>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan='3'>&nbsp;</td>
					</tr>
					<tr>
						<td><div><strong><h4>Field</h4></strong></div></td>
						<td><div><strong><h4>Summary</h4></strong></div></td>
						<td><div><strong><h4>Platform(s) or Package(s)</h4></strong></div></td>
					</tr>
					<tr>
						<td>Computer Networking Routing and Switching</td>
						<td>Network Design, Implementation (Configuration) and Troubleshooting</td>
						<td>CISCO</td>
					</tr>
					<tr>
						<td>Database Scripting and Administration</td>
						<td>Data Manipulation, Administration and Security</td>
						<td>Oracle, MySQL, MSSQL, POSTGRESQL</td>
					</tr>
					<tr>
						<td>Web Design and Development</td>
						<td>Web Layout and Scripting. Data driven apps and interractive websites</td>
						<td>LAMP Webstack, HTML, Apache, MySQL, PHP, CSS, JavaScript</td>
					</tr>
					<tr>
						<td>Systems Administration</td>
						<td>Systems Administration Roles and Concepts.Linux Operating System Network Services</td>
						<td>Linux</td>
					</tr>
					<tr>
						<td>Computer Architecture</td>
						<td>Computer Systems Organisation, Features and Operation</td>
						<td>Intel X86 Family</td>
					</tr>
					<tr>
						<td>Microsoft Office</td>
						<td>Introductory and Advanced Training on the Microsoft Office software package</td>
						<td>Windows, Microsoft Office</td>
					</tr>
					<tr>
						<td>Foundation Computing Concepts</td>
						<td>Electricity and Waves, Number Systems, Boolean Logic, Analogue and Digital Signals </td>
						<td>Theory</td>
					</tr>
					<tr>
						<td>Career Development and the Workplace</td>
						<td>Business Operations Overview, Staying Relevant Through Continuous Improvement, Acquiring the Right Skills</td>
						<td>Theory</td>
					</tr>
					<tr>
						<td colspan='3' >&nbsp;</td>
					</tr>
				</table>	
			</form>
		<br><br><br>
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>