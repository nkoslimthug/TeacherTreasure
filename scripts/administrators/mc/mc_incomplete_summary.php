<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
include ("../../functions/draw_table33.php");
include ("../../functions/table_tmp_file_create.php");
include ("../../functions/table_tmp_file_update.php");

if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
	if (isset($_POST['btnAbort']))
	{
		if($_SESSION['guest']==true)
		{
			header ("Location:../../../index.php");
		}
		else
		{
			header ("Location:../administrator_home.php");
		}
		exit;
	}

	/*$subject_name=$_SESSION['subject_name'];
	$subject_code=$_SESSION['subject_code'];
	$grade=$_SESSION['grade'];*/
	
	//count incomplete tests
	//===================================================
	$incomplete_summary_query="SELECT m.test_owner,s.subject_name,m.subject_code,m.grade,m.subject_counter,m.topic_id,m.test_size
								FROM tblmctestsummary m,tblsubjects s
								WHERE s.subject_code=m.subject_code
								AND	m.test_owner='".$_SESSION['username']."'
								AND m.test_status=0";
	echo $incomplete_summary_query."<br>";
	if (!$incomplete_summary_result=mysqli_query($cxn,$incomplete_summary_query))
	{
		$_SESSION['incomplete_summary_message']="Faied to get incomplete tests<br>";
		//header ("Location:../administrator_home.php");
	}
	else
	{
		$tests=[];
		$test_count=0;
		$test_counter=0;
		while ($row=mysqli_fetch_assoc($incomplete_summary_result))
		{
			extract($row);
			echo $test_owner." ".$subject_name." ".$grade." ".$subject_counter."<br>";
			$tests[$test_counter][0]=$test_owner;
			$tests[$test_counter][1]=$subject_name;
			$tests[$test_counter][2]=$topic_id;
			$tests[$test_counter][3]=$grade;
			$tests[$test_counter][4]=$subject_counter;
			$_SESSION['subject_code']=$subject_code;
			//$_SESSION['incomplete_summary_message']="Incomplete tests exist<br>";
			//$test_counter++;
			//$test_count++;
			$_SESSION['max_questions']=$test_size;
		}
		
		
	}	
	//
	
?>
<!doctype html>
<html>
<head>
	<title>
		Multiple Choice Questions
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../../style/college.css" />
</head>
<body>
	<div id="banner" >
		
	</div>
	<div id="slogan_bar" >
		<center><i>eCompanion - It's Just What the Teacher Ordered</i></center>
	</div>
	<div id="message_bar" >
		
		<?php
				
			?>
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner">
			<?php	
				if (isset($_SESSION['question_add_message']))
				{
					echo $_SESSION['question_add_message'];
					unset ($_SESSION['question_add_message']);
				}
				if (isset($_SESSION['initialise_message']))
				{
					echo $_SESSION['initialise_message'];
					unset ($_SESSION['initialise_message']);
				}
				if (isset($_SESSION['max_questions_message']))
				{
					echo $_SESSION['max_questions_message'];
					unset ($_SESSION['max_questions_message']);
				}
				if (isset($_SESSION['test_counter_message']))
				{
					echo $_SESSION['test_counter_message'];
					unset ($_SESSION['test_counter_message']);
				}
				if (isset($_SESSION['summary_close_message']))
				{
					echo $_SESSION['summary_close_message'];
					unset ($_SESSION['summary_close_message']);
				}
				if (isset($_SESSION['initialise_query']))
				{
					echo $_SESSION['initialise_query'];
					unset ($_SESSION['initialise_query']);
				}
			?>
		</div>
		<form id="form1" name="form1" method="post" action="mc_incomplete_process.php">
					<?php
						echo "Test counter is $test_counter<br>";
						echo "Test count is $test_count<br>";
						$test_counter=0;
						reset ($tests);
						if ($test_counter==0)
						//while ($test_counter<=$test_count)
						{
							$tests_key=$tests[$test_counter][0]."+".$tests[$test_counter][1]."+".$tests[$test_counter][2]."+".$tests[$test_counter][3]."+".$tests[$test_counter][4];
							echo $tests_key."<br>";
						}
					?>
					
					
					<hr/>
					<table >
						<tr>
							<td><input style="background-color: green; font-weight: bolder;" type="submit" name="<?php echo 'C+'.$tests_key; ?>" id="<?php echo 'C+'.$tests_key; ?>" value="Continue" /></td>
							<td><input style="background-color: red; font-weight: bolder;" type="submit" name="<?php echo 'D+'.$tests_key; ?>" id="<?php echo 'C+'.$tests_key; ?>" value="Delete" /></td>
						</tr>
							
						<tr>
							<td>&nbsp;</td>
						</tr>
					
					</table>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					
				</form>
	</div>
	
	<div id="footer" >
		<font="brown"></font>
	</div>
</body>
</html>
