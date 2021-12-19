<?php 
$root_path="../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");

foreach ($_POST AS $key=>$value)
{
	echo "$key = $value<br>";
}

$_SESSION['invalid_fields']='';
$invalids='';
function alpha_match($current_char)  //alphanumeric validation
{
	$current_outcome=preg_match("~[A-Za-z0-9'-]~",$current_char);
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
	if (isset($_POST['btnEditUser']))
	{
		$field_counter=0;
		$invalid_counter=0;
		
		foreach ($_POST AS $key=>$value) //loop through submitted strings
		{
			if ($key!=='btnEditUser')
			{
				echo "$key = $value<br><br>";
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
						echo "Outcome is $current_outcome<br>";
					}
					else if ($key=='forename'||$key=='lastname'||$key=='middle_name1'||$key=='middle_name2')
					{
						$current_outcome=alpha_match($current_char);
						echo "Outcome is $current_outcome<br>";
					}
					if ($current_outcome==0)
					{
						$invalid_counter++;
						$_SESSION['invalid_counter']=$invalid_counter;
						if ($current_char==' ')
						{
							$_SESSION['field_validate']="Space character entered in field <b>$key</b> is illegal<br>";
							//header ("Location:account_edit.php");
							//exit;
						}
						else
						{
							$_SESSION['field_validate']="Invalid character <b>$current_char</b> entered in field <b>$key</b><br>";
							//header ("Location:account_edit.php");
							//exit;
							
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
echo "+++++++++++++++++++++++";
$char_counter=0; $char_count=0;
echo "Starting function operation<br>";
//$invalids=string_outcome($char_counter,$char_count);
string_outcome($char_counter,$char_count);
echo "Function operation completed<br>";
if (isset($_SESSION['invalid_counter']))
{
	if ($_SESSION['invalid_counter']>0)         //invalid fields exist
	{
		$_SESSION['invalid_fields']=$invalids;
		echo "Invalid fields exist<br>";
		header ("Location:account_edit.php");
		exit;
	}
}

{
	echo "No invalid fields<br>";
	
}
echo "*************************<br>";
echo "There are ".$_SESSION['invalid_counter']." invalid fields<br>";
foreach ($_POST as $field =>$value)
{
	//echo "$field = $value<br/>";
	if ($value=="blank" && ($value!="middle_name1"||$value!="middle_name2"))
	{
		$_SESSION['blank_message']="No option selected for field <b>$field</b><br>";
		echo $_SESSION['blank_message'];
		header("Location:./account_edit.php");
		exit;
	}
}


string_outcome($char_counter,$char_count); //validate field input

$forename=strtoupper(trim($_POST['forename']));
$lastname=strtoupper(trim($_POST['lastname']));
$middle_name1=strtoupper(trim($_POST['middle_name1']));
$middle_name2=strtoupper(trim($_POST['middle_name2']));
$grade=(int)$_POST['grade'];
echo "Username is <b>".$_SESSION['username']."</b><br>";
echo "Forename is <b>$forename</b><br>";
echo "Lastname is <b>$lastname</b><br>";
echo "Middlename is <b>$middle_name1</b><br>";
echo "Middlename is <b>$middle_name2</b><br>";
echo "Grade is <b>$grade</b><br>";
if ($_SESSION['source_form']=="pupil_select.php")
{
	$username=$_SESSION['pupil_name'];
}
else
{
	$username=$_SESSION['username'];
}
//--------------------------------------------------------------------

$member_query="UPDATE tblmembers
				SET forename='$forename',
				lastname='$lastname',
				middle_name1='$middle_name1',
				middle_name2='$middle_name2',
				grade=$grade
			WHERE username='".$username."'";
echo $member_query."<br>";
if (!$member_result=mysqli_query($cxn,$member_query))
{
	$_SESSION['member_message']="Failed to store new member: ".mysqli_error($cxn)."<br>";
	header ("Location:../students/student_home.php");
	exit;
}
else
{
	if ($_SESSION['member_type']==2)
	{
		$_SESSION['member_message']="User <b>".$_SESSION['username']."</b> updated<br>";
	header ("Location:../students/student_home.php");
		exit;
	}
	else if ($_SESSION['member_type']==1)
	{
		if ($_SESSION['source_form']=="pupil_select.php")
		{	
			$_SESSION['member_message']="User <b>".$_SESSION['pupil_name']."</b> updated<br>";
		}
		else
		{
			$_SESSION['member_message']="User <b>".$_SESSION['username']."</b> updated<br>";
		}
		header ("Location:../administrators/administrator_home.php");
		exit;
	}
}
echo $_SESSION['member_message'];
?>