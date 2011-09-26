<?php

require_once('util.php');
require_once(__DIR__ . '/../../config/config.php');
$config = getZombieConfig();
$GLOBALS['zombie_root'] =  $config['zombie_root'];

function autoloadApp($class) {
   $slug = classToUnderscore($class);
   @include($GLOBALS['zombie_root'] . '/apps/' . $slug . '/' . $slug . '.php');
}

function autoloadModel($class) {
   if (substr($class, -5) == 'Model') {
      $slug = classToUnderscore(substr($class, 0, strlen($class) - 5)); 
      include($GLOBALS['zombie_root'] . '/model/' . $slug . '.php');
   }
}

function autoloadSession($class) {
   if (substr($class, -7) == 'Session') {
      include($GLOBALS['zombie_root'] . '/brainz/session/' . $class . '.php');
   }
}

function autoloadQuery($class) {
   if (substr($class, -5) == 'Query') {
      include($GLOBALS['zombie_root'] . '/brainz/database/' . $class . '.php');
   }
}

function autoloadModelBase($class) {
   if (substr($class, -9) == 'ModelBase') {
      include($GLOBALS['zombie_root'] . '/brainz/model/' . $class . '.php');
   }
}

function autoloadController($class) {
   if (substr($class, -10) == 'Controller') {
      include($GLOBALS['zombie_root'] . '/brainz/controllers/' . $class . '.php');
   }
}

spl_autoload_register('autoloadModel');
spl_autoload_register('autoloadQuery');
spl_autoload_register('autoloadSession');
spl_autoload_register('autoloadModelBase');
spl_autoload_register('autoloadController');
spl_autoload_register('autoloadApp');

?>
