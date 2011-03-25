<?php
require_once(__DIR__ . "/../../brainz/app.php");

class Menu extends App {
   public function execute($action, $request) {
      if ($this->session->is_set('username')) {
         $this->title = "Welcome, " . $this->session->get('username');
      } else {
         $this->title = "Menu";
      }
      $this->apps = array(
         array("name" => "Home",
               "app" => "welcome",
               "cache" => false,
               "active" => true),
      );
      if ($this->session->is_set('username')) {
      } else {
         array_push($this->apps, array("name" => "Login",
                                       "cache" => false,
                                       "app" => "login"));
      }
      $groups = $this->session->get('groups');
      if ($this->in_group('admin')) {
         array_push($this->apps, array("name" => "Users",
                                       "cache" => false,
                                       "app" => "users"));
         array_push($this->apps, array("name" => "Groups",
                                       "cache" => false,
                                       "app" => "groups"));
         array_push($this->apps, array("name" => "Console",
                                       "cache" => true,
                                       "app" => "console"));
      }
      $this->render("menu/view.php");
   }
}

?>
