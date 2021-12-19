<?php
function draw_table33($column1,$column2,$column3)
{
echo "<table border='1'>";
	echo "<tr>";
		echo "<td style='align:center;'>&nbsp;</td>";
		echo "<td >My name is </td>";
		echo "<td >$_SESSION['fullname']</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td style='align:center;'>&nbsp;</td>";
		echo "<td>This is my first login in </td>";
		echo "<td>$_SESSION['new_year']</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td style='align:center;'>&nbsp;</td>";
		echo "<td>I am now in grade</td>";
		echo "<td>$_SESSION['student_grade']</td>";
	echo "</tr>";
	echo "</table>";
}

?>