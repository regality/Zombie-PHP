<?php

class PhpSession extends Session {

   protected function __construct() {
      parent::__construct();
      session_set_cookie_params($this->config['session']['timeout'],
                                '/' . $this->config['web_root'],
                                $this->config['domain'],
                                $this->config['session']['secure'],
                                $this->config['session']['http_only']);
      $this->create();
      $this->prevent_hijack();
   }

   public function get_array() {
      return $_SESSION;
   }

   public function save() {
   }

   public function create() {
      session_start();
   }

   public function regenerate_id() {
      session_regenerate_id();
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

   public function destroy() {
      setcookie(session_name(),'',time() - 1);
      session_destroy();
   }

}

?>
