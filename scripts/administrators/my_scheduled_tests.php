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
	
//retrieve scheduled tests
	function topic_get($cxn,$topic_id,$subject_code)
	{
		include ("../functions/sunungura.php");
		$topic_query="SELECT topic_name 
								FROM tbltopics 
								WHERE subject_code=".$subject_code.
								" AND topic_id=".$topic_id;
		echo $topic_query."<br>";
		if (!$topic_result=mysqli_query($cxn,$topic_query))
		{
			$_SESSION['topic_message']="Failed to retrieve topic".mysqli_error($cxn)."<br>";
			echo $_SESSION['topic_message'];
		}
		else
		{
			$row=mysqli_fetch_assoc($topic_result);
			extract ($row);
			return $topic_name;
		}	
	}
	
	function scheduled_tests($cxn)
	{
		$tests=[];
		$test_counter=0;
		include ("../functions/sunungura.php"); 
		$scheduled_query="SELECT m.test_owner,m.subject_code,s.subject_name,m.grade,m.subject_counter,m.test_size,m.test_status,m.test_deadline 
								FROM tblmctestsummary m, tblsubjects s
								WHERE m.subject_code=s.subject_code
								and m.test_owner='".$_SESSION['username']."'
								ORDER BY m.subject_code,m.test_deadline DESC";
		//echo $scheduled_query."<br>";
		if (!$scheduled_result=mysqli_query($cxn,$scheduled_query))
		{
			$_SESSION['scheduled_messsage']="Failed to count incomplete tests :".mysqli_error($cxn)."<br>";
			//echo $_SESSION['scheduled_messsage'];
			//header ("Location:./administrator_home.php");
		}
		else
		{
			while ($row=mysqli_fetch_assoc($scheduled_result))
			{
				extract ($row);
				//$topic_name=topic_get($cxn,$topic_id,$subject_code);
				//echo $subject_name." ".$subject_counter." ".$test_size." ".$test_status."<br>";
				$tests[$test_counter][0]=$test_owner;
				$tests[$test_counter][1]=$subject_name;
				$tests[$test_counter][3]=$grade;
				$tests[$test_counter][4]=$test_size;
				$tests[$test_counter][5]=$test_status;
				$tests[$test_counter][6]=$test_deadline;
				$tests[$test_counter][7]=$subject_counter;
				//$tests[$test_counter][8]=
				$test_counter++;
			}
		}
		$_SESSION['test_counter']=$test_counter;
		$_SESSION['test_count']=$test_counter;
		$_SESSION['tests']=$tests;
		return $tests;
	}
	
	scheduled_tests($cxn);
	//echo "I have ".$_SESSION['test_counter']." tests in the database<br>";
	
	
	//display array contents
	$tests=$_SESSION['tests'];
	$test_counter=0;
	reset ($tests);
	while ($test_counter<$_SESSION['test_count'])
	{
		//echo $tests[$test_counter][0]." ".$tests[$test_counter][1]." ".$tests[$test_counter][3]." ".$tests[$test_counter][4]."<br>";
		$test_counter++;
		//echo "This is round ".$test_counter."<br>";
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
					<li><a href='test_select_frm.php'>Schedule Test</a></li>
					<li><a href='administrator_home.php'>View Tests</a></li>
					<li><a href='administrator_home.php'>Back</a></li>
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
			<form id="form1" name="form1" method="post" action="./mc/test_details.php">
				<table border="3" style="width:100%;align:center;">
					<tr>
						<td style="align:center; width:8%;"><b>Subject</b></td>
						
						<td style="align:center;width:7%"><b>Grade</b></td>
						<td style="align:center;width:8%"><b>Test Size</b></td>
						<td style="align:center;width:8%"><b>Test Counter</b></td>
						<td style="align:center;width:8%"><b>Status</b></td>
						<td style="align:center;width:15%"><b>Due date</b></td>
						<td style="align:center;width:20%"><b>Selector</b></td>
					</tr>
					<?php
						$test_counter=0;
						$test_number=$test_counter+1;
						reset ($tests);
						while ($test_counter<$_SESSION['test_count'])
						{
							$test_key=$tests[$test_counter][0]."+".$tests[$test_counter][1]."+".$tests[$test_counter][3]."+".$tests[$test_counter][7];
							echo "<tr>";
								echo "<td>".$tests[$test_counter][1]."</td>";
								//echo "<td>".$tests[$test_counter][2]."</td>";
								echo "<td>".$tests[$test_counter][3]."</td>";
								echo "<td>".$tests[$test_counter][4]."</td>";
								echo "<td>".$test_number."</td>";
								echo "<td>".$tests[$test_counter][5]."</td>";
								echo "<td>".$tests[$test_counter][6]."</td>";
								echo "<td><input type='submit' name='".$test_key."' value='Test Details' /></td>";
							echo "</tr>";
							$test_counter++;
							$test_number++;
						}
					?>
				
				</table>
			</form>
								
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>