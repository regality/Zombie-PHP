<?php

function __autoload($class) {
   require('util.php');
   include(__DIR__ . '/../config.php');
   if (substr($class, -5) == 'Model') {
      $slug = class_to_underscore(substr($class, strlen($class), -5)); 
      include($zombie_root . '/model/' . $slug . '.php');
   } else {
      $slug = class_to_underscore($class); 
      include($zombie_root . '/model/' . $slug . '.php');
      include($zombie_root . '/apps/' . $slug . '/' . $slug . '.php');
   }
}

?>
