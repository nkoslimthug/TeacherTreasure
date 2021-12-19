<?php
$root_path="../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
include ("../functions/draw_table33.php");
//include ("../functions/draw_table_grade.php");
//Initialise counters
/*if (isset($_SESSION['question_counter']))
{*/
	$_SESSION['question_counter']=0;
	$_SESSION['source_form']="student_home.php";
/*}*/
unset ($_SESSION['question_type']);
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
	//Unset values
			unset ($_SESSION['question_number']);
			unset ($_SESSION['question_counter']);
			unset ($_SESSION['batch_size']);
			unset ($_SESSION['story_id']);
			unset ($_SESSION['image_id']);
			unset ($_SESSION['story_title']);
			unset ($_SESSION['story_content']); 
			unset ($_SESSION['subject_name']);
			unset ($_SESSION['grade']);
			unset ($_SESSION['paper_type']);
			unset ($_SESSION['subject_code']);
			unset ($_SESSION['question_score']);
			unset ($_SESSION['topic_name']);
			unset ($_SESSION['paper_description']);
			unset ($_SESSION['topic_id']);
			unset ($_SESSION['start_timestamp']);
			unset ($_SESSION['start_time']);
			unset ($_SESSION['instruction']);
			unset ($_SESSION['true_answer']);
			unset ($_SESSION['table_based']);
			unset ($_SESSION['question_complete']);
			unset ($_SESSION['answer_T']);
			unset ($_SESSION['option1']);
			unset ($_SESSION['option2']);
			unset ($_SESSION['option3']);
			unset ($_SESSION['answered_counter']);
			unset ($_SESSION['names']);
			unset ($_SESSION['section_counter']);
			unset ($_SESSION['section_count']);
			unset ($_SESSION['section_count']);
			unset ($_SESSION['quota_tracker']);
			unset ($_SESSION['section_quota']);
			unset ($_SESSION['section_name']);
			unset ($_SESSION['answered_strings']); 
			unset ($_SESSION['header_path']); 
			unset ($_SESSION['images_path']); 
			unset ($_SESSION['choices0']); 
			unset ($_SESSION['choices1']);
			unset ($_SESSION['choices2']);
			unset ($_SESSION['choices3']);
			unset ($_SESSION['correct_answer']);
			unset ($_SESSION['verdict']);
			unset ($_SESSION['submitted_answer']);
			unset ($_SESSION['questions_counter']);
			unset ($_SESSION['questions_count']); 
			unset ($_SESSION['teststart_message']);
			unset ($_SESSION['start_logtime']);
			unset ($_SESSION['complete']);
			unset ($_SESSION['dataload_message']);
			unset ($_SESSION['paper_size']);
			unset ($_SESSION['sections']);
			unset ($_SESSION['section_count']);
			unset ($_SESSION['completed_message']);
			unset ($_SESSION['testkill_query_message']);
			
			//unset ($_SESSION(['sections']);
		//	unset ();
	
	//echo "After unsetting ....<br>";
	foreach($_SESSION as $key=>$value)
	{
		//echo "$key = $value<br>";
	}
	//echo "<br>";
	if (isset($_SESSION['source_form']))
	{
		if ($_SESSION['source_form']=="login.php")
		{
			
			$last_login_query="UPDATE tblmembers
								SET last_login=now()
								WHERE username='".$_SESSION['username']."'";
			if (!$last_login_result=mysqli_query($cxn,$last_login_query))
			{
				$_SESSION['last_login_msg']="Failed to update time<br>";
			}
			else
			{
				$_SESSION['last_login_msg']="Login time updated<br>";
			}
			//echo $_SESSION['last_login_msg'];
		}
	}
	
	//Delete unconfirmed questions from log
	$unconfirmed_query="DELETE FROM tblmctestlog 
						WHERE username='".$_SESSION['username']."'
						AND complete!=1 OR question_counter IS NULL";
	if (!$unconfirmed_result=mysqli_query($cxn,$unconfirmed_query))
	{
		$_SESSION['unconfirmed_message']="Failed to clear unconfirmed questions<br>";
	}
	else
	{
		$_SESSION['unconfirmed_message']="Unconfirmed questions cleared<br>";
	}
	//echo $_SESSION['unconfirmed_message'];
	
	//Delete incomplete topic_test
	$topic_query="DELETE FROM tblmctestlog 
				WHERE test_type='MC'
				AND username='".$_SESSION['username']."'
				HAVING COUNT(*)<10
				GROUP BY subject_code,topic_id,username,start_time ";
	//echo $topic_query."<br>";
	if (!$topic_result=mysqli_query($cxn,$topic_query))
	{
		$topic_message=" ".mysqli_error($cxn)."<br>";
	}
	else
	{
		$topic_message="Deleted<br>";
	}
	
	//Delete incomplete general test
	$test_query="DELETE FROM tblmctestlog 
				WHERE test_type IN ('General Test')
				AND username='".$_SESSION['username']."'
				HAVING COUNT(*)<10
				GROUP BY subject_code,topic_id,username,start_time";
	//echo $test_query."<br>";
	if (!$test_result=mysqli_query($cxn,$test_query))
	{
		$test_message="Deletion of incomplete tests failed:".mysqli_error($cxn)."<br>";
	}
	else
	{
		$test_message="Deleted<br>";
	}

	
?>
<!doctype html>
<html>
<head>
	<title>
		Student Home
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
			<ul id="nav">
                	<li><a href="exercise_select_frm.php">Test By Topic</a></li>
					<li><a href="dynamic_test.php">General Test</a></li>
					<li><a href="scheduled_tests.php">Scheduled Test</a></li>
					<li><a href="scores.php" >My Reports</a></li>
                	<li><a href="<?php echo $root_path;?>scripts/general/account_edit.php?id=2&u_id=<?php echo $_SESSION['username'];?>">My Account</a></li>
					<li><a href="<?php echo $root_path;?>scripts/general/logout.php">Logout</a></li>
				</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner">  
		<?php  
			if (isset($_SESSION['member_message']))
			{
				echo $_SESSION['member_message'];
				unset ($_SESSION['member_message']);
			}
			/*if (isset($_SESSION['login_time_message']))
			{
				echo $_SESSION['login_time_message'];
				unset ($_SESSION['login_time_message']);
			}*/
			if (isset($_SESSION['grade_confirm_message']))
			{
				echo $_SESSION['grade_confirm_message'];
				unset ($_SESSION['grade_confirm_message']);
			}
			if (isset($_SESSION['first_day_message']))
			{
				echo $_SESSION['first_day_message'];
				unset ($_SESSION['first_day_message']);
			}
			if (isset($_SESSION['array_msg']))
			{
				echo $_SESSION['array_msg'];
				unset ($_SESSION['array_msg']);
			}
					
			unset ($_SESSION['question_type']);
			if ($_SESSION['logged_in']!="T")
			{
				session_destroy();
				$_SESSION['login_message']="Please log in before accessing that page<br>";
				header ("Location:../../index.php");
				
			}


			if (isset($_SESSION['source_form']))
			{
				if ($_SESSION['source_form']=="login.php")
				{
					
					$last_login_query="UPDATE tblmembers
										SET last_login=now()
										WHERE username='".$_SESSION['username']."'";
					if (!$last_login_result=mysqli_query($cxn,$last_login_query))
					{
						$_SESSION['last_login_msg']="Failed to update time<br>";
					}
					else
					{
						$_SESSION['last_login_msg']="Login time updated<br>";
					}
					//echo $_SESSION['last_login_msg'];
				}
			}
			unset ($_SESSION['question_type']);
			if ($_SESSION['logged_in']!="T")
			{
				session_destroy();
				$_SESSION['login_message']="Please log in before accessing that page<br>";
				header ("Location:../../index.php");
				
			}
			if (isset($_SESSION['source_form']))
			{
				if ($_SESSION['source_form']=="login.php")
				{
					$last_login_query="UPDATE tblmembers
										SET last_login=now()
										WHERE username='".$_SESSION['username']."'";
					if (!$last_login_result=mysqli_query($cxn,$last_login_query))
					{
						$_SESSION['last_login_msg']="Failed to update time<br>";
					}
					else
					{
						$_SESSION['last_login_msg']="Login time updated<br>";
					}
					//echo $_SESSION['last_login_msg'];
					unset ($_SESSION['last_login_msg']);
				}
			}
			
		?>
		</div>
			<div id="user_banner">
				<?php
					if (isset($_SESSION['welcome_msg']))
					{
						echo $_SESSION['welcome_msg'];
						unset ($_SESSION['welcome_msg']);
					}
					else 
					{
						echo $_SESSION['fullname']."<br>";
					}			
				?>
			</div>
			<form autocomplete='off' id='grade_form' name='grade_form' method='post' action='grade_confirm_process.php'>
			<?php
			//prepare grades array
			$grades=[];
			$grade_counter=0;
			$selected="";
			$grades[$grade_counter]=$_SESSION['student_grade'];
					
				if (isset($_SESSION['year_diff']))  
				if ($_SESSION['year_diff']==1) //first login in current year
				{
					
					echo "<table border='0'>";
					echo "<tr>";
						echo "<td style='align:center;width:40%'>&nbsp;</td>";
						echo "<td >&nbsp; </td>";
						echo "<td >&nbsp;</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td style='align:center;width:40%'>&nbsp;</td>";
						echo "<td >&nbsp; </td>";
						echo "<td >&nbsp;</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td style='align:center;'>&nbsp;</td>";
						echo "<td>This is my first login in the year </td>";
						echo "<td><b>".$_SESSION['new_year']."</b></td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td style='align:center;'>&nbsp;</td>";
						echo "<td>I am now in grade</td>";
						echo "<td><select name='grade' id='grade' selected=$selected >";
						$grades=[];
						$grade_counter=0;
						$selected="";
						if ($_SESSION['student_grade']<7)
						{
							$next_grade=$_SESSION['student_grade']+1;
						}
						else if ($_SESSION['student_grade']==7)
						{
							$next_grade=$_SESSION['student_grade'];
						}
						while ($grade_counter<=7)
						{
							$grades[$grade_counter]=$grade_counter;
							echo "<option value='$grade_counter' ";
							if ($grade_counter==$next_grade)
							{
								echo "selected='selected'";
							}
							echo ">$grade_counter</option>";
							$grade_counter++;
						}	
					echo "</select></td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td style='align:center;'>&nbsp;</td>";
						echo "<td><input type='submit' name='grade_confirm' id='grade_confirm' value='Confirm Grade'/> </td>";
						echo "<td>&nbsp;</td>";
					echo "</tr>";
					echo "</table>";
				}
				//echo $_SESSION['year_diff'];
						
			?>
			</form>
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>