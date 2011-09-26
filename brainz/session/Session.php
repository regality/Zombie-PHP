<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

require_once(__DIR__ . "/../../config/config.php");

abstract class Session {
   protected $session;
   protected $session_id;
   protected $config;
   protected static $instance;

   protected function __construct() {
      $this->config = getZombieConfig();
   }

   public static function getSession() {
      $class = get_called_class();
      if (!isset($class::$instance)) {
         $class::$instance = new $class();
      }
      return $class::$instance;
   }

   public function preventHijack() {
      if (!$this->exists('REMOTE_ADDR')) {
         $this->set('REMOTE_ADDR', $_SERVER['REMOTE_ADDR']);
         $this->set('HTTP_USER_AGENT', $_SERVER['HTTP_USER_AGENT']);
      } else if (($this->config['session']['ip_sticky'] &&
                  $_SESSION['REMOTE_ADDR'] != $_SERVER['REMOTE_ADDR']) ||
                  $_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) {
         $this->clear();
      }
   }

   public function generateId() {
      // make this cryptographically strong
      return sha1(time() . rand() . rand());
   }

   abstract public function regenerateId();
   abstract public function save();
   abstract public function getArray();
   abstract public function create();
   abstract public function exists($a);
   abstract public function set($a, $b = null);
   abstract public function get($key);
   abstract public function destroy();
}

?>
