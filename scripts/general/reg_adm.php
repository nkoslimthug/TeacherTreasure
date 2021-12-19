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
														$service_query="SELECT * 
																		FROM tblservice";
																		echo $service_query."<br>";
														$service_result=mysqli_query($cxn,$service_query);
														while ($row=mysqli_fetch_assoc($service_result))
														{
															extract ($row);
															echo "<option value='$service_code'>$service_description</option>";
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
		<center>The Technological High Table</center>
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
								foreach ($_SESSION['invalid_fields'] as $key=>$value)
								{
									echo "$value<br>";	
								}
								//unset ($_SESSION['invalid_fields']);
								session_destroy();
							}
							
							unset ($_SESSION['invalid_answers']);
							/*foreach ($_SESSION as $key=>$value)
							{
								echo "$key = $value<br>";
							}*/
							
						?>
		</div>
			<form autocomplete='off' id='form1' name='capture' method='post' action='reg_adm_process.php'>
								<table >
									
										<tr>
											<td colspan='8'><div align='center'><strong>
											  <h3>Registration Form</h3></strong></div></td>
										</tr>					
										<tr>
											<td colspan='8'><strong><h4>Personal Details</h4></strong></td>
										</tr>
										<tr>
											<td ><label for='forename'>Firstname:</label></td>
											<td colspan='6' width='75%'><input type='text' name='forename' id='forename' value ='<?php if (isset($_POST['forename'])){ echo $_POST['forename'];}if (isset($forename)){ echo $forename;}?>' size='50' required='required'/></td>
										</tr>
										<tr>
											<td ><label for='lastname'>Surname:</label></td>
											<td colspan='3'><input type='text' name='lastname' id='lastname' value ='<?php if (isset($_POST['lastname'])){ echo $_POST['lastname'];} if (isset($lastname)){ echo $lastname;}?>' size='50' required='required'/></td>
										</tr>
										<tr>
											<td><label for='middle_name1'>Middle Name1:</label></td>
											<td colspan='3'><input type='text' name='middle_name1' id='middle_name1' size='50' value ='<?php if (isset($_POST['middle_name1'])){ echo $_POST['middle_name1'];}if (isset($middle_name1)){ echo $middle_name1;}?>' /></td>										
										</tr>
										<tr>
											<td><label for='middle_name2'>Middle Name2:</label></td>
											<td colspan='3'><input type='text' name='middle_name2' id='middle_name2' size='50' value ='<?php if (isset($_POST['middle_name2'])){ echo $_POST['middle_name2'];}if (isset($middle_name2)){ echo $middle_name2;}?>' /></td>										
										</tr>
																		
										<tr>
											<td><label for='role'>Role:</label></td>
											<td colspan='8'>
												<select name='role' id='role' required='required'>
													<option value='blank'>Please select your administrative role</option>
													<option value='Guardian'>Guardian</option>
																										
												</select>
											</td>
										</tr> 
										<tr>
											<td >&nbsp;</td>
										</tr>
										<tr>
											<td><label for='service'>Service:</label></td>
											<td>
												<select name='service' id='service' required='required'>
												<option value='blank'>Select Your Package</option>
													<?php
														$service_query="SELECT * 
																		FROM tblservice";
														$service_result=mysqli_query($cxn,$service_query);
														while ($row=mysqli_fetch_assoc($service_result))
														{
															extract ($row);
															echo "<option value='$service_code'>$service_description</option>";
														}
													?>							
												</select>
											</td>
										</tr> 
										<tr>
											<td ><p>&nbsp;</p></td>
										</tr>
										<tr>
											<td colspan='8'><strong><h4>Login Details</h4></strong></td>
										</tr>
										<tr>
											<td ><label for='username'>Login Name:</label></td>
											<td colspan='3'><input type='text' name='username' id='username' value ='<?php if (isset($_POST['username'])){ echo $_POST['username'];}if (isset($username)){ echo $username;}?>'  required='required'/></td>
										</tr> 
										<tr>
											<td><label for='password'>Passcode:</label></td>
											<td colspan='3'><input type='password' name='password' id='password' <?php if(!isset($_GET['u_id'])){echo "required='required'";}?>/></td>
										</tr> 
										<tr>	
											<td>Confirm code:</td>
											<td colspan='3'><input type='password' name='passwordconfirm' id='passwordconfirm' <?php if(!isset($_GET['u_id'])){echo "required='required'";}?>/></td>
										</tr>
															
								</table>
								<p>
								</p>
								<p>
									<input type='submit' name='btnRegister' id='btnRegister' value='Register' />
									<a href="../../index.php">Cancel </a>
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