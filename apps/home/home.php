<?php

require_once(__DIR__ . "/../../brainz/app.php");

class Home extends App {
   public function index_run($request) {
      // prefetch 5 tokens
      $this->token_list = $this->get_csrf_token(5);
      require($this->app_root . "/menu/menu.php");
      $this->request = $request;
      $this->menu = new Menu();
   }
}

?>
