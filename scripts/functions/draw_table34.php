<?php
include ("../../../tmp/table.php");
if ($_SESSION['logged_in']!="T")
	{
		session_destroy();
		$_SESSION['login_message']="Please log in before accessing that page<br>";
		header ("Location:../../index.php");
		
	}
function draw_table33($val10,$val11,$val12,$val13,$val14,$val15,$val16,$val20,$val21,$val22,$val23,$val24,$val25,$val26)
{
echo "<table border='0'>";
	/*echo "<tr>";
		echo "<td style='align:center;'>H</td>";
		echo "<td style='align:center;'>T</td>";
		echo "<td style='align:center;'>U</td>";
	echo "</tr>";*/
	echo "<tr>";
		echo "<td style='align:center;'>$val10</td>";
		echo "<td >$val11</td>";
		echo "<td >$val12</td>";
		echo "<td >$val13</td>";
		echo "<td >$val14</td>";
		echo "<td >$val15</td>";
		echo "<td >$val16</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td style='align:center;'>$val20</td>";
		echo "<td>$val21</td>";
		echo "<td>$val22</td>";
		echo "<td>$val23</td>";
		echo "<td>$val24</td>";
		echo "<td>$val25</td>";
		echo "<td>$val26</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td colspan='7'>---------------------------------</td>";
		//echo "<td>---</td>";
		//echo "<td>---</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		/*echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";*/
	echo "</tr>";
	echo "<tr>";
		echo "<td colspan='7'>----------------------------------</td>";
		//echo "<td>===</td>";
		//echo "<td>===</td>";
	echo "</tr>";
echo "</table>";
}
/*echo "Starting <br><br>";
dt(4,0,0,'-',1,0);
echo "Ended <br>";*/
?>