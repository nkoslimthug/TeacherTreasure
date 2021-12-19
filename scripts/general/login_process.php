<?php
$root_path="../../";
include($root_path."config/paths.php");
include($root_path."config/sungunura.php");
include ("../functions/create_mac_file.php");
include ("../functions/my_mac.php");
include ("../functions/update_mac_file.php");
//header ("Location:login.php");


//check program expiry - mysql number of days between dates
	$expiry_query="SELECT DATE_ADD(start_date, INTERVAL 1 year) AS expiry,current_date FROM tblusers;";
	$expiry_result=mysqli_query($cxn,$expiry_query);
	$row=mysqli_fetch_assoc($expiry_result);
	extract ($row);
	echo "Current date is ".$current_date."<br>";
	echo "Expiry date is ".$expiry."<br>";
	
	if ($current_date>$expiry)
	{
		$_SESSION['expiry_message']="Your licence has expired. Please contact vendor for renewal<br>";
		
		header ("Location:../../index.php");
		exit;
	}
	else
	{
		//$_SESSION['expiry_message']="Application still in service<br>";
	}	
	echo $_SESSION['expiry_message'];
	
if (isset($_POST['btnLogin']))				//Login 
{
	$_SESSION['btnLogin']=$_POST['btnLogin'];
}
$char_counter=0;
		foreach ($_POST AS $key => $value)  //validate user input
		{
			if ($key!=='btnAnswer')
			{
				echo "$key = $value<br>";
				$char_count=strlen($value);
				while ($char_counter<$char_count)
				{
					$current_char=substr($value,$char_counter,1);
					echo "Character $char_counter is ".$current_char."<br>";
					//Validate each character
					$current_outcome=preg_match("~[A-Za-z0-9]~",$current_char);
					echo "Current outcome is $current_outcome<br/>";
					if ($current_outcome==0)
					{
						$_SESSION['answer_validate']="Invalid character entered<br>";
						echo $_SESSION['answer_validate'];
						header("Location:login.php");
						exit;
					}
					$char_counter++;
				}
			}
		}
	
	//user array
	$users=[];
	$user_counter=0;
	$user_query="SELECT username FROM tblmembers";
	if (!$user_result=mysqli_query($cxn,$user_query))
	{
		$_SESSION['users_message']="No users found<br>";
		header("Location:login.php");
		exit;
	}
	else
	{
		while ($row=mysqli_fetch_assoc($user_result))	//generate users array
		{
			extract ($row);
			$users[$user_counter]=$username;
			$user_counter++;
		}
	}
	
	$_POST['username'] = trim($_POST['username']);
	$_POST['password'] = trim($_POST['password']);
	//check if user exists
		if (!in_array($_POST['username'],$users))
		{
			$_SESSION['login_message']="Invalid username or wrong password<br>";
			header("Location:login.php");
			exit;
		}
		else   //authenticate user
		
		$username_length=strlen($_POST['username']);
		$password_length=strlen($_POST['password']);
		if ($username_length==0||$password_length<8)
		{
			$_SESSION['login_message']="Invalid username or wrong password. Blank username is not allowed and password must be at least 8 characters long<br>";
			header("Location:login.php");
			exit;
		}
		else
		{
			$login_query="SELECT username,forename,lastname,middle_name1,middle_name2,grade,member_type,AES_DECRYPT(`password`,'9@55w0rd') AS password,now()-last_login AS date_diff 
						FROM tblmembers WHERE username='".$_POST['username']."'";
			echo $login_query."<br>";
			if (!$login_result=mysqli_query($cxn,$login_query))
			{
				$_SESSION['login_message']=mysqli_error($cxn);
				header ("Location:login.php");
				exit;
			}
			else
			$row=mysqli_fetch_assoc($login_result);
			extract ($row);
			//check date tampering
			if ($date_diff<0)
			{
				$_SESSION['date_tempering_message']="Date reversal detected<br> Please contact program vendor for support<br>";
				header ("Location:../students/student_home.php");
				exit;
			}
				if ($_POST['username']==$username && $_POST['password']==$password)  //correct login
				{
					//update login time
					$login_time_query="UPDATE tblmembers SET last_login=now()
										WHERE username='$username'";
										echo $login_time_query."<br>";
					if (!$login_time_result=mysqli_query($cxn,$login_time_query))
					{
						$_SESSION['login_time_message']=mysqli_error($cxn);
					}
					else
					{
						$_SESSION['login_time_message']="Login time updated";
					}
					echo $_SESSION['login_time_message']."<br>";
					//Verify grade
					$grade_query="SELECT username,SUBSTR(now(),1,4)-SUBSTR(last_login,1,4) AS year_diff,SUBSTR(now(),1,4) AS new_year
								FROM tblmembers 
								WHERE username='$username'";
					if (!$grade_result=mysqli_query($cxn,$grade_query))
					{
						echo "Error ".mysqli_error($cxn)."<br>";
					}
					else
					{
						$row=mysqli_fetch_assoc($grade_result);
						extract ($row);
						$_SESSION['year_diff']=$year_diff;
						$_SESSION['new_year']=$new_year;
					}
					//set parameters
					$_SESSION['username']=$username;
					$_SESSION['forename']=$forename;
					$_SESSION['lastname']=$lastname;
					$_SESSION['middle_name1']=$middle_name1;
					$_SESSION['middle_name2']=$middle_name2;
					$_SESSION['fullname']="<b>".$forename." ".$middle_name1." ".$middle_name2." ".$lastname."</b>";
					$_SESSION['welcome_msg']="Welcome <b>".$_SESSION['fullname']."</b><br>";
					$_SESSION['member_type']=$member_type;
					$_SESSION['student_grade']=$grade;
					$_SESSION['logged_in'] = 'T';
					$_SESSION['last_login_msg'] = "Login time updated<br>";
					if ($member_type==2)
					{	
						header ("Location:../students/student_home.php");
						exit;
					}
					if ($member_type==1)
					{	
						header ("Location:../administrators/administrator_home.php");
						exit;
					}
					
				}
				else 
				{
					$_SESSION['login_message']="Invalid username or wrong password<br>";
					header ("Location:login.php");
					exit;
					
				}
				echo $_SESSION['login_message'];
		}
?>