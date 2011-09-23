<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

require("session.php");

class MemcacheSession extends Session {
   public static $instance;

   public $session;
   public $session_id;
   public $mem;

   public function __construct() {
      $this->session = false;
      $this->mem = memcache_connect('localhost', 11211);
      if (isset($_COOKIE['sessid'])) {
         $this->session_id = $_COOKIE['sessid'];
         $this->session = memcache_get($this->mem, 'sess_' . $this->session_id);
      }
      if ($this->session == false) {
         $this->create();
      } else {
         $this->session = unserialize($this->session);
         if (!is_array($this->session)) {
            $this->session = array();
         }
      }
   }

   public function __destruct() {
      if (!isset($this->deleted)) {
         $this->save();
      }
   }

   public static function get_session() {
      if (!isset(MemcacheSession::$instance)) {
         MemcacheSession::$instance = new MemcacheSession();
      }
      return MemcacheSession::$instance;
   }

   public function get_array() {
      return $this->session;
   }

   public function save() {
      $session = serialize($this->session);
      //memcache_set($this->mem, 'foo', 'bar', 0, 1800);
      memcache_set($this->mem,
                   'sess_' . $this->session_id,
                   $session,
                   0,
                   1800);
   }

   public function create() {
      $this->session_id = sha1(time() + rand(1,10000000));
      setcookie('sessid',
                $this->session_id,
                time() + 60*30,
                '/',
                $_SERVER['SERVER_NAME'],
                false,
                true);
      $this->session = array();
   }

   public function is_set($key) {
      return isset($this->session[$key]);
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
      $this->deleted = true;
      setcookie('sessid','',time() - 1);
   }

}

?>
