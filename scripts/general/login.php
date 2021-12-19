<?php
	$root_path="../../";
	include($root_path."config/paths.php");
	include($root_path."config/sungunura.php");
	
?>
<!doctype html>
<html>
<head>
	<title>
		Login
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
			<style>
				input[type=text]{
					width: 200%;
					padding: 12px 2px;
					margin: 8px 0;
					box-sizing: border-box;
					border: 2px solid purple;
					border-radius: 8px;
				}
				input[type=password]{
					width: 200%;
					padding: 12px 2px;
					margin: 8px 0;
					box-sizing: border-box;
					border: 2px solid purple;
					border-radius: 8px;
				}
			</style>
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
		<br><br><br>
		<div id="menu">
			<ul id="nav">					
					<li><a href="../../index.php">Back</a></li>
				</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner"> 
		<?php
			if(isset($message))												
			{ 
				echo "<b style='color:red'>$message</b>"; 
				unset($message);
			}
			if(isset($_SESSION['login_message']))
			{ 
				echo "<b style='color:red'>".$_SESSION['login_message']."</b>"; 
				unset($_SESSION['login_message']);
			}
			if (isset($_SESSION['member_message']))
			{
				echo $_SESSION['member_message'];
				unset ($_SESSION['member_message']);
			}
			if (isset($_SESSION['status_message']))
			{
				echo $_SESSION['status_message'];
				unset ($_SESSION['status_message']);
			}
			
			if (isset($_SESSION['first_day_message']))
			{
				echo $_SESSION['first_day_message'];
				unset ($_SESSION['first_day_message']);
			}
			/*if (isset($_SESSION['id_check_message']))
			{
				echo $_SESSION['id_check_message'];
				unset($_SESSION['id_check_message']);
			}
			if (isset($_SESSION['update_status_message']))
			{
				echo $_SESSION['update_status_message'];
				unset ($_SESSION['update_status_message']);
			}
			if (isset($_SESSION['purge_users_message']))
			{
				echo $_SESSION['purge_users_message'];
				unset ($_SESSION['purge_users_message']);
			}*/
																							
		?>
		</div>
		<form  autocomplete="off" id="form1" name="form1" method="post" action="login_process.php">
								<table >
									<tr>
										<td colspan="2">
											<?php
											echo "<h2>";
												if(isset($message))												{ 
													echo "<b style='color:red'>$message</b>"; 
													unset($message);
												}
												if(isset($_SESSION['login_message']))												{ 
													echo "<b style='color:red'>".$_SESSION['login_message']."</b>"; 
													unset($_SESSION['login_message']);
												}
												if (isset($_SESSION['member_message']))
												{
													echo $_SESSION['member_message'];
													unset ($_SESSION['member_message']);
												}
											echo "</h2>";											
											?>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<div align="left">
												<strong>Login</strong>
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td >
											Login:
										</td>
										<td >
											<input type="text" name="username" id="username" required="required" />
										</td>
									</tr>
									<tr>
										<td>
											<label for="password">Passcode:</label>
										</td>
										<td>
											<input type="password" name="password" id="password" required="required" />
										</td>
									</tr>
								</table>
								<p>
									<input type="submit" name="btnLogin" id="btnLogin" value="Login"  />
								</p>
								<p>&nbsp;</p>
							</form>
		</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>