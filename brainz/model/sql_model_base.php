<?php

abstract class SqlModelBase extends ModelBase {
   public function __construct() {
      parent::__construct();
      require(__DIR__ . "/../config.php");
      require_once($db_file);
      $this->db = new $db_class($db_host, $db_user, $db_pass, $database);
   }
}

?>
