<?php

class <CLASS_NAME> extends Controller {

   /*********************************************
    * run functions
    *********************************************/

   public function indexRun($request) {
<MODEL_GET_ALL>
   }

   public function createRun($request) {
   }

   public function successRun($request) {
   }

   /*********************************************
    * save functions
    *********************************************/

   public function createSave($request) {
      $<TABLE_NAME>_model = new <MODEL_CLASS_NAME>();
      $status = $<TABLE_NAME>_model->insert(<INSERT_FUNC_PARAMS_APP>);
      $this->json['status'] = ($status ? "success" : "failed");
   }

}

?>
