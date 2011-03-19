<?php
require_once("../brainz/app.php");

class Login extends App {
   public function execute($action) {
      if (!$this->session->is_set("username")) {
         $this->render("login/view.php");
      } else {
         echo "logged in.";
      }
   }
}

?>
