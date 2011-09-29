<?php

require("../zombie-core/util/autoload.php");
require("../apps/home/home.php");
header("Content-Type: charset=utf-8");

$app = new Home();
$app->run();

?>
