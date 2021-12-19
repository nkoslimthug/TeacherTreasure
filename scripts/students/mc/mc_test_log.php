<?php
$root_path="../../";
include ($root_path."config/paths.php");
include ($root_path."config/sungunura.php");
include ("./mc_questions_store.php");
include ("./mc_questions_load.php");
include ("../../functions/summary_load.php");


mc_questions_store();		//Store detailed questions on disk_free_space
mc_questions_load();		//Load data file into database
summary_load();				//Populate summary table

?>