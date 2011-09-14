<?php

class Users extends SecureController {
   public $groups = array('admin');

   public function index_run($request) {
      $users_model = new UsersModel();
      $this->users = $users_model->get_all();
   }

   public function edit_run($request) {
      $groups_model = new GroupsModel();
      $this->groups = $groups_model->get_all();

      $users_model = new UsersModel();
      $this->users = $users_model->get_one($request['id']);

      $this->form_action = 'update';
   }

   public function new_run($request) {
      $groups_model = new GroupsModel();
      $this->groups = $groups_model->get_all();

      $this->view = 'edit';
      $this->form_action = 'create';
   }

   public function update_run($request) {
   }

   public function delete_run($request) {
   }

   public function create_run($request) {
   }

   public function password_run($request) {
      $users_model = new UsersModel();
      $user = $users_model->get_one($request['id']);
      $this->username = $user['username'];
   }

   public function password_update_run($request) {
   }

   public function password_update_save($request) {
      $users_model = new UsersModel();
      $success = $users_model->update_password($request['id'],
                                               $request['new_password']);
      if ($success) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
         $this->error('Could not update password.');
      }
   }

   public function create_save($request) {
      $users_model = new UsersModel();
      if ($users_model->insert($request)) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function update_save($request) {
      $users_model = new UsersModel();
      if ($users_model->update($request['id'], $request)) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function delete_save($request) {
      $users_model = new UsersModel();
      if ($users_model->delete($request['id'])) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

}

?>
