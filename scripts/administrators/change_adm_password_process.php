<?php 
$root_path="../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");

function alpha_match($current_char)  //alphanumeric validation
{
	$current_outcome=preg_match("~[A-Za-z0-9 ]~",$current_char);
	return $current_outcome;
}

function integer_match($current_char) //integer validation
{
	$current_outcome=preg_match("~[0-9]~",$current_char);
	return $current_outcome;
}

function string_outcome($char_counter,$char_count)
{
	if (isset($_POST['btnRegister']))
	{
		$field_counter=0;
		foreach ($_POST AS $key=>$value)
		{
			if ($key!=='btnRegister')
			{
				echo "$key = $value<br><br>";
				$char_count=strlen($value);
				$char_counter=0;
				$invalid_counter=0;
				//$field_counter++;
				while ($char_counter<$char_count)
				{
					$current_char=substr($value,$char_counter,1);
					echo "Character $char_counter is ".$current_char."<br>";
					//Validate each character
					if ($key=='grade')
					{
						$grade=$value;
						$current_outcome=integer_match($current_char);
						if ($current_outcome==0)
						{
							$invalid_counter++;
							$_SESSION['field_validate']="Invalid character <b>$current_char</b> entered in field <b>$key</b><br>";
							echo $_SESSION['field_validate'];
							$invalid_fields[$field_counter]=$_SESSION['field_validate'];
						//	header("Location:registration.php");
						//	exit;
						}
					}
					/*else if ($key=='password'||$key==passwordconfirm)
					{
						//tag_check
					}*/
					else
					{
						if ($key=='username')
						{
							$username=$value;
							echo "Username is $username<br>";
						}
						else if ($key=='old_password')
						{
							$old_password=$value;
							echo "Old Password is $old_password<br>";
						}
						else if ($key=='new_password')
						{
							$new_password=$value;
							echo "Password is $new_password<br>";
						}
						else if ($key=='new_passwordconfirm')
						{
							$new_passwordconfirm=$value;
							echo "Password confirmation is $new_passwordconfirm<br>";
						}
						$current_outcome=alpha_match($current_char);						
					}
					echo "Current outcome is $current_outcome<br/>";
					if ($current_outcome==0)
					{
						$invalid_counter++;
						$_SESSION['field_validate']="Invalid character <b>$current_char</b> entered in field <b>$key</b><br>";
						echo $_SESSION['field_validate'];
						$invalid_fields[$field_counter]=$_SESSION['field_validate'];
						//header("Location:registration.php");
						//exit;
					}
					$char_counter++;
				}
				$field_counter++;
			}
			
		}
		if (@$invalid_fields)
		{
			$_SESSION['invalid_fields']=$invalid_fields;
		}
	}
	/*foreach ($invalid_fields as $key=>$value)
	{
		echo "$key = $value <br>";
	}*/
}
//
$char_counter=0; $char_count=0;
string_outcome($char_counter,$char_count); //validate field input

//retrieve old password and username
$login_query="SELECT username,AES_DECRYPT(`password`,'9@55w0rd') AS old_password FROM tblMembers WHERE username ='".$_SESSION['username']."'";
		echo $login_query."<br>";
		$login_result=mysqli_query($cxn,$login_query);
		$row=mysqli_fetch_assoc($login_result);
		extract ($row);
		echo "The old password for <b>$username</b> is <b>$old_password</b><br>";

//Password Matching Confirmation
if ($_POST['new_password']!=$_POST['new_passwordconfirm'])		//new password vs confirmation
{
	$_SESSION['password_message']="Non-matching passwords supplied<br>";
	echo $_SESSION['password_message'];
	echo "Supplied password is ".$_POST['password']."<br>";
	echo "Password confirmation is ".$_POST['passwordconfirm']."<br>";
	header("Location:./change_adm_password.php");
	exit;
}
else if ($_POST['old_password']!=$old_password)		//old password validation
{
	$_SESSION['password_message']="Wrong old password supplied<br>";
	echo $_SESSION['password_message'];
	header("Location:./change_adm_password.php");
	exit;
}
else if ($_POST['new_password']==$old_password)
{
	$_SESSION['password_message']="Current password cannot be repeated. Please supply a different password<br>";
	echo $_SESSION['password_message'];
	header("Location:./change_adm_password.php");
	exit;
}

//Password Length
$password_length=strlen($_POST['new_password']);
if ($password_length<8)
{
	$_SESSION['password_message']="Password should be at least 8 characters long. Submitted new pass is $password_length characters long<br>";
	echo $_SESSION['password_message'];
	header("Location:./change_adm_password.php");
	exit;
}


//echo "There are $invalid_count fields with illegal characters<br>";
//store_member($username,$forename,$lastname,$middle_names,$grade,$password); //store new member
echo "=============================================<br>";


echo "==============================================<br>";

$username=$_SESSION['username'];
$old_password=$_POST['old_password'];
$new_password=$_POST['new_password'];
$new_passwordconfirm=$_POST['new_passwordconfirm'];
//--------------------------------------------------------------------
$member_query="UPDATE tblmembers 
				SET	password=AES_ENCRYPT('$new_password','9@55w0rd') 
				WHERE username='".$_SESSION['username']."'";
echo $member_query."<br>";
if (!$member_result=mysqli_query($cxn,$member_query))
{
	$_SESSION['member_message']="Failed to store new member: ".mysqli_error($cxn)."<br>";
	header("Location:../../index.php");
	exit;
}
else
{
	$_SESSION['member_message']="Password reset successfully for user <b>".$_SESSION['forename']." ".$_SESSION['lastname']."</b><br>";
	header ("Location:../administrators/administrator_home.php");
	exit;
}
echo $_SESSION['member_message'];
?>