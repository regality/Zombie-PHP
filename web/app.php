<?php

require_once("../zombie-core/util/util.php");
require_once("../zombie-core/util/autoload.php");

function notFound() {
   class Tmp extends Controller {
      public function init() {
         $this->allowFormat("json");
         $this->allowFormat("xml");
         $this->allowFormat("serial");
      }
   }
   $c = new Tmp();
   $c->run();
}

if (isset($_GET['app'])) {
   // sanitize the app name: only letters, numbers, and underscores
   $app = preg_replace('/[^0-9a-zA-Z_]/', '', $_GET['app']);
   $app_file = "../apps/$app/$app.php";
   if (file_exists($app_file)) {
      require_once($app_file);
      $app_class = underscoreToClass($app);
      if (class_exists($app_class)) {
         $app = new $app_class();
         $app->run();
      } else {
         notFound();
      }
   } else {
      notFound();
   }
} else {
   notFound();
}

?>
