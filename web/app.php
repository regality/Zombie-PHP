<?php

if (isset($_GET['app'])) {
   $app = preg_replace('/[^a-zA-Z_]/', '', $_GET['app']);
   $app_class = str_replace(' ','', ucwords(str_replace('_', ' ', $app)));
   $app_file = '/var/www/zombie/apps/' . $app . '/' . $app . '.php';
   if (!file_exists($app_file)) {
      die("app not found");
   }
   include($app_file);
   if (class_exists($app_class)) {
      $app = new $app_class();
      $app->run();
   }
} else {
   echo "no app specified";
}

?>
