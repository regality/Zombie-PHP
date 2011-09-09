<?php

class <CLASS_NAME> extends BasicController {

   /*********************************************
    * run functions
    *********************************************/

   public function index_run($request) {
<MODEL_GET_ALL>
   }

   public function create_run($request) {
   }

   public function success_run($request) {
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

}

?>
