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
	foreach ($_POST AS $key => $value)
	{
		echo "$key = $value<br>";
	}
//session_start();
//$_SESSION['question_number']=120;
//$_SESSION['question_counter']=120;
//$_SESSION['batch_size']=32;

/*$question_counter=10;
$test_step=5;
$_SESSION['test_cap']=20;
$_SESSION['story_id']=0;
$_SESSION['image_id']=0;
$_SESSION['story_title']='';
$_SESSION['story_content']='';*/

//count incomplete tests
	$incomplete_summ_count="SELECT COUNT(*) incomplete_summary
								FROM tblmctestsummary
								WHERE test_owner='".$_SESSION['username']."'
								AND test_status=0";
	//echo $incomplete_summ_count."<br>";
	if (!$incomplete_summ_result=mysqli_query($cxn,$incomplete_summ_count))
	{
		$_SESSION['incomplete_summ_messsage']="Failed to count incomplete tests :".mysqli_error($cxn)."<br>";
		//header ("Location:./administrator_home.php");
	}
	else
	{
		$row=mysqli_fetch_assoc($incomplete_summ_result);
		extract ($row);
		if ($incomplete_summary>0)
		{
			//display incomplete tests
			echo $incomplete_summary." incomplete tests exist for user ".$_SESSION['username']."<br>";
			header ("Location:./mc/mc_incomplete_summary.php");    //retrieve incomplete tests
		}
		else  //no incomplete tests
		{
			//proceed to create new test
			$_SESSION['new_test_message']="Click <b>Submit Request</b> to create a new test<br>"; 			
		}
	}
?>
<!doctype html>
<html>
<head>
	<title>
		Cyber Home
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
		
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			<ul class="nav">
					<li><a href='test_select_frm.php'>New Test</a></li>
					<li><a href='my_scheduled_tests.php'>View Tests</a></li>
					<li><a href='administrator_home.php'>Home</a></li>
					<li><a href="<?php echo $root_path;?>/scripts/general/logout.php">Logout</a></li>
     			</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner"> 
		<?php
			
			if (isset($_SESSION['blank_subject_message']))
			{
				echo $_SESSION['blank_subject_message'];
				unset ($_SESSION['blank_subject_message']);
			}
			if (isset($_SESSION['blank_paper_message']))
			{
				echo $_SESSION['blank_paper_message'];
				unset ($_SESSION['blank_paper_message']);
			}
			/*if (isset($_SESSION['new_test_message']))
			{
				echo $_SESSION['new_test_message'];
				unset ($_SESSION['new_test_message']);
			}*/
			
		?>
		
		</div>
		<!--<form id="form1" name="form1" method="post" action="testtopic_select_frm.php">  -->
								
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>