<?php
function my_mac() //read MAC address
{
	ob_start(); // Turn on output buffering
	system('ipconfig /all'); //Execute external program to display output
	$mycom=ob_get_contents(); // Capture the output into a variable
	ob_clean(); // Clean (erase) the output buffer
	$findme = "Physical";
	$physical_mac = strpos($mycom, $findme); // Find the position of Physical text
	$mac=substr($mycom,($physical_mac+36),17); // Get Physical Address
	//echo $mac."<br>";
	return $mac;
}
?>