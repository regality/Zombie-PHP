<?php

class Home extends PageController {
   public function defaultRun($request) {
      if ($this->config['env'] == 'prod') {
         require_once($this->config['zombie_root'] . "/config/version.php");
         $this->version = version();
      }
      $this->token = $this->getCsrfToken();
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
         $preload_class = underscoreToClass($this->action);
         $this->preload = new $preload_class();
      } else {
         $this->view = '404';
      }
   }
}

?>
