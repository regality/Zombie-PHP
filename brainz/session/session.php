<?php

abstract class Session {
   public $session;
   public $session_id;

   abstract public function __construct();
   abstract public static function get_session();
   abstract public function save();
   abstract public function get_array();
   abstract public function create();
   abstract public function set($a, $b = null);
   abstract public function get($key);
   abstract public function clear();
}

?>
