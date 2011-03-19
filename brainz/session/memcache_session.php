<?php
require("session.php");

class MemcacheSession extends Session {
   private $session;
   private $session_id;
   private $mem;

   public function __construct() {
      $this->session = false;
      $this->mem = memcache_connect('localhost', 11211);
      if (isset($_COOKIE['sessid'])) {
         $this->session_id = $_COOKIE['sessid'];
         $session = memcache_get($this->mem, 'sess_' . $this->session_id);
      }
      if (!$session) {
         $this->create();
      } else {
         $this->session = unserialize($session);
         if (!is_array($this->session)) {
            $this->session = array();
         }
      }
   }

   public function save() {
      $session = serialize($this->session);
      memcache_set($this->mem,
                   'sess_' . $this->session_id,
                   $session,
                   1800);
   }

   public function create() {
      $this->session_id = sha1(time() + rand(1,10000000));
      setcookie('sessid',
                $this->session_id,
                time() + 60*30,
                '/',
                'wb.regaltic.com',
                false,
                true);
      $this->session = array();
   }

   public function get($key) {
      return $this->session[$key];
   }

   public function set($a, $b = null) {
      if (is_array($a)) {
         $this->session = array_merge($this->session, $a);
      } else {
         $this->session[$a] = $b;
      }
   }

   public function clear() {
      memcache_delete($this->mem, 'sess_' . $this->session_id);
      setcookie('sessid','',time() - 1);
   }

}

?>
