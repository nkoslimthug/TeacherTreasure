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
	if (isset($_POST['btnAbort'])){
		if($_SESSION['guest']==true){
			header ("Location:../../../index.php");
		}
		else{
			header ("Location:../administrator_home.php");
		}
		exit;
	}

	$subject_name=$_SESSION['subject_name'];
	$subject_code=$_SESSION['subject_code'];
	$grade=$_SESSION['grade'];
	//$_SESSION['paper_type']=1;
	//$paper_number=$_SESSION['question_number'];

	$page_title=$_SESSION['paper_description'];
	if ($_SESSION['question_counter']==0)
	{
	//Determine TopicID
		$topic_query="SELECT topic_id 
					  FROM tbltopics 
					  WHERE topic_name='".$_SESSION['topic_name']."'";
		//echo $topic_query."<br/>";
		$topic_result=mysqli_query($cxn,$topic_query);
		$row=mysqli_fetch_assoc($topic_result);
		extract($row);
		$_SESSION['topic_id']=$topic_id;
		
	
	//Determine question_counter
	
		$count_query="SELECT COUNT(*) AS question_count 
					FROM tblmcquestions 
					WHERE subject_code=$subject_code AND topic_id=$topic_id";
		//echo $count_query."<br>";
		$count_result=mysqli_query($cxn,$count_query);
		$row=mysqli_fetch_assoc($count_result);
		extract ($row);
		$_SESSION['question_count']=$question_count;
		
	}
	if ($_SESSION['question_counter']==$_SESSION['question_count'])
	{
		header ("Location:../administrator_home.php");
		exit;
	}
	$_SESSION['question_counter']++;
	//echo "Question count is ".$_SESSION['question_count']."<br>";
	//echo "The question counter is now ".$_SESSION['question_counter']."<br>";
	$question_counter=$_SESSION['question_counter'];
	
	
	//Load random question
	
					//$answered_strings='';
					//echo "Batch size is ".$_SESSION['batch_size']."<br>";
					while($_SESSION['question_counter']<=$_SESSION['question_count']) //change condition
					{
						$question_select_query="SELECT * 
												FROM tblmcquestions 
												WHERE subject_code=$subject_code 
												AND topic_id=".$_SESSION['topic_id']."
												AND question_number=".$_SESSION['question_counter']."
												ORDER BY question_number LIMIT 1";
						//echo "$question_select_query<br/>";
						
						if (!$question_select_result=mysqli_query($cxn,$question_select_query))
						{
							$message= "Error : \n".mysqli_error($cxn);
							//echo "<script type = 'text/javascript'>alert('".$message."')</script>";
						}
						$row=mysqli_fetch_assoc($question_select_result);
						extract ($row);
						/*	echo "True answer is ".$true_answer."<br>";
						echo "Option 1 is ".$option1."<br>";
						echo "Option 2 is ".$option2."<br>";
						echo "Option 3 is ".$option3."<br>";*/
						$_SESSION['instruction']=$instruction;
						$_SESSION['true_answer']=$true_answer;
						//	echo "Image ID == $image_id <br>";
						$_SESSION['story_id']=$story_id;
						$_SESSION['image_id']=$image_id;
						$question_complete=$question;
						$_SESSION['table_based']=$table_based;
						if ($table_based!=0)
						{
							$_SESSION['question_complete']=$question_complete;
						}
						else
						{
							$_SESSION['question_complete']=ucfirst($question_complete);
						}
						
						$_SESSION['answer_T']=$true_answer;
						$_SESSION['option1']=$option1;
						$_SESSION['option2']=$option2;
						$_SESSION['option3']=$option3;
						$_SESSION['question_number']=$question_number;
						
						//echo "True answer as extracted from the database is $true_answer<br/>";
				
						//populate questions already answered
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
									//echo "Processing string $question_string<br/>";
									$answered_counter=$_SESSION['answered_counter'];
									$answered_strings=$_SESSION['answered_strings'];
									$answered_strings[$answered_counter]=$question_string;
									$_SESSION['answered_strings']=$answered_strings;
									$_SESSION['answered_counter']++;
								//	echo "This is the question ".$_SESSION['question_counter']."<br>"; 
									break;
								}
							}
							
							//$_SESSION['question_code']=$question_number;
							$_SESSION['question_number']=$question_number;
							if(isset($question_number_section) && $question_number_section!=''&&$question_number_section!='0'){
								$_SESSION['question_code'].='('.$question_number_section.')';						
							}
					}
					//Process Image
								if ($_SESSION['image_id']!=0)
								{
									$image_query="SELECT * 
												FROM tblimages 
												WHERE image_id=".$_SESSION['image_id'];
									//echo $image_query."<br>";
									$image_result=mysqli_query($cxn,$image_query);
									if (!$row=mysqli_fetch_assoc($image_result))
									{
										$_SESSION['image_message']="No image found for question ".$_SESSION['subject_code']."_".$_SESSION['topic_id']."_".$question_number."<br>";
										header ("Location:../administrator_home.php");
									}
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
				//include($root_path."config/page_header.php");
				
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
			if (isset($_SESSION['source_form_message']))
				{
					echo $_SESSION['source_form_message'];
					unset ($_SESSION['source_form_message']);
				}
				
			if (isset($_SESSION['flaw_message']))
			{
				echo $_SESSION['flaw_message'];
				unset ($_SESSION['flaw_message']);
			}
			?>
			
		</div>
		<form id="form1" name="form1" method="post" action="mc_all_answers.php">
					<?php     
					//Retrieve question
					//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
						// Test time progress
						//test_time_message();
						echo "<br><p><h3>".$_SESSION['paper_description']."</h3></p>";
						//test_duration();
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
						//Display Image
						 
							if ($image_id!=0)
							{
								echo "<center>".$image_title."</center><br><br>";
								echo "<center><img src='$image_location'></img></center>";
							}
							//dislay session values
							/*	echo "Instruction is ".$_SESSION['instruction']."<br>";
								echo "First choice is ".$_SESSION['choices0']."<br>";
								echo "Second choice is ".$_SESSION['choices1']."<br>";
								echo "Third choice is ".$_SESSION['choices2']."<br>";
								echo "Fourth choice is ".$_SESSION['choices3']."<br>";*/
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
								echo "Instruction is ".$_SESSION['instruction']."<br>";
								echo "First choice is ".$_SESSION['choices0']."<br>";
								echo "Second choice is ".$_SESSION['choices1']."<br>";
								echo "Third choice is ".$_SESSION['choices2']."<br>";
								echo "Fourth choice is ".$_SESSION['choices3']."<br>";
								
								
								echo "Diverting <br>";
								//write temp file
								create_table_file();
								update_table();
								header ("Location:mc_ind_questions_table.php");
								exit;
							 }
							echo "
							<h3 style='color: blue; font-weight: bolder'>
								Question ". $_SESSION['question_counter']." of  ".$_SESSION['question_count']."\t\t\t\t 
							</h3>";
									
							echo  "<h4 style='color': brown;>	
								Available for  grades <b>$lower_grade</b> to <b>$upper_grade</b>
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
							<td><input style="background-color: green; font-weight: bolder;" type="submit" name="btnAnswer" id="btnAnswer" value="Next Question" /></td>
							<td><input style="background-color: red; font-weight: bolder;" type="submit" name="<?php echo  $subject_code|$topic_id|$question_number; ?>" id="btnAbort" value="Report Question" /></td>
							<td><input style="background-color: red; font-weight: bolder;" type="submit" name="btnAbort" id="btnAbort" value="Abort" /></td>
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
