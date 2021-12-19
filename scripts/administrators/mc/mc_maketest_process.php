<?php
$root_path="../../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
//include ("../../../tmp/table.php");
//include ("../../functions/draw_table33.php");

//include ("./mc_table_dump.php");
//include ("./mc_table_load.php");
//echo "Pass counter on entry is ".$_SESSION['pass_counter']."<br>";
	function subject_all_counter($cxn)
	{
		$_SESSION['subject_all_query']="SELECT COUNT(*) as summary_entries
										FROM tblmctestsummary
										WHERE test_owner='".$_SESSION['username']."'
										AND subject_code=".$_SESSION['subject_code']."
										AND grade=".$_SESSION['grade'];
		echo $_SESSION['subject_all_query']."<br>";
		if (!$subject_all_result=mysqli_query($cxn,$_SESSION['subject_all_query']))
		{
			$_SESSION[$subject_count_message]="Failed to count all subjects :".mysqli_error($cxn)."<br>";
		}
		else
		{
			$row=mysqli_fetch_assoc($subject_all_result);
			extract ($row);
			echo $summary_entries;
			return $summary_entries;
		}
	}

	function subject_counter($cxn)
	{
		//$subject_count=0;
		include ("../../functions/sunungura.php");
		$_SESSION['subject_count_query']="SELECT subject_counter
								FROM tblmctestsummary
								WHERE test_owner='".$_SESSION['username']."'
								AND subject_code=".$_SESSION['subject_code']."
								AND grade=".$_SESSION['grade'];
		echo $_SESSION['subject_count_query']."<br>";
		if (!$subject_count_result=mysqli_query($cxn,$_SESSION['subject_count_query']))
		{
			$_SESSION[$subject_count_message]="Failed to get subject count".mysqli_error($cxn)."<br>";
			//header ("Location:../administrator_home.php");	
			//exit;
		}
		else
		{
			$row=mysqli_fetch_assoc($subject_count_result);
			extract ($row);
			//$subject_count++; //counter for next test
			return $subject_counter;
			$_SESSION['subject_count_message']="Subject count is ".$subject_count."<br>";
		}
	}
	
	
	function test_size($cxn)
	{
		include ("../../functions/sunungura.php");
		$test_size_query="SELECT test_size 
					   	FROM tblmctestsummary
						WHERE test_owner='".$_SESSION['username']."'
						AND subject_code=".$_SESSION['subject_code']."
						AND grade=".$_SESSION['grade']."
						AND test_status=0";
		echo $test_size_query."<br>";
		if (!$test_size_result=mysqli_query($cxn,$test_size_query))
		{
			$_SESSION['test_size_message']="Failed to get test size".mysqli_error($cxn)."<br>";
			echo $_SESSION['test_size_message'];
			//header ("Location:../administrator_home.php");
			//exit;
		}
		else
		{
			$row=mysqli_fetch_assoc($test_size_result);
			extract ($row);
			return $test_size;
		}
	}
	
	function new_summary_entry($cxn)
	{
		include ("../../functions/sunungura.php");
		$subject_counter=subject_all_counter($cxn);
		$subject_counter++;
		$_SESSION['initialise_query']="INSERT INTO tblmctestsummary
							(test_owner,
							subject_code,
							grade,
							subject_counter,
							topic_id,
							test_size,
							start_date,
							test_deadline)
							VALUES
							('".$_SESSION['username']."',"
							 .$_SESSION['subject_code'].","
							 .$_SESSION['grade'].","
							 .$subject_counter.","
							 .$_SESSION['topic_id'].","
							 .$_SESSION['max_questions'].",
							 now(),'"
							 .$_SESSION['deadline']."')";
		echo $_SESSION['initialise_query']."<br>";
		if (!$initialise_result=mysqli_query($cxn,$_SESSION['initialise_query']))
		{
			$_SESSION['initialise_message']="Failed to initialise test: ".mysqli_error($cxn)."<br>";
			header ("Location:../administrator_home.php");	
			exit;
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
							 AND subject_counter=".$subject_counter."
					    	AND grade=".$_SESSION['grade'];
		echo $_SESSION['summary_close_query']."<br>";
		echo $_SESSION['subject_counter_summary_value'];
							 
		if (!$summary_close_result=mysqli_query($cxn,$_SESSION['summary_close_query']))
		{
			$_SESSION['summary_close_message']="Failed to close test summary: ".mysqli_error($cxn)."<br>";
			header ("Location:../administrator_home.php");	
			exit;
		}
		else
		{
			$_SESSION['summary_close_message']="Test summary successfully closed<br>";
			header ("Location:../administrator_home.php");	
			exit;
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
		}
		else
		{
			$_SESSION['detail_close_message']="Test detail successfully closed<br>";
		}
		echo $_SESSION['detail_close_message'];
	}
	
	//add question
	function add_question($cxn)
	{
		include ("../../functions/sunungura.php");		
		$subject_counter=subject_all_counter($cxn);
		//$subject_counter++;
		$username=$_SESSION['username'];
		$subject_code=$_SESSION['subject_code'];
		$topic_id=$_SESSION['topic_id'];
		$grade=$_SESSION['grade'];
		$question_number=$_SESSION['question_number'];
		$question_counter=$_SESSION['question_counter'];
		$test_counter=$_SESSION['test_counter'];
		$instruction=$_SESSION['instruction'];
		//$subject_counter=$subject_counter($cxn);
		
		$_SESSION['question_add_query']="INSERT INTO tblmctestdetail
						(test_owner,subject_code,topic_id,grade,question_number,question_counter,instruction,subject_counter)
						VALUES
						('$username',
						$subject_code,
						$topic_id,
						$grade,
						$question_number,
						$test_counter,
						'$instruction',
						$subject_counter)";
		echo $_SESSION['question_add_query']."<br>";
		if (!$question_add_result=mysqli_query($cxn,$_SESSION['question_add_query']))
		{
			$errno=mysqli_errno($cxn);
			if ($errno==1062)
			{
				$_SESSION['errno']=1062;
				
			}
			$_SESSION['question_add_message']="Failed to add question to test :".mysqli_error($cxn)."   ".$errno."<br>";
			$_SESSION['test_counter']--;
			//header ("Location:../administrator_home.php");	
		}
		else
		{
			$_SESSION['question_add_message']="Question added to test<br>";
			//header ("Location:./mc_maketest.php");
			//exit;
		}
		echo $_SESSION['question_add_message'];
	}
	
	if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		//header ("Location:../../index.php");
	}
	foreach ($_POST AS $key=>$value)
	{
		echo "$key = $value<br>";
	}
	
	if (isset($_POST['btnAbort']))				//exercise aborted
	{
		$_SESSION['abort_message']="Question Selection Aborted<br>";
		header ("Location:../administrator_home.php");
		exit;
	}
	
	if (isset($_POST['btnSkip']))				//Question Skipped
	{
		//move on
		header ("Location:./mc_maketest.php");
		exit;
	}
	

	
	//check for incomplete tests
	
	if (isset($_POST['btnAccept']))				//Test complete
	{
		//if ($_POST['btnDone']=='Done')
		echo "Test is done<br>";//seal test
		if ($_SESSION['pass_counter']==0)
		{
			//check for incomplete tests on this subject by this user
			$open_testsummary_query="SELECT COUNT(*) AS summary_test_counter
									 FROM tblmctestsummary
									 WHERE test_owner='".$_SESSION['username']."'
									 AND subject_code=".$_SESSION['subject_code']."
									 AND grade=".$_SESSION['grade']."
									 AND test_status=0";
			echo $open_testsummary_query."<br>";
			if (!$open_testsummary_result=mysqli_query($cxn,$open_testsummary_query))
			{
				$_SESSION['summary_test_err_msg']="Failed to get open_test summary count :".mysqli_error($cxn)."<br>";
				header ("Location:../administrator_home.php");
			}
			else
			{
				$row=mysqli_fetch_assoc($open_testsummary_result);
				extract ($row);
				$_SESSION['summary_test_counter']=$summary_test_counter;
				$_SESSION['open_testsummary_message']="There are ".$_SESSION['summary_test_counter']." open tests in summary<br>";	
				echo $_SESSION['open_testsummary_message'];
			}
						
			$open_testdetail_query="SELECT COUNT(*) AS detail_test_counter
									FROM tblmctestdetail
									WHERE test_owner='".$_SESSION['username']."'
									AND subject_code=".$_SESSION['subject_code']."
									AND topic_id=".$_SESSION['topic_id']."
									AND grade=".$_SESSION['grade']."
									AND test_status=0";
			echo $open_testdetail_query."<br>";
			if (!$open_testdetail_result=mysqli_query($cxn,$open_testdetail_query))     //error
			{
				$_SESSION['detail_test_err_msg']="Failed to get open test count :".mysqli_error($cxn)."<br>";
				header ("Location:../administrator_home.php");
			}
			else        //counter retrieved
			{
				$row=mysqli_fetch_assoc($open_testdetail_result);
				extract ($row);
				$_SESSION['detail_test_counter']=$detail_test_counter;
				$_SESSION['open_testdetail_message']="There are ".$_SESSION['detail_test_counter']." open questions in detail<br>";
				echo $_SESSION['open_testdetail_message'];
			}
			
			if ($_SESSION['summary_test_counter']==0 && $_SESSION['detail_test_counter']==0) //no pre-existing summary nor detail test for specified paramaters - start new test
			{
				//new_summary_entry
				echo "summary to detail is zero-zero<br>";
				new_summary_entry($cxn);
				
				//load question
				//add_question();
			}
			if ($_SESSION['summary_test_counter']==0 && $_SESSION['detail_test_counter']!=0)
			{
				if ($_SESSION['max_questions']==$_SESSION['detail_test_counter'])   //test is complete - create summary  entry and close
				{
					echo "summary to detail is zero-one<br>";
					//close test detail
					close_test_detail($cxn);
					//close test summary
					close_test_summary($cxn);
					//new summary entry
					new_summary_entry($cxn);
				}
				else  if ($_SESSION['max_questions']<$_SESSION['detail_test_counter'])    // create summary entry and continue capturing 
				{
					//new summary entry
					new_summary_entry($cxn);
				}
				else   //inconsistent status
				{
						
				}
			}
			if ($_SESSION['summary_test_counter']!=1 && $_SESSION['detail_test_counter']!=0)
			{
				$test_size=test_size($cxn);
				$_SESSION['max_questions']=$test_size;
				if ($test_size==$_SESSION['detail_test_counter'])   //test is complete -  close it
				{
					echo "summary to detail is one-zero";
					//close test detail
					close_test_detail($cxn);
					//close test summary
					close_test_summary($cxn);	
				}
				else  if ($test_size<$_SESSION['detail_test_counter'])    // continue capturing test
				{
					
				}
				else   //inconsistent status
				{
						
				}
			}
			if ($_SESSION['summary_test_counter']==1 && $_SESSION['detail_test_counter']=0)  //test summary exists no questions - start capturing
			{
				
			}
		}
		$_SESSION['test_counter']++;
		$_SESSION['pass_counter']++;
		//$_SESSION['session_pass_message']="This is pass number ".$_SESSION['pass_counter']."<br>";
		add_question($cxn);
		if ($_SESSION['test_counter']==$_SESSION['max_questions'])
		{
			//accept to End
			//close test detail
			close_test_detail($cxn);
			//close test summary
			close_test_summary($cxn);
			header ("Location:../administrator_home.php");	
		}
		else
		{
			$_SESSION['test_counter_message']="Detail test counter is ".$_SESSION['detail_test_counter']."<br>";
			$_SESSION['max_questions_message']="Maximum desired questions is ".$_SESSION['max_questions']."<br>";
			echo $_SESSION['test_counter_message'];
			echo $_SESSION['max_questions_message'];
			$_SESSION['detail_test_counter']++;  //advance question counter
			header ("Location:./mc_maketest.php");
			exit;
		}
	}
		
?>
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
			?>
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner">  </div>
		<form id="form1" name="form1" method="post" action="mc_maketest.php">
					
					<hr/>
					<table >
						<tr>
							<td><input style="background-color: green; font-weight: bolder;" type="submit" name="next_question" id="next_question" value="Next" /></td>
							<!--<td><input style="background-color: red; font-weight: bolder;" type="submit" name="btnSkip" id="btnSkip" value="Skip" /></td> -->
						</tr>
							
						<tr>
							<td>&nbsp;</td>
						</tr>
						
						<tr>
						    <!--	<td><input style="background-color: red; font-weight: bolder;" type="submit" name="btnAbort" id="btnAbort" value="Abort Topic" /></td>-->
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
						<td>&nbsp;</td>
						</tr>
						<tr>						
							<td>&nbsp;</td>
						</tr>
						<tr>
							<!-- <td><input style="background-color: green; font-weight: bolder;" type="submit" name="btnDone" id="btnDone" value="Done" /></td> -->
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