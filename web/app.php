<?php

require_once("../zombie-core/util/util.php");
require_once("../zombie-core/util/autoload.php");

if (isset($_GET['app'])) {
   // sanitize the app name: only letters, numbers, and underscores
   $app = preg_replace('/[^0-9a-zA-Z_]/', '', $_GET['app']);
   require_once("../apps/$app/$app.php");
   $app_class = underscoreToClass($app);
   if (class_exists($app_class)) {
      $app = new $app_class();
      $app->run();
   } else {
      echo "unkown app: $app";
   }
} else {
   echo "no app specified";
}

?>
