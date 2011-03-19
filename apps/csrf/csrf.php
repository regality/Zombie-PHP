<?php
require_once("../brainz/app.php");

class Csrf extends App {
   public function execute($action) {
      $csrf = $this->get_csrf_token();
      $json = array("status" => "success",
                    "token" => $csrf);
      echo json_encode($json);
   }
}

?>
