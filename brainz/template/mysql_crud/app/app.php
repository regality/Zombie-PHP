<?php

class <CLASS_NAME> extends Controller {

   /*********************************************
    * run functions
    *********************************************/

   public function indexRun($request) {
      $<TABLE_NAME>_model = new <MODEL_CLASS_NAME>();
      $this->data['<TABLE_NAME>'] = $<TABLE_NAME>_model->getAll();
   }

   public function editRun($request) {
<MODEL_GET_ALL>
      $<TABLE_NAME>_model = new <MODEL_CLASS_NAME>();
      $this->data['<TABLE_NAME>'] = $<TABLE_NAME>_model->getOne($request['id']);
      $this->data['form_action'] = 'update';
   }

   public function newRun($request) {
<MODEL_GET_ALL>
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
      $<TABLE_NAME>_model = new <MODEL_CLASS_NAME>();
      $status = $<TABLE_NAME>_model->insert(<INSERT_FUNC_PARAMS_APP>);
      $this->json['status'] = ($status ? "success" : "failed");
   }

   public function updateSave($request) {
      $<TABLE_NAME>_model = new <MODEL_CLASS_NAME>();
      $status = $<TABLE_NAME>_model->update($request['id'], <INSERT_FUNC_PARAMS_APP>);
      $this->json['status'] = ($status ? "success" : "failed");
   }

   public function deleteSave($request) {
      $<TABLE_NAME>_model = new <MODEL_CLASS_NAME>();
      $status = $<TABLE_NAME>_model->delete($request['id']);
      $this->json['status'] = ($status ? "success" : "failed");
   }

}

?>
