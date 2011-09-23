<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

class DatabaseSession extends Session {
   private $session_model;
   private $state;

   protected function __construct() {
      parent::__construct();
      $this->session_model = new SessionModel();
      $this->session_model->clearOld($this->config['session']['timeout']);
      $this->session = false;
      if (isset($_COOKIE[session_name()])) {
         $this->session_id = $_COOKIE[session_name()];
         $this->session = $this->session_model->getSession($this->session_id);
      }
      if (!$this->session) {
         $this->create();
      } else {
         $this->session = unserialize($this->session);
         if (!is_array($this->session)) {
            $this->session = array();
            echo 'bad session';
         }
      }
   }

   public function __destruct() {
      $this->save();
   }

   public function getArray() {
      return $this->session;
   }

   public function save() {
      if ($this->state == 'new') {
         $this->session_model->insert($this->session_id, $this->session);
      } else {
         $this->session_model->update($this->session_id, $this->session);
      }
      $this->state = 'saved';
   }

   public function create() {
      $this->session_id = $this->generateId();
      $this->setCookie();
      $this->state = 'new';
      $this->session = array();
   }

   public function setCookie() {
      setcookie(session_name(),
                $this->session_id,
                time() + $this->config['session']['timeout'],
                '/',
                $_SERVER['SERVER_NAME'],
                false,
                true);
   }

   public function regenerateId() {
      $old_id = $this->session_id;
      $this->session_id = $this->generateId();
      $this->session_model->updateId($this->session_id, $old_id);
      $this->setCookie();
   }

   public function set($a, $b = null) {
      if (is_array($a)) {
         $this->session = array_merge($this->session, $a);
      } else {
         $this->session[$a] = $b;
      }
   }

   public function get($key) {
      if (isset($this->session[$key])) {
         return $this->session[$key];
      } else {
         return false;
      }
   }

   public function exists($key) {
      return isset($this->session[$key]);
   }

   public function destroy() {
      setcookie(session_name(),'',time() - 1);
      $this->session = array();
      $this->session_model->destroy($this->session_id);
   }
}

?>
