<?php
function update_mac($mac_file)
{
	$mac_file = './id.php';
	$handle = fopen($mac_file, 'w') or die('Cannot open file:  '.$mac_file);
	$current_mac = my_mac($mac_file); //get the read MAC address
	fwrite($handle,$current_mac); //store MAC address
	fclose ($handle);
	return $current_mac;
}
?>