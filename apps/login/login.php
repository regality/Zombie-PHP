<?php

class Login extends BasicController {
   public function index_run($request) {
      if ($this->session->is_set("username")) {
         $this->view = "logged_in";
      }
   }
}

?>
