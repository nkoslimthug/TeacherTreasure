<?php 
$root_path="../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");

foreach ($_POST as $key => $value)
{
	echo "$key = $value<br>";
}
$_SESSION['invalid_fields']='';
$invalids='';
function alpha_match($current_char)  //alphanumeric validation
{
	$current_outcome=preg_match("~[A-Za-z0-9]~",$current_char);
	return $current_outcome;
}

function password_match($current_char)  //password validation
{
	$current_outcome=preg_match("~[A-Za-z0-9!@#$%^&*()_]~",$current_char);
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
		$invalid_counter=0;
		
		foreach ($_POST AS $key=>$value) //loop through submitted strings
		{
			if ($key!=='btnRegister')
			{
				//echo "$key = $value<br><br>";
				$char_count=strlen($value);
				$char_counter=0;
				
				//$field_counter++;
				while ($char_counter<$char_count) //loop through characters in a given string
				{
					echo "Header=======================================<br>";
					echo "AT top of loop invalid_counter=$invalid_counter<br>";
					$current_char=substr($value,$char_counter,1);
					echo "Character $char_counter is ".$current_char."<br>";
					//Validate each character
					
					if ($key=='old_password'||$key='new_password'||$key=='new_passwordconfirm')
					{
						$current_outcome=password_match($current_char);
					}
					if ($current_outcome==0)
					{
						$invalid_counter++;
						$_SESSION['invalid_counter']=$invalid_counter;
						if ($current_char==' ')
						{
							$_SESSION['field_validate']="Space character entered in field <b>$key</b> is illegal<br>";
						}
						else
						{
							$_SESSION['field_validate']="Invalid character <b>$current_char</b> entered in field <b>$key</b><br>";
						}
						$invalid_fields[$invalid_counter]=$_SESSION['field_validate'];
						$_SESSION['$invalid_fields']=$invalid_fields;
						echo "++++++++++++++++++++<br>";
						echo $_SESSION['field_validate'];
						echo "Character counter is $char_counter<br>";
						echo "Field counter is $field_counter<br>";
						echo "Invalid counter is $invalid_counter<br>";
						echo "++++++++++++++++++++<br>";
					}
					$char_counter++;
					echo "AT bottom of loop invalid_counter=$invalid_counter<br>";
					echo "Footer=======================================<br>";
				}
				//if (isset($_SESSION['field_validate'])) {	echo $_SESSION['field_validate'];}
				$field_counter++;
				$value=trim($value);
			}
		}
	}
	if (isset($invalid_fields)){
		return $invalid_fields;
	}
}

$char_counter=0; $char_count=0;
echo "Starting function operation<br>";
$invalids=string_outcome($char_counter,$char_count);
echo "Function operation completed<br>";
if (isset($_SESSION['invalid_counter']))
{
	if ($_SESSION['invalid_counter']>0)         //invalid fields exist
	{
		$_SESSION['invalid_fields']=$invalids;
		echo "Invalid fields exist<br>";
		header ("Location:change_password.php");
		exit;
	}
}
else
{
	echo "No invalid fields<br>";
}

foreach ($_POST as $field =>$value)
{
	//echo "$field = $value<br/>";
	if ($value=="blank" && ($value!="middle_name1"||$value!="middle_name2"))
	{
		$_SESSION['blank_message']="No option selected for field <b>$field</b><br>";
		echo $_SESSION['blank_message'];
		header("Location:./registration.php");
		exit;
	}
}


string_outcome($char_counter,$char_count); //validate field input

//
$char_counter=0; $char_count=0;
string_outcome($char_counter,$char_count); //validate field input
//Password Length
$password_length=strlen($_POST['new_password']);
//retrieve old password and username
$login_query="SELECT username,AES_DECRYPT(`password`,'9@55w0rd') AS old_password ,member_type
				FROM tblmembers 
				WHERE username ='".$_SESSION['username']."'";
				echo $login_query."<br>";
				$login_result=mysqli_query($cxn,$login_query);
				$row=mysqli_fetch_assoc($login_result);
				extract ($row);
				echo "The old password for <b>$username</b> is <b>$old_password</b><br>";

//Password Matching Confirmation
	
if ($_POST['new_password']!=$_POST['new_passwordconfirm'])					//new password vs confirmation
{
	$_SESSION['password_message']="Non-matching passwords supplied<br>";
	echo $_SESSION['password_message'];
	echo "Supplied password is ".$_POST['password']."<br>";
	echo "Password confirmation is ".$_POST['passwordconfirm']."<br>";
	header("Location:./change_password.php");
	exit;
}

/*else if (!isset($_SESSION['pupil_name']))
{
	if ($_POST['old_password']!=$old_password)		//old password validation
	{
		$_SESSION['password_message']="Wrong old password supplied<br>";
		echo $_SESSION['password_message'];
		header("Location:./change_password.php");
		exit;
	}
}*/
else if ($_POST['new_password']==$old_password)
{
	$_SESSION['password_message']="Current password cannot be repeated. Please supply a different password<br>";
	echo $_SESSION['password_message'];
	header("Location:./change_password.php");
	exit;
}
if ($password_length<8)
{
	$_SESSION['password_message']="Password should be at least 8 characters. Submitted pass is $password_length characters long<br>";
	echo $_SESSION['password_message'];
	header("Location:./change_password.php");
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

	if ($_SESSION['member_type']==2)
	{
		$username=$_SESSION['username'];
		
	}
	else if ($_SESSION['member_type']==1)
	{
		if ($_SESSION['source_form']=="pupil_select.php")
		{	
			$username=$_SESSION['pupil_name'];
		}
		else
		{
			$username=$_SESSION['username'];
		}
	}
//--------------------------------------------------------------------
$member_query="UPDATE tblmembers 
				SET	password=AES_ENCRYPT('$new_password','9@55w0rd') 
				WHERE username='".$username."'";
echo $member_query."<br>";
if (!$member_result=mysqli_query($cxn,$member_query))
{
	$_SESSION['member_message']="Failed to store new member: ".mysqli_error($cxn)."<br>";
	header("Location:../../index.php");
	exit;
}
else
{
	echo "Source form is ".$_SESSION['source_form']."<br>";
		
	if ($_SESSION['source_form']=="pupil_select.php")
	{
		$_SESSION['member_message']="Password reset successfully for <b>".$_SESSION['pupil_name']."</b><br>";
		header ("Location:../administrators/administrator_home.php");
		exit;
	}
	else
	{		
		$_SESSION['member_message']="Password reset successfully for <b>".$_SESSION['username']."</b><br>";
		if ($_SESSION['source_form']=="manage_account.php")
		{
			header ("Location:../administrators/administrator_home.php");
			exit;
		}
		
		else if ($_SESSION['source_form']=="student_home.php")
		{
			header ("Location:../students/student_home.php");
			exit;
		}
	}
}
echo $_SESSION['member_message'];
?>