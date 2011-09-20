<?php

class Users extends SecureController {
   public $groups = array('admin');

   public function indexRun($request) {
      $users_model = new UsersModel();
      $this->users = $users_model->getAll();
   }

   public function editRun($request) {
      $groups_model = new GroupsModel();
      $this->groups = $groups_model->getAll();

      $users_model = new UsersModel();
      $this->users = $users_model->getOne($request['id']);

      $this->form_action = 'update';
   }

   public function newRun($request) {
      $groups_model = new GroupsModel();
      $this->groups = $groups_model->getAll();

      $this->view = 'edit';
      $this->form_action = 'create';
   }

   public function updateRun($request) {
   }

   public function deleteRun($request) {
   }

   public function createRun($request) {
   }

   public function passwordRun($request) {
      $users_model = new UsersModel();
      $user = $users_model->getOne($request['id']);
      $this->username = $user['username'];
   }

   public function passwordUpdateRun($request) {
   }

   public function passwordUpdateSave($request) {
      $users_model = new UsersModel();
      $success = $users_model->updatePassword($request['id'],
                                               $request['new_password']);
      if ($success) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
         $this->error('Could not update password.');
      }
   }

   public function createSave($request) {
      $users_model = new UsersModel();
      $status = $users_model->insert($request['username'],
                                     $request['firstname'],
                                     $request['lastname'],
                                     $request['password'],
                                     $request['groups']);
      if ($status) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function updateSave($request) {
      $users_model = new UsersModel();
      $status = $users_model->update($request['id'],
                                     $request['username'],
                                     $request['firstname'],
                                     $request['lastname'],
                                     $request['groups']);
      if ($status) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function deleteSave($request) {
      $users_model = new UsersModel();
      $status = $users_model->delete($request['id']);
      if ($status) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

}

?>
