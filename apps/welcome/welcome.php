<?php
require_once(__DIR__ . "/../../brainz/app.php");

class Welcome extends App {
   public function execute($action, $request) {
      $this->render("welcome/view.php");
   }
}

?>
