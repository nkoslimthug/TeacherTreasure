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
	//echo "Question counter is ".$_SESSION['question_counter']."<br>";
	$subject_name=$_SESSION['subject_name'];
	$subject_code=$_SESSION['subject_code'];
	$grade=$_SESSION['grade'];
	//$_SESSION['paper_type']=1;
	//$paper_number=$_SESSION['question_number'];
	//echo $_SESSION['max_questions']." questions are required for the this test<br>";
	if (isset($_SESSION['test_counter']))
	{
		//echo $_SESSION['test_counter']." questions have been accepted so far<br>";
	}
	//echo "Chosen grade is ".$_SESSION['grade']."<br>";
	//echo "Question counter is ".$_SESSION['question_counter']."<br>";
	if (isset($_SESSION['paper_description']))
	{
		$page_title=$_SESSION['paper_description'];
	}
	if ($_SESSION['question_counter']==1)
	{
		
	//Total questions in current topic
		$count_query="SELECT COUNT(*) AS question_count 
					FROM tblmcquestions 
					WHERE subject_code=$subject_code AND topic_id=".$_SESSION['topic_id'];
		//echo $count_query."<br>";
		$count_result=mysqli_query($cxn,$count_query);
		$row=mysqli_fetch_assoc($count_result);
		extract ($row);
		$_SESSION['question_count']=$question_count;
		
	}
	
	$_SESSION['question_counter']++;
	//echo "Question count is ".$_SESSION['question_count']."<br>";
	//echo "The question counter is now ".$_SESSION['question_counter']."<br>";
	$question_counter=$_SESSION['question_counter'];
	
	
	//Load random question
	
					//$answered_strings='';
					//echo "Batch size is ".$_SESSION['batch_size']."<br>";
					while($_SESSION['test_counter']<=$_SESSION['max_questions']) //change condition
					{
						$question_select_query="SELECT * FROM tblmcquestions 
												WHERE subject_code=$subject_code 
												AND topic_id=".$_SESSION['topic_id']."
												AND lower_grade<=$grade
												AND upper_grade>=$grade
												ORDER BY rand()
												LIMIT 1";
						
						//echo "$question_select_query<br/>";
						
						if (!$question_select_result=mysqli_query($cxn,$question_select_query))
						{
							$message= "Error : \n".mysqli_error($cxn);
							//echo "<script type = 'text/javascript'>alert('".$message."')</script>";
						}
						$row=mysqli_fetch_assoc($question_select_result);
						extract ($row);
						
						$_SESSION['instruction']=$instruction;
						$_SESSION['true_answer']=$true_answer;
						$_SESSION['story_id']=$story_id;
						$_SESSION['image_id']=$image_id;
						$_SESSION['question_complete']=$question;
						$_SESSION['table_based']=$table_based;
						$_SESSION['answer_T']=$true_answer;
						$_SESSION['option1']=$option1;
						$_SESSION['option2']=$option2;
						$_SESSION['option3']=$option3;
						$_SESSION['question_number']=$question_number;
						
						//echo "True answer as extracted from the database is $true_answer<br/>";
				
						//populate questions already accepted
							$question_string=$subject_code.$grade.$topic_id.$question_number; //Generate string
							$question_string=strval($question_string);
							//echo "Question string is $question_string<br/>";
							
							if ($_SESSION['question_counter']==1)
							{	//first question
								//echo "This is the first question<br>"; 
								$answered_counter=$_SESSION['question_counter'];
								$_SESSION['answered_counter']=$answered_counter;
								$answered_strings[$answered_counter]=$question_string;
								$_SESSION['answered_strings']=$answered_strings;
								$_SESSION['answered_counter']++;
								break;
							}
							else 
							{
								   
									if (in_array($question_string,$_SESSION['answered_strings']))
									{
										//echo "$question_string already exists<br/>";
										continue;
									}
								
									else
									{
										if (isset($_SESSION['answered_strings']))
										{
										//echo "Processing string $question_string<br/>";
										$answered_counter=$_SESSION['answered_counter'];
										$answered_strings=$_SESSION['answered_strings'];
										$answered_strings[$answered_counter]=$question_string;
										$_SESSION['answered_strings']=$answered_strings;
										$_SESSION['answered_counter']++;
										}
									//	echo "This is the question ".$_SESSION['question_counter']."<br>"; 
										break;
									}
								
							}
							
							//$_SESSION['question_code']=$question_number;
							$_SESSION['question_number']=$question_number;
							if(isset($question_number_section) && $question_number_section!=''&&$question_number_section!='0')
							{
								$_SESSION['question_code'].='('.$question_number_section.')';						
							}
					}
					//Process Image
								if ($_SESSION['image_id']!=0)
								{
									$image_query="SELECT * FROM tblimages WHERE image_id={$_SESSION['image_id']}";
									$image_result=mysqli_query($cxn,$image_query);
									$row=mysqli_fetch_assoc($image_result);
									extract($row);
								//	echo "Image ID is ".$_SESSION['image_id']."<br>";
								//	echo "Image title is $image_title <br>";
								//	echo "Image URL is $image_location <br>";
								}
								//echo "Image ID = $image_id <br>";
					
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
				if (isset($_SESSION['initialise_message']))
				{
					echo $_SESSION['initialise_message'];
					unset ($_SESSION['initialise_message']);
				}
				/*if (isset($_SESSION['initialise_query']))
				{
					echo $_SESSION['initialise_query'];
					unset ($_SESSION['initialise_query']);
				}	*/		
				if (isset($_SESSION['question_add_message']))
				{
					echo $_SESSION['question_add_message'];
					unset ($_SESSION['question_add_message']);
				}
				
				/*if (isset($_SESSION['max_questions_message']))
				{
					echo $_SESSION['max_questions_message'];
					unset ($_SESSION['max_questions_message']);
				}*/
				/*if (isset($_SESSION['test_counter_message']))
				{
					echo $_SESSION['test_counter_message'];
					unset ($_SESSION['test_counter_message']);
				}*/
				if (isset($_SESSION['summary_close_message']))
				{
					echo $_SESSION['summary_close_message'];
					unset ($_SESSION['summary_close_message']);
				}
			/*	if (isset($_SESSION['question_add_query']))
				{
					echo $_SESSION['question_add_query'];
					unset ($_SESSION['question_add_query']);
				}*/
				
			?>
		</div>
		<form id="form1" name="form1" method="post" action="mc_maketest_process.php">
					<?php     
					//Retrieve question
					//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
						// Test time progress
						//test_time_message();
						if (isset($_SESSION['paper_description']))
						{
							echo "<br><p><h3>".$_SESSION['paper_description']."</h3></p>";
						}
						//test_duration();
						echo "<b><p>$instruction</b></p>";
						
						// Retrieve story
						if (isset ($_SESSION['story_id']))
						{
							if ($story_id!=0) 
							{
								$story_query="SELECT story_title,story_content 
												FROM tblstories 
												WHERE subject_code=$subject_code AND story_id={$_SESSION['story_id']}";
								//echo "$story_query<br/>";
								$story_result=mysqli_query($cxn,$story_query);
								$row=mysqli_fetch_assoc($story_result);
								extract ($row);
								echo "<center><h3>$story_title</h3></center>";
								echo "<p>$story_content<br/></p>";
							}
						}
						//Display Image
						 
							if ($image_id!=0)
							{
								echo "<center>".$image_title."</center><br><br>";
								echo "<center><img src='$image_location'></img></center>";
							}
							//dislay session values
								echo "<big>Question ".$_SESSION['test_counter']." of ".$_SESSION['max_questions']." added to paper<br> </big>";
								
							//Display current question
							if ($table_based!=0) //table based question
							{
								//store session variables
								//$instruction=$_SESSION['instruction'];
								$story_id=$_SESSION['story_id'];
								$_SESSION['table_based']=$table_based;
								$_SESSION['choices0']=$choices[0];
								$_SESSION['choices1']=$choices[1];
								$_SESSION['choices2']=$choices[2];
								$_SESSION['choices3']=$choices[3];
								//dislay session values
								/*echo "Instruction is ".$_SESSION['instruction']."<br>";
								echo "First choice is ".$_SESSION['choices0']."<br>";
								echo "Second choice is ".$_SESSION['choices1']."<br>";
								echo "Third choice is ".$_SESSION['choices2']."<br>";
								echo "Fourth choice is ".$_SESSION['choices3']."<br>";*/
								$_SESSION['choices']=$choices; 
								
								
								echo "Diverting <br>";
								//write temp file
								create_table_file();
								update_table();
								header ("Location:mc_ind_questions_table.php");
								exit;
							 }
							echo "
							<h3 style='color: blue; font-weight: bolder'>
								Question ". $_SESSION['question_counter']." of ".$_SESSION['total_questions']." displaying
							</h3>
							";
							//echo $question;
						$_SESSION['header_path']="../../";
						$_SESSION['images_path']="../../../";						
						include ($_SESSION['header_path']."general/question_header.php");
						echo "<p><b style='color:blue'>".$_SESSION['question_complete']."</b><br/></p>";
						//echo $question."<br>";
						$question=$_SESSION['question_complete'];
						//echo "Batch size is ".$_SESSION['batch_size']."<br>";
						//*151*2*7*1*1*300*37132313984#
						//Could not process request at extension.pre.flow. Please try later.
						/*foreach ($_SESSION as $key=>$value)
						{
							echo "$key = $value<br>";
						}
						*/
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
					<table >
						<tr>
							<td><input style="background-color: green; font-weight: bolder;" type="submit" name="btnAccept" id="btnAccept" value="Accept" /></td>
							<td><input style="background-color: red; font-weight: bolder;" type="submit" name="btnSkip" id="btnSkip" value="Skip" /></td>
						</tr>
							
						<tr>
							<td>&nbsp;</td>
						</tr>
						
						<tr>
							<td><input style="background-color: red; font-weight: bolder;" type="submit" name="btnAbort" id="btnAbort" value="Abort Topic" /></td>
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
							<td><input style="background-color: green; font-weight: bolder;" type="submit" name="btnDone" id="btnDone" value="Done" /></td>
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
