<?php
$root_path="../../";
include ($root_path."config/paths.php");
include ($root_path."config/sungunura.php");

function score_calc()
	{
		if ($_SESSION['question_type']=='MC')
		{
			$score=$_SESSION['question_score'];
			$size=$_SESSION['size'];	
		}	
		else if($_SESSION['question_type']='SQ'){
			//$size=$_SESSION['total_paper_marks'];
			$size=$_SESSION['size'];
			$score=$_SESSION['score_counter'];
		}
		$percentage=number_format(($score/$size)*100,1);
		$_SESSION['percentage']=$percentage;
		//echo "You have scored <b style='color:blue'>$score </b><font color='#900090'>out of <b style='color:blue'>$size </b><font color='#900090'><br/>";
		return $percentage;
	}

?>