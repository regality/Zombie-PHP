<?php

class Home extends PageController {
   public function defaultRun($request) {
      $this->data['env'] = $this->config['env'];
      $this->data['domain'] = $this->config['domain'];
      $this->data['mcookie'] = $_COOKIE['m'];
      $this->data['is_mobile'] = $this->is_mobile;
      $this->data['web_root'] = $this->config['web_root'];
      $this->data['token'] = $this->getCsrfToken();
      $this->data['request'] = $request;
      $this->data['menu'] = new Menu();
      $this->data['action'] = $this->action;
      $this->data['preload_action'] = (isset($request['preload_action'])
                                    ? $request['preload_action'] : "index");

      if ($this->config['env'] == 'prod') {
         require_once($this->config['zombie_root'] . "/config/version.php");
         $this->data['version'] = version();
      } else {
         $this->data['console'] = new Console();
      }

      if ($this->action == 'index') {
         $this->action = 'welcome';
      }

      if (class_exists($this->action)) {
         $preload_class = underscoreToClass($this->action);
         $this->data['preload'] = new $preload_class();
      } else {
         $this->view = '404';
      }
   }
}

?>
