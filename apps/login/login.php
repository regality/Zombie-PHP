<?php

class Login extends Controller {
   public function indexRun($request) {
      if ($this->session->exists("username")) {
         $this->view = "logged_in";
      }
   }
}

?>
