<?php
//draws a table
function draw_table($columns,$rows)
{
	
	
	$column_counter=0;
	$columns=0;
	$row_counter=0;
	$rows=0;
	echo "<table border='1' width='100%' style='align:center;'>";
		
		echo "<tr>";
			echo "<td style='align:center;width:10%;'>H</td>";
				}
				else
				{
					if ($column_counter==0)
					{
						echo "<td style='align:center;width:10%;'>&nbsp;</td>";
					}
					else
					{
						echo "<td style='align:center;width:10%;'>$row_counter$column_counter</td>";
					}
				}
				$column_counter++;
			}
			$row_counter++;
			echo "</tr>";
		}
	echo "</table>";
}
draw_table(3,3);
?>