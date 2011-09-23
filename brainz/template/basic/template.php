<?php

require_once(__DIR__ . "/../ZombieTemplate.php");

class BasicTemplate extends ZombieTemplate {
   public function templatePrepare() {
      $this->addView('index');
   }

   public function templateExecute() {
   }
}

?>
