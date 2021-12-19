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
						if ($key=='forename')
						{
							$forename=$value;
							echo "Forename is $forename<br>";
						}
						else if ($key=='lastname')
						{
							$lastname=$value;
							echo "Lastname is $lastname<br>";
						}
						else if ($key=='middle_name1')
						{
							$middle_names=$value;
							echo "Middlename is $middle_name1<br>";
						}
						else if ($key=='middle_name2')
						{
							$middle_names=$value;
							echo "Middlename is $middle_name2<br>";
						}
						else if ($key=='username')
						{
							$username=$value;
							echo "Username is $username<br>";
						}
						else if ($key=='password')
						{
							$password=$value;
							echo "Password is $password<br>";
						}
						else if ($key=='passwordconfirm')
						{
							$passwordconfirm=$value;
							echo "Password confirmation is $passwordconfirm<br>";
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
				$value=trim($value);
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
//check blank fields
foreach ($_POST as $field =>$value)
{
	echo "$field = $value<br/>";
	if ($value=="blank")
	{
		$_SESSION['blank_message']="No option selected for field <b>$field</b><br>";
		echo $_SESSION['blank_message'];
		//header("Location:./reg_adm.php");
		//exit;
	}
}


$char_counter=0; $char_count=0;
string_outcome($char_counter,$char_count); //validate field input


//Password Matching Confirmation
if ($_POST['password']!=$_POST['passwordconfirm'])
{
	$_SESSION['password_message']="Non-matching passwords supplied<br>";
	echo $_SESSION['password_message'];
	echo "Supplied password is ".$_POST['password']."<br>";
	echo "Password confirmation is ".$_POST['passwordconfirm']."<br>";
	header("Location:reg_adm.php");
	exit;
}

//Password Length
$password_length=strlen($_POST['password']);
if ($password_length<6)
{
	$_SESSION['password_message']="Password should be longer than 6 characters. Submitted pass is $password_length characters long<br>";
	echo $_SESSION['password_message'];
	header("Location:reg_adm.php");
	exit;
}

echo "Outside the function<br>";
if (@$_SESSION['invalid_fields'])
{
	echo "There are elements in the array<br>";
	foreach ($_SESSION['invalid_fields'] as $key=>$value)
	{
		echo "$key = $value<br>";	
	}
}


//echo "There are $invalid_count fields with illegal characters<br>";
//store_member($username,$forename,$lastname,$middle_names,$grade,$password); //store new member
echo "=============================================<br>";


echo "==============================================<br>";

$username=trim($_POST['username']);
$forename=strtoupper(trim($_POST['forename']));
$lastname=strtoupper(trim($_POST['lastname']));
$middle_name1=strtoupper(trim($_POST['middle_name1']));
$middle_name2=strtoupper(trim($_POST['middle_name2']));

//$grade=(int)$_POST['grade'];
$password=trim($_POST['password']);
//--------------------------------------------------------------------
//member_type 1 is an administrator
//member type 2 is a student
$member_query="INSERT INTO tblmembers 
				(username,forename,lastname,middle_name1,middle_name2,member_type,password)
				VALUES 
				('$username','$forename','$lastname','$middle_name1','$middle_name2',1,AES_ENCRYPT('$password','9@55w0rd'))";
if (!$member_result=mysqli_query($cxn,$member_query))
{
	$_SESSION['member_message']="Failed to store new member: ".mysqli_error($cxn)."<br>";
	header("Location:../index.php");
	exit;
}
else
{
	$_SESSION['member_message']="User <b>$username</b> registered successfully<br>";
	header ("Location:login.php");
	exit;
}
echo $_SESSION['member_message'];

?>