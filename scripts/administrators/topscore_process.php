<?php
//include($root_path."../../config/paths.php");
include ("../../config/sungunura.php");
include ("../functions/table_tmp_file_create.php");
include ("../functions/table_tmp_file_update.php");
$_SESSION['source_form']="scores_process";
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
	}
foreach ($_POST as $key=>$value)
{
	echo "$key=$value<br>";
	$question_details="$key";
	echo $question_details."<br>";
}

echo "Question details are $question_details<br>";
//Unbundling question attributes

$subject="";
$topic="";
$question_number="";
$plus_counter=0;
$char_counter=0;
$table_file="";
$char_count=strlen($question_details);

while ($char_counter<$char_count)
{
	$current_char=substr($question_details,$char_counter,1);
	//echo "Character $char_counter is ".$current_char."<br>";
	//$char_counter++;
	switch ($plus_counter)
	{
		case 0:
			if ($current_char=="+")
			{
				$plus_counter++;
				$question_number="";
				continue;
			}
			else
			{
				$subject=$subject.$current_char;
			}
	
		case 1:
			if ($current_char=="+")
			{
				$plus_counter++;
				$topic="";
				continue;
			}
			else
			{
				$question_number=$question_number.$current_char;
			}
		
		case 2:
			if ($current_char=="+")
			{
				$plus_counter++;
				//$start_time="";
				continue;
			}
			else
			{
				if ($current_char=="_")
				{
					$current_char=" ";
				}
				$topic=$topic.$current_char;
			}
	}
	$char_counter++;
} 
//echo "Pupil name is $pupil<br>";
echo "Subject code is $subject<br>";
echo "Topic ID is $topic<br>";
echo "Question number is $question_number<br>";

?>
<!doctype html>
<html>
<head>
	<title>
		Test Report
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
					<li><a href='./administrator_home.php'>Home</a></li>
					<li><a href='./top_scored.php'>Back</a></li>
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
			
		?>
		
		</div>
		<form id="form1" name="form1" method="post" action="report_question.php">
								
								
		<?php
				$question_query="SELECT * FROM tblmcquestions 
								WHERE subject_code=$subject 
								AND topic_id=$topic 
								AND question_number=$question_number";
				echo $question_query;
				if (!$question_result=mysqli_query($cxn,$question_query))
				{
					$_SESSION['test_message']="Failed to retrive question :".mysqli_error($cxn)."<br>";
					exit;
				}
				else
				{
					$row=mysqli_fetch_assoc($question_result);
					extract ($row);
					echo "<b><p>$instruction</b></p>";
					// Retrieve story
						if (isset ($_SESSION['story_id']))
						{
							if ($story_id!=0) 
							{
								$story_query="SELECT story_title,story_content FROM tblstories 
								WHERE subject_code=$subject_code AND story_id={$_SESSION['story_id']}";
								//echo "$story_query<br/>";
								$story_result=mysqli_query($cxn,$story_query);
								$row=mysqli_fetch_assoc($story_result);
								extract ($row);
								echo "<center><h3>$story_title</h3></center>";
								echo "<p>$story_content<br/></p>";
							}
						}
						//Process Image
								if ($image_id!=0)
								{
									$image_query="SELECT * FROM tblimages WHERE image_id={$_SESSION['image_id']}";
									$image_result=mysqli_query($cxn,$image_query);
									$row=mysqli_fetch_assoc($image_result);
									extract($row);
								//	echo "Image ID is ".$_SESSION['image_id']."<br>";
								//	echo "Image title is $image_title <br>";
								//	echo "Image URL is $image_location <br>";
								}
						//Display Image
						 
							if ($image_id!=0)
							{
								echo "<center>".$image_title."</center><br><br>";
								echo "<center><img src='$image_location'></img></center>";
							}
					echo "<p><b style='color:blue'>".$question."</b><br/></p>";
						//echo $question."<br>";
					//Choices
						//Randomise answers</p>
						//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
						$choice_ceiling=4;
						$choice_counter=0;
						$selector_counter=0;
						$selectors[$selector_counter]="";
						while ($choice_counter<$choice_ceiling)
						{
							$query="SELECT MOD(ROUND(100*TRUNCATE(RAND(),2),0),4)";
							$result2=mysqli_query($cxn,$query);
							$row=mysqli_fetch_row($result2);
							$selector=$row[0];
							$selector_counter++;
							if (in_array($selector,$selectors))
							{
								$choice_counter--;
							}
							else if (!in_array($selector,$selectors))	//this random number not already been generated
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
						
				}
			?>
			<hr/>
					<p>
						<b style='color: red;'><u>Answers</u></b>
					</p>
					<p>
						
						
							<?php 
								if ($choices[0]==$true_answer)
								{
									echo "<b style='color:blue;'><b>A.</b>  ".$choices[0];
								}
								else
								{
									echo "<b style='color:purple;'><b>A.</b> ".$choices[0];
								}
							?>
					</p>
					<p>
						
						<?php 
								if ($choices[1]==$true_answer)
								{
									echo "<b style='color:blue;'><b>B.</b> ".$choices[1];
								}
								else
								{
									echo " <b style='color:purple;'><b>B.</b> ".$choices[1];
								}
							?>
					</p>
					<p>
						
						
							<?php 
								if ($choices[2]==$true_answer)
								{
									echo "<b style='color:blue;'><b>C.</b> ".$choices[2];
								}
								else
								{
									echo " <b style='color:purple;'><b>C.</b> ".$choices[2];
								}
							?>
					</p>
					<p>
						
						
							<?php 
								if ($choices[3]==$true_answer)
								{
									echo "<b style='color:blue;'><b>D.</b> ".$choices[3];
								}
								else
								{
									echo " <b style='color:purple;'> <b>D.</b> ".$choices[3];
								}
							?>
					</p>
					<hr/>
											             
								<p>&nbsp;</p>
								<table >
						<tr>
							
						</tr>
					</table>
						<p>&nbsp;</p>
							<p>&nbsp;</p>
							<p>&nbsp;</p>
		</form>
	</div>
	<div id="index_right_sidebar" >
		
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>