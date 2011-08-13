<?php

require_once(__DIR__ . "/../../brainz/app.php");

class Home extends App {
   public function index_run($request) {
      $this->token = $this->get_csrf_token();
      require($this->app_root . "/menu/menu.php");
      $this->request = $request;
      $this->menu = new Menu();
   }
}

?>
