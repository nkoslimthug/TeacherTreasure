<?php
$root_path="../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
foreach ($_SESSION AS $key=>$value)
{
	echo "$key = $value<br>";
}
echo session_id();

						
//if (isset($_POST['grade_confirm'])) //grade adjustment sought

	$grade_confirm_query="UPDATE tblmembers SET grade={$_POST['grade']} WHERE username='".$_SESSION['username']."'";
	echo $grade_confirm_query."<br>";
	if (!$grade_confirm_result=mysqli_query($cxn,$grade_confirm_query))
	{
		$_SESSION['grade_confirm_message']="<br>Failed to update grade: ".mysqli_error($cxn)."<br>";
	}
	else
	{
		$_SESSION['grade_confirm_message']="<br>Grade updated to <b>Grade ".$_POST['grade']."</b><br>";
	}
	echo $_SESSION['grade_confirm_message'];
	unset ($_POST['grade_confirm']);
	unset ($_SESSION['year_diff']);

header ("Location:../students/student_home.php");
exit;
?>