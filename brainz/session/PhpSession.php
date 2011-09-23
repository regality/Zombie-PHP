<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

class PhpSession extends Session {

   protected function __construct() {
      parent::__construct();
      session_set_cookie_params($this->config['session']['timeout'],
                                '/' . $this->config['web_root'],
                                $this->config['domain'],
                                $this->config['session']['secure'],
                                $this->config['session']['http_only']);
      $this->create();
      $this->preventHijack();
   }

   public function getArray() {
      return $_SESSION;
   }

   public function save() {
   }

   public function create() {
      session_start();
   }

   public function regenerateId() {
      session_regenerate_id();
   }

   public function exists($key) {
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
