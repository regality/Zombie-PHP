<?php

class Csrf extends Controller {
   public function init() {
      $this->defaultFormat("json");
      $this->disallowFormat("html");
   }

   public function indexRun($request) {
      $this->data['token'] = $this->getCsrfToken();
      $this->data['status'] = 'success';
   }
}

?>
