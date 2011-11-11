<?php

class Welcome extends Controller {
   public function indexRun($request) {
      $gs_file = $this->config['zombie_root'] . "/GETTING-STARTED";
      $getting_started = htmlentities(file_get_contents($gs_file));
      $this->data['getting_started'] = $getting_started;
   }
}

?>
