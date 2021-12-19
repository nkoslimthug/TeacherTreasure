<?php 
$root_path="../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
include ("../functions/create_mac_file.php");
include ("../functions/my_mac.php");
include ("../functions/update_mac_file.php");

/*if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
*/
//--------------------------Package Validation------------------------------

	//Retrieve current machine's MAC address
	$mac_file="";
	$current_mac=my_mac();

	//read file if it exists and capture the stored MAC
	if (file_exists("./id.php"))
	{
		$id_outcome=file_exists("./id.php");	//set flag
		//Read the file
		$my_file = "./id.php";
		$handle = fopen($my_file,'r');
		$stored_mac = fread($handle,filesize($my_file)); //read stored mac
		fclose($handle);
		echo "ID outcome is $id_outcome<br>";
		echo "the stored address is $stored_mac<br>";
		if ($stored_mac==$current_mac)  //this is the machine of previous execution - continue operations
		{
			//perform second check
			$db_status_query="SELECT user,start_date,expiry_date,now() AS now 
								FROM tblusers";
			if (!$db_status_result=mysqli_query($cxn,$db_status_query))
			{
				$_SESSION['db_status_message']="Failed to get db status: ".mysqli_error($cxn)."<br>";
				header("Location:../../index.php");
				exit;
			}
			else if ($row=mysqli_fetch_assoc($db_status_result))
			{
				extract ($row);
				if ($user==1)
				{
					$_SESSION['licence_status_message']="Unauthorised deployment1. Please contact vendor for support";
					header("Location:../../index.php");
					exit;
				}
				if ($now>$expiry_date)
				{
					$_SESSION['licence_status_message']="Your licence expired on <b>".$expiry_date."</b>. Contact vendor for renewal<br>";
					header("Location:../../index.php");
					exit;
				}
				if (date("Y-m-d H:i:s",filemtime("./id.php"))>$start_date)
				{
					$_SESSION['id_check_message']="ID Inconsistency detected. Contact vendor for assistance<br>";
				}
				else if (date("Y-m-d H:i:s",filemtime("./id.php"))<=$start_date)
				{
					$_SESSION['id_check_message']="Your licence has been successfully validated<br>";
				}
			}
			//Package enforcement
			
			
			//
			$limit_query="SELECT COUNT(*) AS user_count 
						FROM tblmembers 
						WHERE member_type=2";
			$limit_result=mysqli_query($cxn,$limit_query);
			if ($row=mysqli_fetch_assoc($limit_result))
			{
				extract ($row);
				if ($user_count>=5)
				{
					$_SESSION['multiuser_message']="You need a multiuser licence. Contact vendor for clarification<br>";
					header("Location:../../index.php");
					exit;
				}
				else if ($user_count<5)
				{
					$_SESSION['licence_status_message']="Your licence has been successfully validated.<br>"; 
				}
			}
		}
		//second step
		//Check status in DB
		
		if ($stored_mac!=$current_mac)					//this is different machine - abort operations
		{
			$_SESSION['licence_status_message']="Unauthorised deployment2. Please contact vendor for support<br>";
			//
			$update_status_query="UPDATE tblusers 
									SET user=1";
			if (!$update_status_result=mysqli_query($cxn,$update_status_query))
			{
				$_SESSION['update_status_message']="Users update failed: ".mysqli_error($cxn)."<br>";
			}
			else
			{
				$_SESSION['update_status_message']="Users updated<br>";
			}
			
			echo $_SESSION['update_status_message'];
			header("Location:../../index.php");
			exit;
		}
	}
	if (!file_exists("./id.php"))						//first package execution -continue operations
	{
		$users_query="SELECT COUNT(*) AS status 
						FROM tblusers";
		$users_result=mysqli_query($cxn,$users_query);
		$row=mysqli_fetch_assoc($users_result);
		extract($row);
		if ($status>0)
		{
			$_SESSION['users_query_msg']="Program inconsistency detected. Contact vendor for support<br>";
			echo $_SESSION['users_query_msg'];
			header("Location:../../index.php");
			exit;
		}
		else
		{
			$id_outcome=0;
			$_SESSION['licence_status_message']="Your licence has been successfully validated.<br>"; //This is material from the source
			create_mac($mac_file);			//create mac_file
			update_mac($mac_file);			//update mac_file
			
			//Delete users
			$purge_users_query="DELETE FROM tblmembers";
			if (!$purge_users_result=mysqli_query($cxn,$purge_users_query))
			{
				$_SESSION['purge_users_message']="Deletion failed: ".mysqli_error($cxn)."<br>";
			}
			else
			{
				$_SESSION['purge_users_message']="Users purged<br>";
			}
			echo $_SESSION['purge_users_message'];
			
			//Clear performance records
			$purge_performance_query="DELETE FROM tblperformance";
			if (!$purge_performance_result=mysqli_query($cxn,$purge_performance_query))
			{
				$_SESSION['purge_performance_message']="Deletion failed: ".mysqli_error($cxn)."<br>";
			}
			else
			{
				$_SESSION['purge_performance_message']="Performance records purged<br>";
			}
			echo $_SESSION['purge_performance_message'];
			
			//Clear detailed test log
			$clear_test_log_query="DELETE FROM tblmctestlog";
			if (!$clear_test_log_result=mysqli_query($cxn,$clear_test_log_query))
			{
				$_SESSION['clear_test_log_message']="Deletion failed: ".mysqli_error($cxn)."<br>";
			}
			else
			{
				$_SESSION['clear_test_log_message']="Detailed test log cleared<br>";
			}
			echo $_SESSION['clear_test_log_message'];
			
			//mark date of first use
			$first_day_query="INSERT INTO tblusers 
							(start_date,expiry_date) 
								VALUES 
							(now(),DATE_ADD(now(),INTERVAL 1 year))";
			echo $first_day_query."<br>";
			if (!$first_day_result=mysqli_query($cxn,$first_day_query))
			{
				$_SESSION['first_day_message']="Failed to mark first day of use<br>";
				header("Location:../../index.php");
				exit;
			}
			else
			{
				$expiry_date_query="SELECT now() AS now,expiry_date 
									FROM tblusers";
				$expiry_date_result=mysqli_query($cxn,$expiry_date_query);
				$row=mysqli_fetch_assoc($expiry_date_result);
				extract ($row);
				$_SESSION['first_day_message']="First day of use has been marked as <b>".$now."</b>. Licence expires on <b>".$expiry_date."</b><br>";
			}
		}
	}
	echo $_SESSION['licence_status_message'];
	//echo file_exists("./id.php")."<br>";
	echo file_exists("./it.php")."<br>";
	
//============================Data validation================================
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
					if ($key=='grade')
					{
						$current_outcome=integer_match($current_char);
					}
					else if ($key=='password'||$key=='passwordconfirm')
					{
						$current_outcome=password_match($current_char);
					}
					else if ($key=='forename'||$key=='lastname'||$key=='middle_name1'||$key=='middle_name2'||$key=='username')
					{
						$current_outcome=alpha_match($current_char);
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
		header ("Location:registration.php");
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


//Password Matching Confirmation
if ($_POST['password']!=$_POST['passwordconfirm'])
{
	$_SESSION['password_message']="Non-matching passwords supplied<br>";
	header("Location:registration.php");
	exit;
}

//Password Length
$password_length=strlen($_POST['password']);
if ($password_length<8)
{
	$_SESSION['password_message']="Password should be at least 8 characters long. Submitted pass is $password_length characters long<br>";
	echo $_SESSION['password_message'];
	header("Location:registration.php");
	exit;
}

//echo "==============================================<br>";
$username=trim($_POST['username']);
$forename=strtoupper(trim($_POST['forename']));
$lastname=strtoupper(trim($_POST['lastname']));
$middle_name1=strtoupper(trim($_POST['middle_name1']));
$middle_name2=strtoupper(trim($_POST['middle_name2']));
$grade=(int)$_POST['grade'];
$password=trim($_POST['password']);
//--------------------------------------------------------------------
//Read set user max
$max_user_query="SELECT counter FROM tbllearners";
$max_user_result=mysqli_query($cxn,$max_user_query);
$row=mysqli_fetch_row($max_user_result);
$max_counter=$row[0];
echo $max_counter."users are permitted<br>";
//member counter
$pupil_count_query="SELECT COUNT(*) AS pupil_count 
					FROM tblmembers 
					WHERE member_type=2";
if (!$pupil_count_result=mysqli_query($cxn,$pupil_count_query))
{
	$_SESSION['pupil_count_msg']="Failed to count the pupils :".mysqli_error($cxn)."<br>";
}
else
{
	$_SESSION['pupil_count_msg']="Pupil count retrieved<br>";
}
$row=mysqli_fetch_assoc($pupil_count_result);
extract ($row);
if ($pupil_count>$max_counter)
{
	$_SESSION['max_counter_message']="Maximum number of registered users has already been reached. Contact vendor to extend licence<br>";
	header ("Location:../../index.php");
	exit;
}
//store member
$member_query="INSERT INTO tblmembers 
				(username,forename,lastname,middle_name1,middle_name2,grade,member_type,password)
				VALUES 
				('$username','$forename','$lastname','$middle_name1','$middle_name2',$grade,2,AES_ENCRYPT('$password','9@55w0rd'))";
if (!$member_result=mysqli_query($cxn,$member_query))
{
	$error_message=mysqli_error($cxn);
	$error_number=mysqli_errno($cxn);
	if ($error_number==1062)
	{
		$_SESSION['member_message']="Username <b>$username</b> already exists. Please try a different username<br>";
	}
	else
	{
		$_SESSION['member_message']="Failed to store new user :".$error_message."<br>";
	}
	
	echo "Error number is <b>$error_number</b><br>";
	echo "Error message is :<b>".$error_message."</b><br>";
	header("Location:./registration.php");
	exit;
}
else
{
	$_SESSION['member_message']="User <b>$username</b> registered successfully<br>";
	header ("Location:login.php");
	exit;
}
echo $_SESSION['member_message'];
//SELECT SELECT DATE_ADD('2020-02-29', INTERVAL 1 year);

/*
echo filemtime("webdictionary.txt");
echo "<br>";
echo "Content last changed: ".date("F d Y H:i:s.", filemtime("webdictionary.txt"));
*/
?>