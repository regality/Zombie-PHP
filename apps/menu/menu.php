<?php

class Menu extends Controller {
   public function indexRun($request) {
      if ($this->session->exists('username')) {
         $this->title = "Welcome, " . $this->session->get('username');
      } else {
         $this->title = "Menu";
      }

      $this->apps['welcome'] = array("name" => "Home");
      if ($this->inGroup('admin')) {
         $this->apps['users'] = array("name" => "Users");
         $this->apps['groups'] = array("name" => "Groups");
      }
      if (!$this->session->exists('username')) {
         $this->apps['login'] = array("name" => "Login");
      } else {
         $this->apps['password'] = array("name" => "Change Password");
      }
      if ($this->config['env'] == 'dev') {
         $this->apps['console'] = array("name" => "Console");
      }
      $this->active = (isset($request['active']) ? $request['active'] : '');
   }
}

?>
