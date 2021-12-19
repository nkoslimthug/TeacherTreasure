<?php
$root_path="../../";
include($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
include ("../functions/sunungura.php");
//Initialise counters
$_SESSION['source_form']="manage_account.php";
unset ($_SESSION['question_type']);
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}

?>
<!doctype html>
<html>
<head>
	<title>
		Guardian Home
	</title>
			<link rel="stylesheet"	type="text/css"	href="../../style/college.css" />
</head>
<body>
	<div id="banner" >
		
	</div>
	<div id="slogan_bar" >
		<i>eCompanion - It's Just What the Teacher Ordered</i>
	</div>
	<div id="message_bar" >
						<?php 
						
						?>
	</div>
	<div id="index_left_sidebar" >
		<br><br><br>
		<div id="menu">
			<ul id="nav">
                	
					<li><a href="../general/account_edit.php">MyAccount</a></li>
					<li><a href="./pupil_select.php">PupilAccount</a></li>
					<li><a href="./administrator_home.php">Home</a></li>
					<li><a href="../../scripts/general/logout.php">Logout</a></li>
			</ul>
		</div>	
	</div>
	<div id="content" >
		<div id="message_banner">  
		<?php  
			if (isset($_SESSION['array_msg']))
			{
				echo $_SESSION['array_msg'];
				unset ($_SESSION['array_msg']);
			}
			if (isset($_SESSION['attempted_message']))
			{
				echo $_SESSION['attempted_message'];
				unset ($_SESSION['attempted_message']);
			}
			if (isset($_SESSION['member_message'])) 	
			{
				echo $_SESSION['member_message'];
				unset ($_SESSION['member_message']);
			}
			if (isset($_SESSION['flaw_message']))
			{
				echo $_SESSION['flaw_message'];
				unset ($_SESSION['flaw_message']);
			}
			if (isset($_SESSION['load_message']))
			{
				echo $_SESSION['load_message'];
				unset ($_SESSION['load_message']);
			}
			if (isset($_SESSION['test_store_message']))
			{
				echo $_SESSION['test_store_message'];
				unset ($_SESSION['test_store_message']);
			}
			if (isset($_SESSION['status_change_message']))
			{
				echo $_SESSION['status_change_message'];
				unset ($_SESSION['status_change_message']);
			}
			if (isset($_SESSION['test_message']))
			{
				echo $_SESSION['test_message'];
				unset ($_SESSION['test_message']);
			}
			if (isset($_SESSION['initialise_message']))
			{
				echo $_SESSION['initialise_message'];
				unset ($_SESSION['initialise_message']);
			}
			if (isset($_SESSION['question_add_message']))
			{
				echo $_SESSION['question_add_message'];
				unset ($_SESSION['question_add_message']);
			}
			if (isset($_SESSION['session_pass_message']))
			{
				echo $_SESSION['session_pass_message'];
				unset ($_SESSION['session_pass_message']);
			}
			if (isset($_SESSION['max_questions_message']))
			{
				echo $_SESSION['max_questions_message'];
				unset ($_SESSION['max_questions_message']);
			}
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
			if (isset($_SESSION['detail_close_message']))
			{
				echo $_SESSION['detail_close_message'];
				unset ($_SESSION['detail_close_message']);
			}
			if (isset($_SESSION['initialise_message']))
			{
				echo $_SESSION['initialise_message'];
				unset ($_SESSION['initialise_message']);
			}
			if (isset($_SESSION['initialise_query']))
			{
				echo $_SESSION['initialise_query'];
				unset ($_SESSION['initialise_query']);
			}
			if (isset($_SESSION['incomplete_summ_messsage']))
			{
				echo $_SESSION['incomplete_summ_messsage'];
				unset ($_SESSION['incomplete_summ_messsage']);
			}
			if (isset($_SESSION['summary_delete_message']))
			{
				echo $_SESSION['summary_delete_message'];
				unset ($_SESSION['summary_delete_message']);
			}
			if (isset($_SESSION['detail_delete_message']))
			{
				echo $_SESSION['detail_delete_message'];
				unset ($_SESSION['detail_delete_message']);
			}
			if (isset($_SESSION['subject_count_message']))
			{
				echo $_SESSION['subject_count_message'];
				unset ($_SESSION['subject_count_message']);
			}
			if (isset($_SESSION['subject_count_query']))
			{
				echo $_SESSION['subject_count_query'];
				unset ($_SESSION['subject_count_query']);
			}
			if (isset($_SESSION['initialise_query']))
			{
				echo $_SESSION['initialise_query'];
				unset ($_SESSION['initialise_query']);
			}
			if (isset($_SESSION['question_add_query']))
			{
				echo $_SESSION['question_add_query'];
				unset ($_SESSION['question_add_query']);
			}
			if (isset($_SESSION['summary_close_query']))
			{
				echo "<br>".$_SESSION['summary_close_query'];
				unset ($_SESSION['summary_close_query']);
			}
			if (isset($_SESSION['summary_close_query']))
			{
				echo "<br>";$_SESSION['summary_close_query']."<br>";
				unset ($_SESSION['summary_close_query']);
			}
			if (isset($_SESSION['detail_close_query']))
			{
				echo "<br>".$_SESSION['detail_close_query']."<br>";
				unset ($_SESSION['detail_close_query']);
			}
			if (isset($_SESSION['source_form_message']))
			{
				echo $_SESSION['source_form_message'];
				unset ($_SESSION['source_form_message']);
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
	</div>
	<div id="index_right_sidebar" >
	</div>
	<div id="footer" >
		<font="brown"></font>
	</div>
	
</body>
</html>