<?php
require_once("../brainz/app.php");

class Welcome extends App {
   public function execute($action) {
      $this->render("welcome/view.php");
   }
}

?>
