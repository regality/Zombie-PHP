<?php

require_once(__DIR__ . "/../util/util.php");
require_once(__DIR__ . "/../config.php");

abstract class SqlModelBase extends ModelBase {
   public function __construct() {
      parent::__construct();
      $this->config = get_zombie_config();
      $db_class = underscore_to_class($this->config['database']['type'] . '_' . 'database');
      $this->db = new $db_class($this->config['database']['host'],
                                $this->config['database']['user'],
                                $this->config['database']['pass'],
                                $this->config['database']['database']);
   }
}

?>
