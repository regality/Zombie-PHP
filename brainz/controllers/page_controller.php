<?php

abstract class PageController extends BasicController {
   public function run($action = null, $request = null) { 
      $this->is_page = true; 
      parent::run($action, $request);
   }
}

?>
