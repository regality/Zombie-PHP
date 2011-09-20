<?php

class Password extends SecureController {

   /*********************************************
    * run functions
    *********************************************/

   public function indexRun($request) {
   }

   public function updateRun($request) {
   }

   public function successRun($request) {
   }

   /*********************************************
    * save functions
    *********************************************/

   public function updateSave($request) {
      $users_model = new UsersModel();
      $success = $users_model->updateMyPassword($this->session->get("username"),
                                                  $request['old_password'],
                                                  $request['new_password']);
      if ($success) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
         $this->error('Could not update password.');
      }
   }

}

?>
