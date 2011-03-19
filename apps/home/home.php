<?php

require_once("../brainz/app.php");

class Home extends App {
   public function execute($action) {
      require($this->app_root . "/menu/menu.php");
      $this->menu = new Menu();
      $this->render("home/view.php");
   }
}

?>
