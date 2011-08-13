<?php

require_once(dirname(__FILE__) . "/app.php");

abstract class Page extends App {
   public function run($action = null, $request = null) { 
      $this->is_page = true; 
      parent::run($action, $request);
   }
}

?>
