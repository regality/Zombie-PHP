<?php

class <CLASS_NAME> extends Controller {

   /*********************************************
    * run functions
    *********************************************/

   public function index_run($request) {
      $<SLUG>_model = new <MODEL_CLASS_NAME>();
      $this-><SLUG> = $<SLUG>_model->get_all();
   }

   public function edit_run($request) {
<MODEL_GET_ALL>
      $<SLUG>_model = new <MODEL_CLASS_NAME>();
      $this-><SLUG> = $<SLUG>_model->get_one($request['id']);
      $this->form_action = 'update';
   }

   public function new_run($request) {
<MODEL_GET_ALL>
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
      $<SLUG>_model = new <MODEL_CLASS_NAME>();
      if ($<SLUG>_model->insert($request)) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function update_save($request) {
      $<SLUG>_model = new <MODEL_CLASS_NAME>();
      if ($<SLUG>_model->update($request['id'], $request)) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function delete_save($request) {
      $<SLUG>_model = new <MODEL_CLASS_NAME>();
      if ($<SLUG>_model->delete($request['id'])) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

}

?>
