<?php
$root_path="../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
if (isset($_POST['btnRegister']))
{
	$char_counter=0;
	foreach ($_POST AS $key=>$value)
	{
		if ($key!=='btnRegister')
		{
			echo "$key = $value<br>";
			$char_count=strlen($value);
			while ($char_counter<$char_count)
			{
				$current_char=substr($value,$char_counter,1);
				echo "Character $char_counter is ".$current_char."<br>";
				//Validate each character
				$current_outcome=preg_match("~[A-Za-z0-9 ]~",$current_char);
				echo "Current outcome is $current_outcome<br/>";
				if ($current_outcome==0)
				{
					$_SESSION['answer_validate']="Invalid character entered<br>";
					echo $_SESSION['answer_validate'];
				}
				$char_counter++;
			}
		}
	}
}
if ($_SESSION['source_form']=="manage_account.php")
		{
			$back_action="../administrators/administrator_home.php";
			$home_action="../administrators/administrator_home.php";
			$cancel_action="../administrators/administrator_home.php";
		}
		
		if ($_SESSION['source_form']=="reports.php")
		{
			$back_action="../administrators/administrator_home.php";
			$home_action="../administrators/administrator_home.php";
			$cancel_action="../administrators/administrator_home.php";
		}
		
		if ($_SESSION['source_form']=="student_home.php")
		{
			$back_action="../students/student_home.php";
			$home_action="../students/student_home.php";
			$cancel_action="../students/student_home.php";
		}
		if (isset($_SESSION['pupil_name']))
		{
			$back_action="../administrators/administrator_home.php";
			$home_action="../administrators/administrator_home.php";
			$cancel_action="../administrators/administrator_home.php";
			$pass="XXXXXXXX";
			
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
		<i>eCompanion - It's Just What the Teacher Ordered</i>
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
					<li><a href='<?php echo $back_action?>'>Back</a></li>
					<li><a href='<?php echo $home_action?>'>Home</a></li>
				</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner"> 
			<?php
							if (isset($_SESSION['field_validate']))
							{
								echo $_SESSION['field_validate'];
								unset ($_SESSION['field_validate']);
							}
							if (isset($_SESSION['password_message']))
							{
								echo $_SESSION['password_message'];
								unset ($_SESSION['password_message']);
							}
							/*foreach ($_SESSION as $key=>$value)
							{
								echo "$key = $value<br>";
							}*/
							
						?>
		</div>
			<form autocomplete='off' id='form1' name='capture' method='post' action='change_password_process.php'>
								<table >
									
										
										<tr>
											<td colspan='8'><strong><h4>Login Details</h4></strong></td>
										</tr>
										<tr>
											<td ><label for='username'>Login Name:</label></td>
											<td colspan='3'><input type='text' name='username' id='username' value ='<?php if (isset($_SESSION['pupil_name'])) { echo $_SESSION['pupil_name'];}  else { echo $_SESSION['username']; } ?>' readonly ='readonly' disabled='disabled'/></td>
										</tr> 
										<tr>
											<td><label for='old_password'>Old Passcode:</label></td>
											<?php
											if (isset($_SESSION['pupil_name']))
											{
												echo "<td colspan='3'><input type='password' name='old_password' id='old_password' value='".$pass."' readonly='readonly' disabled='disabled' /></td>";
											}
											else
											{
												echo "<td colspan='3'><input type='password' name='old_password' id='old_password' value='' /></td>";
											}
											?>
										</tr> 
										<tr>
											<td><label for='new_password'>New Passcode:</label></td>
											<td colspan='3'><input type='password' name='new_password' id='new_password' /></td>
										</tr> 
										<tr>	
											<td>Confirm New Password:</td>
											<td colspan='3'><input type='password' name='new_passwordconfirm' id='new_passwordconfirm' /></td>
										</tr>
										
									
											</table>										
										</td>
									</tr>
									
									
								</table>
								<p>
								</p>
								<p>
									<input type='submit' name='btnPassword' id='btnPassword' value='Change Password' />
									<a href="<?php echo $cancel_action; ?>">Cancel </a>
								</p>
							</form>
		
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>