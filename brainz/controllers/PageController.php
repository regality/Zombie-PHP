<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

abstract class PageController extends Controller {
   public function run($action = null, $request = null) { 
      $this->is_page = true; 
      parent::run($action, $request);
   }
}

?>
