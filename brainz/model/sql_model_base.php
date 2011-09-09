<?php

require_once(__DIR__ . "/../util/util.php");
require_once(__DIR__ . "/../config.php");

abstract class SqlModelBase extends ModelBase {
   public function __construct() {
      parent::__construct();
      $config = get_zombie_config();
      $db_class = underscore_to_class($config['database']['type'] . '_' . 'database');
      $this->db = new $db_class($config['database']['host'],
                                $config['database']['user'],
                                $config['database']['pass'],
                                $config['database']['database']);
   }
}

?>
