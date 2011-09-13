<?php

class Csrf extends Controller {
   public function index_run($request) {
      $csrf = $this->get_csrf_token();
      $this->json = array("status" => "success",
                          "token" => $csrf);
      $this->format = 'json';
   }
}

?>
