<?php
//session_start();
include ("../../config/sungunura.php");
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
	if ($_SESSION['source_form']=="manage_account.php")
	{
		$action="../general/account_edit.php";
		$_SESSION['source_form']="pupil_select.php";
	}
	if ($_SESSION['source_form']=="reports.php")
	{
		$action="scores.php";
	}
	
?>
<!doctype html>
<html>
<head>
	<title>
		Pupil Selection
	</title>
	<link rel="stylesheet"
			type="text/css"
			<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
</head>
<body>
	<div id="banner" style="text-align:center";>
	
	</div>
	<div id="slogan_bar" style="background:white;color:purple;font-style:bold;" >
		<i>eCompanion - It's Just What the Teacher Ordered</i>
	</div>
	<div id="index_left_sidebar" style="background:white";>
		<br><br><br>
		<div id="menu">
			<ul>
				<li><a href="../administrators/administrator_home.php" >Back</a></li>
			</ul>
		</div>	
	</div>
	<div id="content" style="color:purple";>
			<div id="message">
					<?php 
						if (isset($_SESSION['person_insert_message']))
						{
							echo $_SESSION['person_insert_message']."<br/>";
							unset ($_SESSION['person_insert_message']);
						}
					?>
				</div>
				<form method="post" id="student_self_reg_frm.php" action="<?php echo $action; ?>">
					<table width="50%" border="2">
						<th id="self_reg_head" colspan="4">Pupil Selection</th>
						<tr>
							<td colspan="4">&nbsp</td>
						</tr>
						<tr>
							<td width="20%">Pupil Name:</td>
							<td width="30%">
								<select name="pupil_name" id="pupil_name" >
									<option value="">Select desired pupil</option>
									<?php
										$pupil_query="SELECT username,forename,lastname 
													FROM tblmembers 
													WHERE member_type=2 
													ORDER BY lastname,forename";
													
													/*$pupil_query="SELECT username,forename,lastname 
													FROM tblmembers 
													ORDER BY lastname,forename";*/
										if ($pupil_result=mysqli_query($cxn,$pupil_query))
										{
											while($row=mysqli_fetch_assoc($pupil_result))
											{
												extract ($row);
												echo "<option value='$username'>$forename $lastname</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="4">&nbsp</td>
						</tr>
						
						<tr>
							<td><input  type="submit" name="choose_pupil" name="choose_pupil" value="Submit Pupil" /></td>
						</tr>
					</table>
				</form>
		
	</div>
	<div id="index_right_sidebar" style="background:white";>

	</div>
	<div id="footer">

	</div>
	
</body>
</html>