<?php

function classToUnderscore($class) {
   // AppName => app_name
   $underscore = preg_replace('/([A-Z])/', '_$1', $class);
   $underscore = strtolower($underscore);
   $underscore = trim($underscore, '_');
   return $underscore;
}

function underscoreToClass($underscore) {
   // app_name => AppName
   $class = str_replace('_', ' ', $underscore);
   $class = str_replace('/', '/ ', $class);
   $class = ucwords($class);
   $class = str_replace(' ', '', $class);
   return $class;
}

function underscoreToMethod($underscore) {
   // app_name => AppName
   $method = str_replace('_', ' ', $underscore);
   $method = str_replace('/', '/ ', $method);
   $method = ucwords($method);
   $method = str_replace(' ', '', $method);
   $method[0] = strtolower($method[0]);
   return $method;
}

?>
