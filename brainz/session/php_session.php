<?php
require("session.php");

class PhpSession extends Session {

   public function __construct() {
      if (!isset($_SESSION)) {
         require(__DIR__ . "/../config.php");
         session_set_cookie_params('1800', '/' . $web_root, $domain, false, true);
         session_start();
      }
   }

   public function save() {
   }

   public function create() {
   }

   public function is_set($key) {
      return isset($_SESSION[$key]);
   }

   public function get($key) {
      if (isset($_SESSION[$key])) {
         return $_SESSION[$key];
      } else {
         return false;
      }
   }

   public function set($a, $b = null) {
      if (is_array($a)) {
         $_SESSION = array_merge($_SESSION, $a);
      } else {
         $_SESSION[$a] = $b;
      }
   }

   public function clear() {
      session_destroy();
      session_start();
   }

}

?>
