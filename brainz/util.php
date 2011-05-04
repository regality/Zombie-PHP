<?php

function class_to_underscore($class) {
   // AppName => app_name
   $underscore = preg_replace('/([A-Z])/', '_$1', $class);
   $underscore = strtolower($underscore);
   $underscore = trim($underscore, '_');
   return $underscore;
}

function underscore_to_class($underscore) {
   // app_name => AppName
   $class = str_replace('_', ' ', $underscore);
   $class = str_replace('/', '/ ', $class);
   $class = ucwords($class);
   $class = str_replace(' ', '', $class);
   return $class;
}

?>
