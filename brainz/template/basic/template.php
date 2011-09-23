<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

require_once(__DIR__ . "/../ZombieTemplate.php");

class BasicTemplate extends ZombieTemplate {
   public function templatePrepare() {
      $this->addView('index');
   }

   public function templateExecute() {
   }
}

?>
