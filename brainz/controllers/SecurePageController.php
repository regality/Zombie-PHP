<?php

abstract class SecurePageController extends SecureController {
   public function run($action = null, $request = null) { 
      $this->is_page = true; 
      parent::run($action, $request);
   }
}

?>
