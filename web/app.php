<?php

require("../brainz/config.php");
require("../brainz/util/util.php");
require("../brainz/util/autoload.php");

if (isset($_GET['app'])) {
   // sanitize the app name: only letters, numbers, underscores, and slashes
   $app = preg_replace('/[^0-9a-zA-Z_]/', '', $_GET['app']);  // => app_dir/foo
   $app_class = underscore_to_class($app);
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
