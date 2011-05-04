<?php
require_once(__DIR__ . "/../../brainz/app.php");

class Menu extends App {
   public function index_run($request) {
      $active = (isset($request['action']) ? $request['action'] : "welcome");
      if ($this->session->is_set('username')) {
         $this->title = "Welcome, " . $this->session->get('username');
      } else {
         $this->title = "Menu";
      }

      $this->apps['welcome'] = array("name" => "Home", "cache" => false);
      if (!$this->session->is_set('username')) {
         $this->apps['login'] = array("name" => "Login", "cache" => false);
      }
      if ($this->in_group('admin')) {
         $this->apps['users'] = array("name" => "Users", "cache" => false);
         $this->apps['groups'] = array("name" => "Groups", "cache" => false);
         $this->apps['console'] = array("name" => "Console", "cache" => true);
      }

      $this->apps[$active]['active'] = true;
   }
}

?>
