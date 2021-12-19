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
		Registration
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
			We are an organisation that is focussed on  
		
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>