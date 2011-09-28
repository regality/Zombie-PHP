<?php

class Csrf extends Controller {
   public function indexRun($request) {
      $this->json['token'] = $this->getCsrfToken();
      $this->json['status'] = 'success';
   }
}

?>
