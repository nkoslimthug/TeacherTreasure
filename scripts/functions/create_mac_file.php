<?php
function create_mac($mac_file)
{
	$mac_file = './id.php';
	$handle = fopen($mac_file, 'w') or die('Cannot open file:  '.$mac_file); //implicitly creates file
}
?>