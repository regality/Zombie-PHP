<?php

abstract class Session {
   abstract public function __construct();
   abstract public function save();
   abstract public function create();
   abstract public function set($a, $b = null);
   abstract public function get($key);
   abstract public function clear();
}

?>
