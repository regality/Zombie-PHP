<?php
require_once("../brainz/app.php");

class Menu extends App {
   public function execute($action) {
      $this->apps = array(
         array("name" => "Home",
               "app" => "welcome",
               "cache" => true,
               "active" => true),
      );
      if ($this->session->is_set('username')) {
         /*
         array_push($this->apps, array("name" => "My First App",
                                       "cache" => true,
                                       "app" => "firstapp"));
         array_push($this->apps, array("name" => "Static Page",
                                       "cache" => true,
                                       "app" => "static_app"));
         */
         array_push($this->apps, array("name" => "Discussion",
                                       "cache" => false,
                                       "app" => "discuss"));
      } else {
         array_push($this->apps, array("name" => "Login",
                                       "cache" => false,
                                       "app" => "login"));
      }
      $groups = $this->session->get('groups');
      if (is_array($groups) && in_array("admin", $groups)) {
         array_push($this->apps, array("name" => "Users",
                                       "cache" => false,
                                       "app" => "users"));
         array_push($this->apps, array("name" => "Console",
                                       "cache" => true,
                                       "app" => "console"));
      }
      $this->render("menu/view.php");
   }
}

?>
