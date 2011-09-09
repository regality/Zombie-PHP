<?php

require_once('util.php');

function load_app($class) {
   include(__DIR__ . '/../config.php');
   $slug = class_to_underscore($class);
   include($zombie_root . '/apps/' . $slug . '/' . $slug . '.php');
}

function load_model($class) {
   if (substr($class, -5) == 'Model') {
      include(__DIR__ . '/../config.php');
      $slug = class_to_underscore(substr($class, 0, strlen($class) - 5)); 
      include($zombie_root . '/model/' . $slug . '.php');
   }
}

function load_model_base($class) {
   if (substr($class, -9) == 'ModelBase') {
      include(__DIR__ . '/../config.php');
      $slug = class_to_underscore($class);
      include($zombie_root . '/brainz/model/' . $slug . '.php');
   }
}

function load_controller($class) {
   if (substr($class, -10) == 'Controller') {
      include(__DIR__ . '/../config.php');
      $slug = class_to_underscore($class);
      include($zombie_root . '/brainz/controllers/' . $slug . '.php');
   }
}

spl_autoload_register('load_model');
spl_autoload_register('load_model_base');
spl_autoload_register('load_controller');
spl_autoload_register('load_app');

?>
