<?php
require_once(__DIR__ . "/../../brainz/app/app.php");

class Login extends App {
   public function index_run($request) {
      if ($this->session->is_set("username")) {
         $this->view = "logged_in";
      }
   }
}

?>
