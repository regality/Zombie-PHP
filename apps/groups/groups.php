<?php

//class Groups extends SecureController {
class Groups extends Controller {
   public $groups = array('admin');

   /*********************************************
    * run functions
    *********************************************/

   public function indexRun($request) {
      $groups_model = new GroupsModel();
      $this->groups = $groups_model->getAll();
   }

   public function editRun($request) {
      $groups_model = new GroupsModel();
      $this->groups = $groups_model->getOne($request['id']);
      $this->form_action = 'update';
   }

   public function newRun($request) {
      $this->view = 'edit';
      $this->form_action = 'create';
   }

   public function updateRun($request) {
   }

   public function deleteRun($request) {
   }

   public function createRun($request) {
   }

   /*********************************************
    * save functions
    *********************************************/

   public function createSave($request) {
      $groups_model = new GroupsModel();
      $status = $groups_model->insert($request['name']);
      if ($status) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function updateSave($request) {
      $groups_model = new GroupsModel();
      $status = $groups_model->update($request['id'], $request['name']);
      if ($status) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function deleteSave($request) {
      $groups_model = new GroupsModel();
      $status = $groups_model->delete($request['id']);
      if ($status) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

}

?>
