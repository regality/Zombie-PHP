<?php

abstract class Model {
   public function __construct() {
      require(__DIR__ . "/../config.php");
      require_once($db_file);
      $this->db = new $db_class($db_host, $db_user, $db_pass, $database);
   }

   /// these used to be abstract
   public function get_all() {}
   public function get_one($id) {}
   public function delete($id) {}
   public function insert($request) {}
   public function update($id, $request) {}
}

?>
