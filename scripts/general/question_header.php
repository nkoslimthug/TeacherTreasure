<?php
	//include ("../../../config/sungunura.php");
	//echo "Question story is ".$_SESSION['question_story']."<br/>";
	//echo "Question image is ".$_SESSION['question_image']."<br/>";
	//echo "New story is ".$_SESSION['new_story']."<br/>";
	if (isset($_POST['new_story'])){
		$_SESSION['new_story']=$_POST['new_story'];
	}
	//if ($_SESSION['paper_number']!=0)\
	if (isset($_SESSION['question_story'])){
		if ($_SESSION['question_story']=="Yes"){
			$story_query="SELECT COUNT(story_number) AS story_counter FROM tblstories WHERE subjectCode={$_SESSION['subjectCode']} 
			AND story_grade={$_SESSION['paper_grade']} AND story_paper_number={$_SESSION['paper_number']} AND 
			paper_type='{$_SESSION['paper_type']}'";
			//echo "$story_query<br/>";
			$story_result=mysqli_query($cxn,$story_query);
			$row=mysqli_fetch_assoc($story_result);
			extract($row);
			//echo "$story_counter<br/>";
			$_SESSION['story_counter']=$story_counter;
		}
	}
	if (isset($_SESSION['new_story']) && $_SESSION['new_story']=='Yes'){
		$_SESSION['story_counter']++;
	}
	
	/*function displayImage()
	{
		$folder_path = $_SESSION['images_path']."images/questions/"; //image's folder path
//		$folder_path = $root_path."images/questions/"; //image's folder path
		$num_files = glob($folder_path . "*.{JPEG, jpeg, JPG, jpg, gif, png, bmp}", GLOB_BRACE);
		$folder = opendir($folder_path);
			if($num_files > 0){
			$count=1;
			while(false !=($file = readdir($folder))) {
				$file_path = $folder_path.$file;
				$extension = strtolower(pathinfo($file ,PATHINFO_EXTENSION));
				if($extension=='jpeg' ||$extension=='jpg' || $extension =='png' || $extension == 'gif' || $extension == 'bmp'){
					$title= trim(strstr( $file, ".", true ));
					$segment = array();
					$i = 0;
					$dash ='_';
					while($i < 9){
						$pos = strpos($title,$dash);	
						$pos =(String)$pos;
						if($pos == '0'){
							$segment[$i] = trim($dash);
							$pos++;
						}
						else if($pos > '0'){
							$segment[$i] = trim(strstr($title,$dash,true));
						}
						else {
							$segment[$i] = trim($title);
						}
						$title = trim(substr($title, (int)$pos));
						$i++;
					}
					if(((int)$segment[0] == (int)$_SESSION['subjectCode']) && ((int)$segment[2] == (int)$_SESSION['paper_grade']) && ((int)$segment[4] ==  (int)$_SESSION['paper_type']) && ((int)$segment[6] ==  (int)$_SESSION['paper_number']) && ((int)$segment[8] ==  (int)$_SESSION['question_image_id'])){				
						echo "<p><img src='".$_SESSION['images_path']."images/questions/".$file."'  height='250' title='$title' /></p>";
					}
				}
			}
		}
		else{
			echo "<script type = 'text/javascript'>alert('the folder was empty !')</script>";
		}
		closedir($folder);		
	}*/
	
	/*function displayStory($cxn){
		//if ($_SESSION['new_story']=='No'){
		$story_query="SELECT story_content FROM tblStories WHERE 
						subjectCode='".$_SESSION['subjectCode']."'
						AND story_grade='".$_SESSION['paper_grade']."'						
						AND story_paper_number='".$_SESSION['paper_number']."'
						AND paper_type='".$_SESSION['paper_type']."'
						AND story_number='".$_SESSION['story_counter']."'";
		//echo "$story_query<br/>";
		
		//if (isset($result))
		$result=mysqli_query($cxn,$story_query);
		if($result){
			if($row=mysqli_fetch_array($result)){
				extract ($row);	
				echo "<p>".nl2br($row['story_content'])."</p>";
			}
		}
	}
	if(isset($_SESSION['question_instruction']) && $_SESSION['question_instruction'] != ''){
		echo  "<p><b>".$_SESSION['question_instruction']."</b></p>";
	}
	//stories
	if (isset($_SESSION['question_story']) && $_SESSION['question_story']=='Yes' && $_SESSION['new_story']=='No'){
		displayStory($cxn);
	}
	if (isset($_SESSION['question_story']) && $_SESSION['question_story']=='Yes' && $_SESSION['new_story']=='Yes'){
		echo "<tr>
										<td width='397'><label for='story_content'>Question Details:</label></td>
										<td width='230'><textarea name='story_content' id='story_content' cols='75' rows='5' placeholder='Capture story here...'></textarea></td>
									</tr>
												</td>
											</tr>";
	}*/
	//images
	/*echo "Subject code is ".$_SESSION['subjectCode']."<br/>";
	echo "Grade is  ".$_SESSION['paper_grade']."<br/>";
	echo "Paper type is ".$_SESSION['paper_type']."<br/>"; 
	echo "Paper number is ".$_SESSION['paper_number']."<br/>";
	echo "Image ID is ".$_SESSION['question_image_id']."<br/>";*/
	if ((isset($_SESSION['question_image_id'])&& $_SESSION['question_image_id'] > 0)){
		displayImage();
	}
?>