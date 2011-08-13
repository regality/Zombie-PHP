<?php
require_once(__DIR__ . " /../../brainz/app/secure_app.php");

class Users extends SecureApp {
   public $groups = array('admin');

   public function index_run($request) {
      $users_model = $this->get_model("users");
      $this->users = $users_model->get_all();
   }

   public function edit_run($request) {
      $groups_model = $this->get_model("groups");
      $this->groups = $groups_model->get_all();

      $users_model = $this->get_model("users");
      $this->users = $users_model->get_one($request['id']);

      $this->form_action = 'update';
   }

   public function new_run($request) {
      $groups_model = $this->get_model("groups");
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

   public function create_save($request) {
      $users_model = $this->get_model("users");
      if ($users_model->insert($request)) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function update_save($request) {
      $users_model = $this->get_model("users");
      if ($users_model->update($request['id'], $request)) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function delete_save($request) {
      $users_model = $this->get_model("users");
      if ($users_model->delete($request['id'])) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

}

?>
