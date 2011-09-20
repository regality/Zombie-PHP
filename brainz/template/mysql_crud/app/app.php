<?php

class <CLASS_NAME> extends Controller {

   /*********************************************
    * run functions
    *********************************************/

   public function indexRun($request) {
      $<TABLE_NAME>_model = new <MODEL_CLASS_NAME>();
      $this-><TABLE_NAME> = $<TABLE_NAME>_model->getAll();
   }

   public function editRun($request) {
<MODEL_GET_ALL>
      $<TABLE_NAME>_model = new <MODEL_CLASS_NAME>();
      $this-><TABLE_NAME> = $<TABLE_NAME>_model->getOne($request['id']);
      $this->form_action = 'update';
   }

   public function newRun($request) {
<MODEL_GET_ALL>
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
      $<TABLE_NAME>_model = new <MODEL_CLASS_NAME>();
      $status = $<TABLE_NAME>_model->insert(<INSERT_FUNC_PARAMS_APP>);
      if ($status) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function updateSave($request) {
      $<TABLE_NAME>_model = new <MODEL_CLASS_NAME>();
      $status = $<TABLE_NAME>_model->update($request['id'], <INSERT_FUNC_PARAMS_APP>);
      if ($status) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function deleteSave($request) {
      $<TABLE_NAME>_model = new <MODEL_CLASS_NAME>();
      if ($<TABLE_NAME>_model->delete($request['id'])) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

}

?>
