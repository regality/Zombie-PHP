<?php

$action = (isset($_REQUEST['a']) ? $_REQUEST['a'] : null);
require("../apps/home/home.php");
$app = new Home();
$app->run($action);

?>
