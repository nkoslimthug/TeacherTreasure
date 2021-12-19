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
			<form autocomplete='off' id='form1' name='capture' method='post' action='registration_process.php'>
								<table >
									
										<tr>
											<td colspan='8'>
												<div align='center'>
													<strong><h3>Student Registration Form</h3></strong>
												</div>
											</td>
										</tr>					
										<tr>
											<td colspan='8'><strong><h4>Personal Details</h4></strong></td>
										</tr>
										<tr>
											<td><label for='forename'>Firstname:</label></td>
											<td colspan='3'><input type='text' name='forename' id='forename' value ='<?php if (isset($_POST['forename'])){ echo $_POST['forename'];}if (isset($forename)){ echo $forename;}?>' size='50' required='required'/></td>
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
											<td><label for='grade'>Grade:</label></td>
											<td>
												<select name='grade' id='grade' required='required'>
												<option value='blank'>Select Your Grade</option>
													<?php
														$grade_counter=1;		
														$s_grade = 0;
														$total_grades=7;
														while ($s_grade<=$total_grades)
														{
															echo "<option value='$s_grade' ";
															if((isset($_POST['grade']) &&($_POST['grade']==$s_grade))||(isset($grade)&&($grade==$s_grade))){
																echo "selected='selected' ";
															}
															echo ">$s_grade</option>";
															$s_grade++;
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
											<td>Confirm Passcode:</td>
											<td colspan='3'><input type='password' name='passwordconfirm' id='passwordconfirm' <?php if(!isset($_GET['u_id'])){echo "required='required'";}?>/></td>
										</tr>
										
									
											</table>										
										</td>
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