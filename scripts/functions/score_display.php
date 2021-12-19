<?php
$root_path="../../";
//include ($root_path."config/paths.php");
//include ($root_path."config/sungunura.php");
function score_display()
{
	include ("./score_calc.php");
	$percentage=score_calc();
	echo '<p>';
		echo "You have scored <b style='color:blue'>".$_SESSION['question_score']."</b><font color='#900090'>out of <b style='color:blue'>".$_SESSION['size']."</b><font color='#900090'><br/>";
		if  ($percentage>=90)
		{
			  echo "You got <font color='green'><b>$percentage</b><font color='#900090'> &#37<br/>";
			//  echo "<font color='green'><b>1</b><font color='#900090'> unit. Excellent Performance. 5-Star Effort<br/>";
		}
		  else if ($percentage>=80)
		  {
			  echo "You got <font color='green'><b>$percentage</b><font color='#900090'> &#37<br/>";
			//  echo "<font color='green'><b>2</b><font color='#900090'> units. Great Performance<br/>";
		  }
		  else if ($percentage>=70)
		  {
			  echo "You got <font color='green'><b>$percentage</b><font color='#900090'> &#37<br/>";
			//  echo "<font color='green'><b>3</b><font color='#900090'> units. Very good<br/>";
		  }
		  else if ($percentage>=60) 
		  {
			  echo "You got <font color='orange'><b>$percentage</b><font color='#900090'> &#37<br/>";
			  //echo "<font color='orange'><b>4</b><font color='#900090'> units. Good<br/>";
		  }
		   else if ($percentage>=50){
			  echo "You got <font color='orange'><b>$percentage</b><font color='#900090'> &#37<br/>";
			 // echo "<font color='orange'><b>5</b><font color='#900090'> units. Fair<br/>";
		  }
		   else if ($percentage>=40){
			  echo "You got <font color='red'><b>$percentage</b><font color='#900090'> &#37<br/>";
			  //echo "<font color='red'><b>6</b><font color='#900090'> units. Try harder<br/>";
		  }
		   else if ($percentage>=30){
			  echo "You got <font color='red'><b>$percentage</b><font color='#900090'> &#37<br/>";
			//  echo "<font color='red'><b>7</b><font color='#900090'> units. Put more effort<br/>";
		  }
		   else if ($percentage>=20){
			  echo "You got <font color='red'><b>$percentage</b><font color='#900090'> &#37<br/>";
			 // echo "<font color='red'><b>8</b><font color='#900090'> units. Work harder next<br/>";
		  }
		  else{
			  echo "You got <font color='red'><b>$percentage</b><font color='#900090'> &#37<br/>";
			  //echo "<font color='red'><b>9</b><font color='#900090'> units. Pull up your socks<br/>";
		  }
		echo '</p>';	
}
?>