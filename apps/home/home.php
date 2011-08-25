<?php

require_once(__DIR__ . "/../../brainz/app/page.php");

class Home extends Page {
   public function index_run($request) {
      $this->token = $this->get_csrf_token();
      require($this->app_root . "/menu/menu.php");
      $this->request = $request;
      $this->menu = new Menu();

      $this->preload_app =  (isset($request['preload_app']) ? $request['preload_app'] : "welcome");
      $this->preload_action =  (isset($request['preload_action']) ? $request['preload_action'] : "index");
      $preload_class = underscore_to_class($this->preload_app);
      $this->preload = new $preload_class();
   }
}

?>
