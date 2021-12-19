<?php
	$root_path="../../";
	include($root_path."config/paths.php");
	include ($root_path."config/sungunura.php");
	
	/*foreach ($_POST as $key => $value)
	{
		echo $key. " = ".$value."<br>";
	}*/
	
	if (isset($_POST['pupil_name']))
	{
		$_SESSION['pupil_name']=$_POST['pupil_name'];
		$user_query="SELECT * FROM tblmembers WHERE username='".$_POST['pupil_name']."'";
		$user_result=mysqli_query($cxn,$user_query);
		$row=mysqli_fetch_assoc($user_result);
		extract ($row);
		//echo "Current user is $username<br>";
		//$_SESSION['username']=$username;
	}	
	
	//Retrieve user details from database to populate fields
	if ($_SESSION['source_form']=="manage_account.php"||$_SESSION['source_form']=="student_home.php")
	{
		$user_query="SELECT * 
					FROM tblmembers 
					WHERE username='".$_SESSION['username']."'";
					//echo $user_query."<br>";
		$user_result=mysqli_query($cxn,$user_query);
		$row=mysqli_fetch_assoc($user_result);
		extract ($row);
		//echo "Current user is $username<br>";
		$_SESSION['username']=$username;
		
	}
	
	/*if ($_SESSION['source_form']=="reports.php")
	{
		$user_query="SELECT * FROM tblmembers WHERE username='".$_SESSION['username']."'";
		$user_result=mysqli_query($cxn,$user_query);
		$row=mysqli_fetch_assoc($user_result);
		extract ($row);
		//echo "Current user is $username<br>";
		$_SESSION['username']=$username;
	}*/
	
		// Back and Home and Cancel
		if ($_SESSION['source_form']=="pupil_select.php")
		{
			$back_action="../administrators/administrator_home.php";
			$home_action="../administrators/administrator_home.php";
			$cancel_action="../administrators/administrator_home.php";
		}
		
		
		if ($_SESSION['source_form']=="manage_account.php")
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
		
?>
<!doctype html>
<html>
<head>
	<title>
		Account Edit
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
</head>
<body>
	<div id="banner" >
		
	</div>
	<div id="slogan_bar" >
		<i>eCompanion -  It's Just What the Teacher Ordered</i>
	</div>
	<div id="message_bar" >
		
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			<ul class="nav">
				<div id="menu">
			<ul class="nav">
					<li><a href='<?php echo $back_action?>'>Back</a></li>
					<li><a href='<?php echo $home_action?>'>Home</a></li>
					
     			</ul>
		</div>	
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
				
				if (isset($_SESSION['member_message']))
				{
					echo $_SESSION['member_message'];
					unset ($_SESSION['member_message']);
				}
				
							
			?>
		</div>
		<form autocomplete='off' id='form1' name='capture' method='post' action='account_edit_process.php'>
								<table >
								<tr>
											<td colspan='8'><div align='center'><strong>
											  <h3>Account Modification Form</h3></strong></div></td>
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
										<?php
											if(!(isset($admin_add)) || isset($member_edit) ){
										?>									
										<tr>
										<?php
										if ($_SESSION['member_type']==2||$_SESSION['source_form']=="pupil_select.php")
												{
											echo "<td><label for='grade'>Grade:</label></td>";
											echo "<td>";
												
												
													echo "<select name='grade' id='grade' >";
												
												
														$grade_counter=1;		
														$s_grade = 1;
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
												}
												?>							
												</select>
											</td>
										</tr> 
										<?php
											}
										?>
										<tr>
											<td ><p>&nbsp;</p></td>
										</tr>
										<tr>
											<td colspan='8'><strong><h4>Login Details</h4></strong></td>
										</tr>
										<tr>
											<td ><label for='username'>Login Name:</label></td>
											<td colspan='3'><input type='text' name='username' id='username' value ='<?php if (isset($_POST['username'])){ echo $_POST['username'];}if (isset($username)){ echo $username;}?>'  required='required' readonly='readonly' /></td>
										</tr> 
										
										<tr>
										<tr>
											<td ><p>&nbsp;</p></td>
										</tr>
										<tr>
											<td><a href="./change_password.php"><big>Change Password</big> </a></td>
											
										</tr> 
										
										<p>
										<tr>
										<tr>
											<td ><p>&nbsp;</p></td>
										</tr>
										<td><input type='submit' name='btnEditUser' id='btnEditUser' value='Submit User' /></td>
									<td><a href="<?php echo $cancel_action;?>">Cancel </a></td>
									</tr>
								</p>
																			
									</table>										
									
								<p>
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