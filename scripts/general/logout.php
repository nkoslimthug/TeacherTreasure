<?php
	$root_path="../../";
	include($root_path."config/paths.php");
	include ($root_path."config/sungunura.php");
	session_destroy();
	header("Location:".$root_path."index.php");
	exit;
?>