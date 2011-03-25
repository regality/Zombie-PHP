<?php

require_once(__DIR__ . "/../../brainz/app.php");

class Home extends App {
   public function execute($action, $request) {
      // prefetch 5 tokens
      $this->token_list = $this->get_csrf_token(5);
      require($this->app_root . "/menu/menu.php");
      $this->menu = new Menu();
      $this->render("home/view.php");
   }
}

?>
