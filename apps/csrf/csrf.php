<?php

class Csrf extends Controller {
   public function indexRun($request) {
      $this->data['token'] = $this->getCsrfToken();
      $this->data['status'] = 'success';
   }
}

?>
