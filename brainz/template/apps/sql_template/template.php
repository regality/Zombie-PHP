<?php
require_once(dirname(__FILE__) . "/../../brainz/app/app.php");

class <CLASS_NAME> extends App {

   /*********************************************
    * run functions
    *********************************************/

   public function index_run($request) {
      $<SLUG>_model = $this->get_model("<SLUG>");
      $this-><SLUG> = $<SLUG>_model->get_all();
   }

   public function edit_run($request) {
<MODEL_GET_ALL>
      $<SLUG>_model = $this->get_model("<SLUG>");
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
      $<SLUG>_model = $this->get_model("<SLUG>");
      if ($<SLUG>_model->insert($request)) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function update_save($request) {
      $<SLUG>_model = $this->get_model("<SLUG>");
      if ($<SLUG>_model->update($request['id'], $request)) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

   public function delete_save($request) {
      $<SLUG>_model = $this->get_model("<SLUG>");
      if ($<SLUG>_model->delete($request['id'])) {
         $this->json['status'] = "success";
      } else {
         $this->json['status'] = "failed";
      }
   }

}

?>
