<?php
$root_path="../../";
include($root_path."../config/paths.php");
include ($root_path."../config/sungunura.php");
	/*if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}*/
	
//retrieve scheduled tests
	
	

?>
<!doctype html>
<html>
<head>
	<title>
		Cyber Home
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
		
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			<ul class="nav">
					<li><a href='../my_scheduled_tests.php'>Back</a></li>
					<li><a href='../administrator_home.php'>Home</a></li>
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
			<form id="form1" name="form1" method="post" action="test_detail.php">
	<?php
	foreach ($_POST as $key=>$value)
	{
		//echo $key." = ".$value."<br>";
		$plus_counter=0;
		$char_counter=0;
		$test_owner="";
		$subject="";
		$topic="";
		$grade="";
		$subject_counter="";
		$table_file="";
		$char_count=strlen($key);
		while ($char_counter<$char_count)
		{
			$current_char=substr($key,$char_counter,1);
			//echo "Character $char_counter is ".$current_char."<br>";
			//$char_counter++;
			switch ($plus_counter)
			{
				case 0:
					if ($current_char=="+")
					{
						$plus_counter++;
						$subject=""; 
						continue;
					}
					else
					{
						$test_owner=$test_owner.$current_char;
					}
			
				case 1:
					if ($current_char=="+")
					{
						$plus_counter++;
						$grade="";
						continue;
					}
					else
					{
						if ($current_char=='_')
						{
							$subject=$subject." ";
						}
						else
						{
							$subject=$subject.$current_char;
						}
					}
				
				case 2:
					if ($current_char=="+")
					{
						$plus_counter++;
						$subject_counter="";
						continue;
					}
					else
					{
						if ($current_char=="_")
						{
							$current_char=" ";
						}
						$grade=$grade.$current_char;
					}
				case 3:
					if ($current_char=="+")
					{
						$plus_counter++;
						$grade="";
						continue;
					}
					else
					{
						if ($current_char=="_")
						{
							$current_char=" ";
						}
						$subject_counter=$subject_counter.$current_char;
					}
				}
			$char_counter++;
		}
		//echo "Dummy is ".$dummy="<br>";
		//echo "Test owner is ".$test_owner."<br>";
		//echo "Subject is ".$subject."<br>";
		//echo "Topic  is ".$topic."<br>";
		//echo "Grade is ".$grade."<br>";
		//echo "Subject counter is ".$subject_counter."<br>";
		
	}
	foreach ($_POST as $key=>$value)
	{
		//echo "$key = $value<br>";
	}
	//echo "<br><br>Test owner is ".$test_owner."<br>";
	//	echo "Subject is ".$subject."<br>";
		//echo "Topic  is ".$topic."<br>";
		//echo "Grade is ".$grade."<br>";
		//echo "Subject counter is ".$subject_counter."<br>";
		
	
	//Retrieve subject code
	$subject_query="SELECT subject_code 
					FROM tblsubjects
					WHERE subject_name='".$subject."'";
	//echo $subject_query."<br>";
	if (!$subject_result=mysqli_query( $cxn,$subject_query))
	{
		$subject_message="Failed to retrieve subject :".mysqli_error($cxn)."<br>";
	}
	else
	{
		$row=mysqli_fetch_assoc($subject_result);
		extract ($row);
		//echo "Subject code is ".$subject_code."<br>";
	}
	
	
	
	
	//Retrieve questions
	
	
	/*function question_get($cxn)
	{
		$questions_query="SELECT q.question,d.question_number,q.true_answer,q.option1,q.option2,q.option3
							FROM tblmcquestions q, tbltestdetails d
							WHERE d.test_owner='".$_SESSION['username']."'
							AND q.subject_code=".$subject_code."
							AND q.topic_id=".$topic_id."
							AND d.subject_counter=".$subject_counter;
	}*/
	
	$questions_query="SELECT q.instruction,q.question,d.question_number,d.question_counter,q.true_answer,q.option1,q.option2,q.option3,q.story_id,q.image_id
							FROM tblmctestdetail d,tblmcquestions q
							WHERE q.subject_code=d.subject_code
							AND q.topic_id=d.topic_id
							AND q.question_number=d.question_number
							AND d.test_owner='".$_SESSION['username']."'
							AND d.subject_code=".$subject_code."
							AND d.grade=".$grade."
							AND d.subject_counter=".$subject_counter."
							ORDER BY d.question_counter";
	//echo $questions_query."<br>";
	if (!$questions_result=mysqli_query($cxn,$questions_query))
	{
		$questions_message="Failed to retrive questions :".mysqli_error($cxn)."<br>";
		//echo $questions_message;
	}
	else
	{
		$test_counter=0;
		$test_count=0;
		$questions=[];
		while ($row=mysqli_fetch_assoc($questions_result))
		{
			extract ($row);
			echo "<br><strong><span style='color:#0000FF;'>Question ".$question_counter."<span style='color:#900090;'></strong><br><br>";
			echo "<strong>".$instruction."</strong><br><br>";
			//Get story
															
															if ($story_id!=0)
															{
																if ($story_counter==1)
																{	
																	$story_query="SELECT story_title,story_content 
																				FROM tblstories 
																				WHERE story_id=$story_id AND subject_code=$subject_code";
																	if (!$story_result=mysqli_query($cxn,$story_query))
																	{ 
																		$_SESSION['story_message']="Failed to retrieve story :".mysqli_error($cxn)."<br>"; 
																		
																		exit;
																	}
																	else
																	{ 
																		$_SESSION['story_message']="Story retrieved<br>"; 
																	}
																	$row=mysqli_fetch_assoc($story_result);
																	extract ($row);
																	echo "<center><h3>$story_title</h3></center>";
																	echo "<p>$story_content<br/></p>";
																	$story_counter++;
																	//echo "Story counter is $story_counter<br>";
																}
															}
															
															//Get image
															if ($image_id!=0)
															{
																$image_counter=1;
																if ($image_counter==1)
																$image_query="SELECT image_location 
																			FROM tblimages 
																			WHERE image_id=$image_id";
																//echo "<br>".$image_query."<br>";
																if (!$image_result=mysqli_query($cxn,$image_query))
																{ 
																	$_SESSION['image_message']="Failed to retrieve image :".mysqli_error($cxn)."<br>"; 
																	
																	exit;
																}
																else
																{ 
																	$_SESSION['image_message']="image retrieved<br>"; 
																}
																$row=mysqli_fetch_assoc($image_result);
																extract ($row);
																//echo $image_location."<br/><br/>";
																echo "<center><img src='$image_location'></img></center>";
																//echo "Image location is $image_location<br>";
															}
			
			echo $question."<br>";
			
			//Randomise answers</p>
			//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			$min_selector=0;
			$max_selector=3;
			$choice_floor=0;
			$choice_ceiling=4;
			$choice_counter=0;
			$selector_counter=0;
			$selectors=[];
			$choices=[];
			while ($choice_counter<$choice_ceiling)
			{
				$selector=rand($min_selector,$max_selector);
				if (in_array($selector,$selectors))
				{
					$choice_counter--;
				}
				else if (!in_array($selector,$selectors))
				{
					switch ($selector)
					{
						case 0:
							$choices[$choice_counter]=$true_answer;
							break;
						case 1:
							$choices[$choice_counter]=$option1;
							break;
						case 2:
							$choices[$choice_counter]=$option2;
							break;
						case 3:
							$choices[$choice_counter]=$option3;
							break;
					}
					$selectors[$selector_counter]=$selector;
					$selector_counter++;
				}
				$choice_counter++;
			}
			
					
		 //End randomise
			while ($choice_counter>$choice_floor)
			{
				$choice_counter--;
				if ($choice_counter==3)
				{
					echo "A: ".$choices[$choice_counter]."<br/>";
				}
				if ($choice_counter==2)
				{
					echo "B: ".$choices[$choice_counter]."<br/>";
				}
				if ($choice_counter==1)
				{
					echo "C: ".$choices[$choice_counter]."<br/>";
				}
				if ($choice_counter==0)
				{
					echo "D: ".$choices[$choice_counter]."<br/>";
				}
			}
		 /*
			echo $true_answer."<br>";
			echo $option1."<br>";
			echo $option2."<br>";
			echo $option3."<br>";
			echo "----------------------<br>";*/
		}
		
	}
		
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