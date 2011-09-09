<?php

require_once('util.php');
require_once(__DIR__ . '/../config.php');

function autoload_app($class) {
   $config = get_zombie_config();
   $slug = class_to_underscore($class);
   include($config['config']['zombie_root'] . '/apps/' . $slug . '/' . $slug . '.php');
}

function autoload_session($class) {
   if (substr($class, -7) == 'Session') {
      $config = get_zombie_config();
      $slug = class_to_underscore($class);
      include($config['config']['zombie_root'] . '/brainz/session/' . $slug . '.php');
   }
}

function autoload_database($class) {
   if (substr($class, -8) == 'Database') {
      $config = get_zombie_config();
      $slug = class_to_underscore($class);
      include($config['config']['zombie_root'] . '/brainz/database/' . $slug . '.php');
   }
}

function autoload_model($class) {
   if (substr($class, -5) == 'Model') {
      $config = get_zombie_config();
      $slug = class_to_underscore(substr($class, 0, strlen($class) - 5)); 
      include($config['config']['zombie_root'] . '/model/' . $slug . '.php');
   }
}

function autoload_model_base($class) {
   if (substr($class, -9) == 'ModelBase') {
      $config = get_zombie_config();
      $slug = class_to_underscore($class);
      include($config['config']['zombie_root'] . '/brainz/model/' . $slug . '.php');
   }
}

function autoload_controller($class) {
   if (substr($class, -10) == 'Controller') {
      $config = get_zombie_config();
      $slug = class_to_underscore($class);
      include($config['config']['zombie_root'] . '/brainz/controllers/' . $slug . '.php');
   }
}

spl_autoload_register('autoload_model');
spl_autoload_register('autoload_database');
spl_autoload_register('autoload_session');
spl_autoload_register('autoload_model_base');
spl_autoload_register('autoload_controller');
spl_autoload_register('autoload_app');

?>
