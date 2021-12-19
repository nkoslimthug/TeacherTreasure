<?php
$mac = shell_exec("ip a | grep -Po 'HWaddr \K.*$'");
var_dump($mac);
echo $mac;
?>
