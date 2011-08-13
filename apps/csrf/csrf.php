<?php
require_once(__DIR__ . "/../../brainz/app/app.php");

class Csrf extends App {
   public function index_run($request) {
      $csrf = $this->get_csrf_token();
      $this->json = array("status" => "success",
                          "token" => $csrf);
      $this->format = 'json';
   }
}

?>
