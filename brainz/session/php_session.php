<?php
require_once("session.php");
require_once(__DIR__ . "/../config.php");

class PhpSession extends Session {
   public static $instance;

   public function __construct() {
      if (!isset($_SESSION)) {
         $config = get_zombie_config();
         session_set_cookie_params($config['session']['timeout'],
                                   '/' . $config['config']['web_root'],
                                   $config['config']['domain'],
                                   false,
                                   true);
         session_start();
      }
   }

   public function get_array() {
      return $_SESSION;
   }

   public static function get_session() {
      if (!isset(PhpSession::$instance)) {
         PhpSession::$instance = new PhpSession();
      }
      return PhpSession::$instance;
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
