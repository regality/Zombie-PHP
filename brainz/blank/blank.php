<?php
require_once("../brainz/app.php");

class Blank extends App {
   public function execute($action) {
      $this->render("blank/view.php");
   }
}

?>
