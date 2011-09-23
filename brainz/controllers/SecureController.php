<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

abstract class SecureController extends Controller {
   public function run($action = null, $request = null) {
      $this->prepare($action, $request);
      if (!isset($this->secure_methods) || in_array($this->action, $this->secure_methods)) {
         $access = $this->hasAccess();
      } else {
         $access = true;
      }
      if ($access === true) {
         $this->execute();
      } else {
         if ($this->format == 'json') {
            $this->json['status'] = $access;
            $this->renderJson();
         } else {
            echo $access;
         }
      }
   }

   public function hasAccess() {
      if (!$this->session->exists('username')) {
         return "logged out";
      }
      if (isset($this->groups)) {
         $user_groups = $this->session->get('groups');
         if (!is_array($user_groups)) {
            return "access denied";
         }
         foreach ($this->groups as $group) {
            foreach ($user_groups as $user_group) {
               if ($user_group == $group) {
                  return true;
               }
            }
         }
         return 'access denied';
      } else {
         return true;
      }
   }
}

?>
