<?php
require_once(__DIR__ . "/../../brainz/app.php");

class Login extends App {
   public function execute($action, $request) {
      if (!$this->session->is_set("username")) {
         $this->render("login/view.php");
      } else {
         echo "logged in.";
      }
   }
}

?>
