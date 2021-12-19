<?php
//draws a table
function draw_table($columns,$rows,$table_heading,$frame_width,$cell_width)
{
	$year_week=0;
	$columns=11;
	$frame_width=$columns+1;
	$cell_width=100/$frame_width;
	$rows=7;
	$frame_height=$rows+1;
	$table_heading="Timetable";
	$row_counter=0;
	//$column_counter=1;
	echo "<table border='1' width='100%' style='align:center;'>";
		echo "<th colspan='$frame_width'>";
			echo $table_heading;
		echo "</th>";
		echo "<tr>";
			echo "<td colspan='$frame_width'>&nbsp;</td>";
		echo "</tr>";
		while ($row_counter<=$rows)
		{
			$column_counter=0;
			echo "<tr>";
			while ($column_counter<=$columns)
			{
				if ($row_counter==0)
				{
					echo "<td style='align:center;width:$cell_width%;'>&nbsp;</td>";
				}
				else
				{
					if ($column_counter==0)
					{
						echo "<td style='align:center;width:$cell_width%;'>&nbsp;</td>";
					}
					else
					{
						echo "<td style='align:center;width:$cell_width%;'>$row_counter$column_counter</td>";
					}
				}
				$column_counter++;
			}
			$row_counter++;
			echo "</tr>";
		}
	echo "</table>";
}
draw_table($columns,$rows,$table_heading,$frame_width,$cell_width,$year_week);
?>