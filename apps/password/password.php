<?php
require_once(dirname(__FILE__) . "/../../brainz/app/secure_app.php");

class Password extends SecureApp {

   /*********************************************
    * run functions
    *********************************************/

   public function index_run($request) {
   }

   public function update_run($request) {
   }

   public function success_run($request) {
   }

   /*********************************************
    * save functions
    *********************************************/

   public function update_save($request) {
      $users_model = $this->get_model("users");
      $success = $users_model->update_password($this->session->get("username"),
                                               $request['old_password'],
                                               $request['new_password']);
      if ($success) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

}

?>
