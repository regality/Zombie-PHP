<?php

class Menu extends Controller {
   public function indexRun($request) {
      if ($this->session->exists('username')) {
         $this->data['title'] = "Welcome, " . $this->session->get('username');
      } else {
         $this->data['title'] = "Menu";
      }

      $this->data['logged_in'] = $this->session->exists("username");
      $this->data['apps'] = array();
      $this->data['apps']['welcome'] = array("name" => "Home", "refresh" => "no");
      if (!$this->session->exists('username')) {
         $this->data['apps']['login'] = array("name" => "Login");
      } else {
         $groups = $this->session->get("groups");
         if (in_array('admin', $groups)) {
            $this->data['apps']['users'] = array("name" => "Users");
            $this->data['apps']['groups'] = array("name" => "Groups");
         }
         $this->data['apps']['password'] = array("name" => "Change Password");
      }
      if ($this->config['env'] == 'dev') {
         $this->data['apps']['console'] = array("name" => "Console", "refresh" => "no");
      }
      $this->data['active'] = (isset($request['active']) ? $request['active'] : '');
   }
}

?>
