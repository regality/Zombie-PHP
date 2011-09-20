<?php

class Csrf extends Controller {
   public function indexRun($request) {
      $csrf = $this->getCsrfToken();
      $this->json = array("status" => "success",
                          "token" => $csrf);
   }
}

?>
