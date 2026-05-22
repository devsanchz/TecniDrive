<?php

$deafult = $db;

$sql = $db ->query("SELECT * FROM personas");


$sql = $db->fetch_assoc($sql);


?>