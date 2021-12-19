<?php
function dt($val10,$val11,$val12,$val20,$val21,$val22)
{
echo "<table border='0'>";
	echo "<tr>";
		echo "<td style='align:center;'>H</td>";
		echo "<td style='align:center;'>T</td>";
		echo "<td style='align:center;'>U</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td style='align:center;'>$val10</td>";
		echo "<td style='align:center;'>$val11</td>";
		echo "<td style='align:center;'>$val12</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td style='align:center;'>$val20</td>";
		echo "<td>$val21</td>";
		echo "<td>$val22</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td colspan='3'>-----------</td>";
		//echo "<td>---</td>";
		//echo "<td>---</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td colspan='3'>------------</td>";
		//echo "<td>===</td>";
		//echo "<td>===</td>";
	echo "</tr>";
echo "</table>";
}
/*echo "Starting <br><br>";
dt(4,0,0,'-',1,0);
echo "Ended <br>";*/
?>