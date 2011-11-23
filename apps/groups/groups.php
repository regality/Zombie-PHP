<?php

class Groups extends SecureController {
   public $groups = array('admin');

   public function init() {
      $this->allowFormat("json");
   }

   /*********************************************
    * run functions
    *********************************************/

   public function indexRun($request) {
      $groups_model = new GroupsModel();
      $this->data['groups'] = $groups_model->getAll();
   }

   public function editRun($request) {
      $groups_model = new GroupsModel();
      $this->data['groups'] = $groups_model->getOne($request['id']);
      $this->data['form_action'] = 'update';
   }

   public function newRun($request) {
      $this->view = 'edit';
      $this->data['form_action'] = 'create';
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
         $this->data['status'] = "success";
      } else {
         $this->data['status'] = "failed";
      }
   }

   public function updateSave($request) {
      $groups_model = new GroupsModel();
      $status = $groups_model->update($request['id'], $request['name']);
      if ($status) {
         $this->data['status'] = "success";
      } else {
         $this->data['status'] = "failed";
      }
   }

   public function deleteSave($request) {
      $groups_model = new GroupsModel();
      $status = $groups_model->delete($request['id']);
      if ($status) {
         $this->data['status'] = "success";
      } else {
         $this->data['status'] = "failed";
      }
   }

}

?>
