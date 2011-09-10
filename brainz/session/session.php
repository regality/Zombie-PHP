<?php

require_once(__DIR__ . "/../config.php");

abstract class Session {
   protected $session;
   protected $session_id;
   protected $config;
   protected static $instance;

   protected function __construct() {
      $this->config = get_zombie_config();
   }

   public static function get_session() {
      $class = get_called_class();
      if (!isset($class::$instance)) {
         $class::$instance = new $class();
      }
      return $class::$instance;
   }

   public function prevent_hijack() {
      if (!$this->is_set('REMOTE_ADDR')) {
         $this->set('REMOTE_ADDR', $_SERVER['REMOTE_ADDR']);
         $this->set('HTTP_USER_AGENT', $_SERVER['HTTP_USER_AGENT']);
      } else if (($this->config['session']['ip_sticky'] &&
                  $_SESSION['REMOTE_ADDR'] != $_SERVER['REMOTE_ADDR']) ||
                  $_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) {
         $this->clear();
      }
   }

   public function generate_id() {
      // make this cryptographically strong
      return sha1(time() . rand() . rand());
   }

   abstract public function regenerate_id();
   abstract public function save();
   abstract public function get_array();
   abstract public function create();
   abstract public function set($a, $b = null);
   abstract public function get($key);
   abstract public function destroy();
}

?>
