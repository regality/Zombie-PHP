<?php
require_once(__DIR__ . "/../../brainz/app.php");

class Csrf extends App {
   public function execute($action, $request) {
      $csrf = $this->get_csrf_token();
      $json = array("status" => "success",
                    "token" => $csrf);
      echo json_encode($json);
   }
}

?>
