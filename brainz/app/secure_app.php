<?php

require_once(dirname(__FILE__) . "/app.php");

abstract class SecureApp extends App {
   public function run($action = null, $request = null) {
      $this->prepare($action, $request);
      
      if (!$this->session->is_set('username')) {
         if ($this->format == 'json') {
            $this->json['status'] = 'logged out';
            $this->render_json();
         } else {
            echo "logged out";
         }
         return;
      }
      if (isset($this->groups)) {
         $user_groups = $this->session->get('groups');
         if (!is_array($user_groups)) {
            if ($this->format == 'json') {
               $this->json['status'] = 'access denied';
               $this->render_json();
            } else {
               echo 'access denied';
            }
            return;
         }
         foreach ($this->groups as $group) {
            foreach ($user_groups as $user_group) {
               if ($user_group == $group) {
                  $this->execute();
                  return;
               }
            }
         }
         if ($this->format == 'json') {
            $this->json['status'] = 'access denied';
            $this->render_json();
         } else {
            echo 'access denied';
         }
      } else {
         $this->execute();
      }
   }
}

?>
