<?php
require_once(dirname(__FILE__) . "/../../brainz/app/secure_app.php");

class Groups extends SecureApp {

   /*********************************************
    * run functions
    *********************************************/

   public function index_run($request) {
      $groups_model = $this->get_model("groups");
      $this->groups = $groups_model->get_all();
   }

   public function edit_run($request) {

      $groups_model = $this->get_model("groups");
      $this->groups = $groups_model->get_one($request['id']);
      $this->form_action = 'update';
   }

   public function new_run($request) {

      $this->view = 'edit';
      $this->form_action = 'create';
   }

   public function update_run($request) {
   }

   public function delete_run($request) {
   }

   public function create_run($request) {
   }

   /*********************************************
    * save functions
    *********************************************/

   public function create_save($request) {
      $groups_model = $this->get_model("groups");
      if ($groups_model->insert($request)) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function update_save($request) {
      $groups_model = $this->get_model("groups");
      if ($groups_model->update($request['id'], $request)) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function delete_save($request) {
      $groups_model = $this->get_model("groups");
      if ($groups_model->delete($request['id'])) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

}

?>
