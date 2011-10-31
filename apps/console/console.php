<?php

class Console extends Controller {
   public function indexRun($request) {
      $this->data['env'] = $this->config['env'];
   }
}

?>
