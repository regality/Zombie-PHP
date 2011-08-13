<?php

require_once(dirname(__FILE__) . "/secure_app.php");

abstract class SecurePage extends SecureApp {
   public function run($action = null, $request = null) { 
      $this->is_page = true; 
      parent::run($action, $request);
   }
}

?>
