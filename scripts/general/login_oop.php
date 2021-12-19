<?php
	$root_path="../../";
	include($root_path."config/paths.php");
	include($root_path."config/sungunura.php");
	$_SESSION['source_form']="login.php";
	if(isset($_POST['btnLogin'])){
		$char_counter=0;
		foreach ($_POST AS $key => $value)
		{
			if ($key!=='btnAnswer')
			{
				echo "$key = $value<br>";
				$char_count=strlen($value);
				while ($char_counter<$char_count)
				{
					$current_char=substr($value,$char_counter,1);
					echo "Character $char_counter is ".$current_char."<br>";
					//Validate each character
					$current_outcome=preg_match("~[A-Za-z0-9 ]~",$current_char);
					echo "Current outcome is $current_outcome<br/>";
					if ($current_outcome==0)
					{
						$_SESSION['answer_validate']="Invalid character entered<br>";
						echo $_SESSION['answer_validate'];
						//header("Location:../student_home.php");
						//exit;
					}
					$char_counter++;
				}
			}
		}
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		if($user->login($username,$password)){ 		
			try {
				$count=0;
				$strQry="SELECT * FROM tblMembers WHERE username ='".$username ."' ORDER BY username";
				$stmt = $db->query($strQry);
				$expiry_date = date('d F, Y',strtotime("now"));
				$current_date = date('d F, Y',strtotime("now"));
				$user_status = '';
				$user_grade = '';
				$user_type='';
				while($row_user = $stmt->fetch()){
					$count++;
					$_SESSION['loggedin'] = true;
					$_SESSION['username'] = $row_user['username'];
					$expiry_date = date('d F, Y',strtotime($row_user['expiry_date']));
					$current_date = date('d F, Y',strtotime("now"));
					$user_status = (int)$row_user['status'];
					$user_grade = $row_user['grade'];
					$user_type=$row_user['member_type'];									
				}
				if($user_type == '1'){
					//logged in return to index page
					//UPDATE login time
					header('Location: ../administrators/administrator_home.php');
					exit;
				}
				if($user_status == '-1'){	
					$message = '<p class="error">Sorry your subscription activation in progress...<br> Please again a little later</p>';
				}	
				if($user_status!=-1 ){
					if( $expiry_date < $current_date){
						$user_status= 0;
						$stmt = $db->prepare('UPDATE tblMembers 
												SET status =:status 
												WHERE username =:username') ;
						$stmt->execute(array(
							':username' => $username,
							':status' => $user_status
						));	
					}				
					if($user_status >= '1'){				
						//logged in return to index page
						$_SESSION['student_grade']=$user_grade;
						$_SESSION['guest']=false;
						$_SESSION['logged_in']="T";
						header('Location: ../students/student_home.php');
						exit;
					}
					if($user_status == '0'){
						$_SESSION['message'] = '<p class="error">Sorry your subscription has expired. Please subscribe</p>';
						//UPDATE login time
						header('Location: registration.php');	
						exit;						
					}
				}
			} catch(PDOException $e) {
				echo $e->getMessage();
			}
		}
		else{
			$message = '<p class="error">Invalid username or password</p>';
		}

	}
	//end if submit
?>
<!doctype html>
<html>
<head>
	<title>
		Login
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
</head>
<body>
	<div id="banner" >
		Top Section
	</div>
	<div id="slogan_bar" >
		<marquee>The Technological High Table</marquee>
	</div>
	<div id="message_bar" >
		CBP
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			<ul id="nav">					
					<li><a href="../../index.php">Back</a></li>
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
			?>  
		</div>
		<form  autocomplete="off" id="form1" name="form1" method="post" action="login.php">
								<table >
									<tr>
										<td colspan="2">
											<?php
												if(isset($message))												{ 
													echo "<b style='color:red'>$message</b>"; 
													unset($message);
												}
												if(isset($_SESSION['login_message']))												{ 
													echo "<b style='color:red'>".$_SESSION['login_message']."</b>"; 
													unset($_SESSION['login_message']);
												}
												if (isset($_SESSION['member_message']))
												{
													echo $_SESSION['member_message'];
													unset ($_SESSION['member_message']);
												}
												
											?>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<div align="left">
												<strong>Login</strong>
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td >
											Login:
										</td>
										<td >
											<input type="text" name="username" id="username" />
										</td>
									</tr>
									<tr>
										<td>
											<label for="password">Passcode:</label>
										</td>
										<td>
											<input type="password" name="password" id="password" />
										</td>
									</tr>
								</table>
								<p>
									<input type="submit" name="btnLogin" id="btnLogin" value="Login" />
								</p>
								<p>&nbsp;</p>
							</form>
		
		
	</div>
	<div id="index_right_sidebar" >
		RIGHT INDEXING
	</div>
	<div id="footer" >
		<font="brown">The Citadel of Learning</font>
	</div>
	
</body>
</html>