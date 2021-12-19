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
				<big>CyBerPlateau is focussed on providing sustainable solutions to the communities we serve.
				We strive to achieve this through exploitation of developments in the technological and socio-economic 
				arena and harnessing resources in our midst in an adept manner. Our activities are thus driven by the 
				needs of our customers in particular, and communities in general. Ultimately, we desire to see our effort
				contributing to the general betterment of our nation and the world at large.	</big>			
			</form>
		
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>