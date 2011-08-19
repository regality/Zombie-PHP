<?php

require_once(__DIR__ . "/../zombie_template.php");

class BasicTemplate extends ZombieTemplate {
   public function template_prepare() {
      $this->add_view('index');
   }

   public function template_execute() {
   }
}

?>
