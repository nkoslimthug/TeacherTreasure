<?php
//session_start();
include ("../../config/sungunura.php");
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
	
	function subject_all_counter($cxn,$story_id)
	{
		include ("../functions/sunungura.php");
		$_SESSION['subject_all_query']="SELECT COUNT(*) as summary_entries
										FROM tblmctestsummary
										WHERE test_owner='".$_SESSION['username']."'
										AND subject_code=".$_SESSION['subject_code']."
										AND topic_id=".$_SESSION['topic_id']."
										AND grade=".$_SESSION['grade'];
		echo $_SESSION['subject_all_query']."<br>";
		if (!$subject_all_result=mysqli_query($cxn,$_SESSION['subject_all_query']))
		{
			$_SESSION['subject_count_message']="Failed to count all subjects :".mysqli_error($cxn)."<br>";
		}
		else
		{
			$row=mysqli_fetch_assoc($subject_all_result);
			extract ($row);
			echo $summary_entries;
			return $summary_entries;
		}
	}
	
	function new_summary_entry($cxn)
	{
		include ("../functions/sunungura.php");
		$story_id=0;
		$subject_counter=subject_all_counter($cxn,$story_id);
		$subject_counter++;
		$_SESSION['initialise_query']="INSERT INTO tblmctestsummary
							(test_owner,subject_code,topic_id,grade,subject_counter,test_size,start_date)
							VALUES
							('".$_SESSION['username']."',"
							 .$_SESSION['subject_code'].","
							 .$_SESSION['topic_id'].","
							 .$_SESSION['grade'].","
							 .$subject_counter.","
							 .$_SESSION['max_questions'].",
							 now())";
		echo $_SESSION['initialise_query']."<br>";
		if (!$initialise_result=mysqli_query($cxn,$_SESSION['initialise_query']))
		{
			$_SESSION['initialise_message']="Failed to initialise test: ".mysqli_error($cxn)."<br>";
			//header ("Location:../administrator_home.php");	
			//exit;
		}
		else
		{
			$_SESSION['initialise_message']="Test initialised<br><br>";
		}
		echo $_SESSION['initialise_message']."<br>";
	}
	
	function close_test_summary($cxn,$subject_counter)
	{
		include ("../../functions/sunungura.php");
		$subject_counter=subject_all_counter($cxn);
		$_SESSION['subject_counter_summary_value']="Subject counter summary value is ".$subject_counter."<br>";
		$_SESSION['summary_close_query']="UPDATE tblmctestsummary
							 SET test_status=1,
							 posted_date=now()
							 WHERE test_owner='".$_SESSION['username']."'
							 AND subject_code=".$_SESSION['subject_code']."
							 AND topic_id=".$_SESSION['topic_id']."
							 AND subject_counter=".$subject_counter."
					    	AND grade=".$_SESSION['grade'];
		echo $_SESSION['summary_close_query']."<br>";
		echo $_SESSION['subject_counter_summary_value'];
							 
		if (!$summary_close_result=mysqli_query($cxn,$_SESSION['summary_close_query']))
		{
			$_SESSION['summary_close_message']="Failed to close test summary: ".mysqli_error($cxn)."<br>";
			header ("Location:./administrator_home.php");	
			exit;
		}
		else
		{
			$_SESSION['summary_close_message']="Test summary successfully closed<br>";
			//header ("Location:./administrator_home.php");	
			//exit;
		}
	}
	
	function close_test_detail($cxn)
	{
		include ("../../functions/sunungura.php");
		$subject_counter=subject_all_counter($cxn);
		$_SESSION['subject_counter_detail_value']="Subject counter detail value is ".$subject_counter."<br>";
		$_SESSION['detail_close_query']="UPDATE tblmctestdetail
							 SET test_status=1
							 WHERE test_owner='".$_SESSION['username']."'
							 AND subject_code=".$_SESSION['subject_code']."
							 AND topic_id=".$_SESSION['topic_id']."
							 AND subject_counter=".$subject_counter."
					    	AND grade=".$_SESSION['grade'];
		echo $_SESSION['detail_close_query']."<br>";
		echo $_SESSION['subject_counter_detail_value'];
							 
		if (!$summary_close_result=mysqli_query($cxn,$_SESSION['detail_close_query']))
		{
			$_SESSION['detail_close_message']="Failed to close test detail: ".mysqli_error($cxn)."<br>";
			header ("Location:./administrator_home.php");	
			exit;
		}
		else
		{
			$_SESSION['detail_close_message']="Test detail successfully closed<br>";
		}
		echo $_SESSION['detail_close_message'];
	}
	
	if (!isset($_POST['choose_story']))
	{
		$story_selector="SELECT story_id,story_title
						FROM tblstories 
						WHERE subject_code=".$_SESSION['subject_code'];
		//echo $story_selector."<br>"; 
		$story_result=mysqli_query($cxn,$story_selector);
		while ($row=mysqli_fetch_assoc($story_result))
		{
			extract ($row);
			echo "Story title is <b>".$story_title."</b><br>";
			$_SESSION['story_id']=$story_id;
		}
	}
	else   //story selected
	{
		//check if story has already been scheduled
		/*$existing_stories_query="SELECT DISTINCT subject_code,story_id,grade
								FROM tblmctestdetail";
		if (!$existing_stories_result=mysqli_query($cxn,$existing_stories_query))
		{
			$_SESSION['existing_stories_message']="Failed to retrieve stories :".mysqli_error($cxn)."<br>";
		}
		else
		{
			while ($row=mysqli_fetch_assoc($existing_stories_result))
			{
				if ($subject_id==$_SESSION['subject_code'] && $story_id==$_SESSION['story_id'] && $grade==$_SESSION['grade'])
				{
					$_SESSION['existing_story_message']="Story has already been scheduled for grade ".$_SESSION['grade']."<br>";
					header ("Location:./administrator_home.php");	
					exit;
				}
			}
		}*/
		$pass_counter=0;
		$question_counter=1;
		foreach ($_POST as $key => $value)
		{
			//echo $key = $value."<br>";
			$_SESSION['story_query']="SELECT * FROM tblmcquestions 
							WHERE subject_code=".$_SESSION['subject_code']." 
							AND lower_grade<=".$_SESSION['grade']."
							AND upper_grade>=".$_SESSION['grade']."
							AND story_id=(SELECT story_id FROM tblstories WHERE story_title='".$_POST['story_name']."')";
			echo $_SESSION['story_query'];
			if (!$story_result=mysqli_query($cxn,$_SESSION['story_query']))
			{
				$_SESSION['story_message']="Failed to retrieve story questions: ".mysqli_error($cxn)."<br>";
			}
			
			$test_status=0;
			if ($pass_counter==0)
			{
				new_summary_entry($cxn);											//load test summary
				$pass_counter++;
				while ($row=mysqli_fetch_assoc($story_result))			//add questions
				{
					extract ($row);
					$subject_counter=subject_all_counter($cxn,$story_id);
					$add_question_query="INSERT INTO tblmctestdetail
										(test_owner,subject_code,topic_id,grade,question_number,question_counter,instruction,test_status,subject_counter,story_id,image_id)
										VALUES
										('".$_SESSION['username']."',
										  ".$_SESSION['subject_code'].",
										  ".$topic_id.",
										  ".$_SESSION['grade'].",
										  ".$question_number.",
										  ".$question_counter.",
										  '".$instruction."',
										  ".$test_status.",
										  ".$subject_counter.",
										  ".$story_id.",
										  ".$image_id."
										 )";
					echo $add_question_query."<br>";
					if (!$add_question_result=mysqli_query($cxn,$add_question_query))					 
					{
						$_SESSION['add_question_message']="Failed to add story question:".mysqli_error($cxn)."<br>";
						//header ("Location:./administrator_home.php");
					}
					else
					{
						$_SESSION['add_question_message']="Question successfully added<br>";
					}
					$question_counter++;
				}
			}
			close_test_detail($cxn);
			close_test_summary($cxn);
			header ("Location:./administrator_home.php");	
			exit;
			//echo $add_question_query."<br>";
			//echo $_SESSION['add_question_message'];
			//echo $_SESSION['story_query']."<br>";	
			//header ("Location:./mc/mc_maketest.php");
			
		}
	}
?>
<!doctype html>
<html>
<head>
	<title>
		Pupil Selection
	</title>
	<link rel="stylesheet"
			type="text/css"
			<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
</head>
<body>
	<div id="banner" style="text-align:center";>
	
	</div>
	<div id="slogan_bar" style="background:white;color:purple;font-style:bold;" >
		<i>eCompanion - It's Just What the Teacher Ordered</i>
	</div>
	<div id="index_left_sidebar" style="background:white";>
		<br><br><br>
		<div id="menu">
			<ul>
				<li><a href="../administrators/administrator_home.php" >Back</a></li>
			</ul>
		</div>	
	</div>
	<div id="content" style="color:purple";>
			<div id="message">
					<?php 
						if (isset($_SESSION['person_insert_message']))
						{
							echo $_SESSION['person_insert_message']."<br/>";
							unset ($_SESSION['person_insert_message']);
						}
					?>
				</div>
				<form method="post" id="story_select.php" action="story_select.php">
					<table width="50%" border="2">
						<th id="self_reg_head" colspan="4">Story Selection</th>
						<tr>
							<td colspan="4">&nbsp</td>
						</tr>
						<tr>
							<td width="20%">Story Title:</td>
							<td width="30%">
								<select name="story_name" id="story_select" >
									<option value="">Select desired story</option>
									<?php
										$story_query="SELECT story_title
														FROM tblstories 
														WHERE subject_code=".$_SESSION['subject_code'];
										if ($story_result=mysqli_query($cxn,$story_query))
										{
											while($row=mysqli_fetch_assoc($story_result))
											{
												extract ($row);
												echo "<option value='$story_title'>$story_title</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="4">&nbsp;</td>
						</tr>
						
						<tr>
							<td><input  type="submit" name="choose_story" name="choose_story" value="Submit Story" /></td>
						</tr>
					</table>
				</form>
		
	</div>
	<div id="index_right_sidebar" style="background:white";>

	</div>
	<div id="footer">

	</div>
	
</body>
</html>