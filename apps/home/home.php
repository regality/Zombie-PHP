<?php

require_once(__DIR__ . "/../../brainz/app/page.php");

class Home extends Page {
   public function default_run($request) {
      $this->token = $this->get_csrf_token();
      $this->request = $request;

      $this->menu = new Menu();
      $this->console = new Console();

      if ($this->action == 'index') {
         $this->action = 'welcome';
      }
      $this->preload_action = (isset($request['preload_action'])
                               ? $request['preload_action']
                               : "index");
      if (class_exists($this->action)) {
         $preload_class = underscore_to_class($this->action);
         $this->preload = new $preload_class();
      } else {
         $this->view = '404';
      }
   }
}

?>
