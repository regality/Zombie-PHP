<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

class MysqlException extends Exception { }

class MysqlQuery extends SqlQuery {
   private $query;
   private $params = array();
   private $param_count = 0;
   private $magic_quotes_on = false;
   private static $db = null;

   public function __construct($query = '', $params = array(), $connector = 'mysql') {
      if (MysqlQuery::$db == null) {
         $config = getZombieConfig();
         MysqlQuery::$db = mysql_connect($config[$connector]['host'],
                                         $config[$connector]['user'],
                                         $config[$connector]['pass']);
         mysql_select_db($config[$connector]['database'], MysqlQuery::$db);
      }
      if (get_magic_quotes_gpc()) {
         $this->magic_quotes_on = true;
      }
      $this->query = $query;
      $this->addParams($params);
   }

   public function selectDb($database) {
      mysql_select_db($database, MysqlQuery::$db);
   }

   public function clear() {
      $this->clearParams();
      $this->clearQuery();
   }

   public function clearParams() {
      $this->param_count = 0;
      $this->params = array();
   }

   public function clearQuery() {
      $this->query = null;
   }

   private function getMysqlResult($query, $debug = false) {
      if ($debug) {
         trigger_error("Query debug:" . $query, E_USER_NOTICE);
      }
      $result = mysql_query($query, MysqlQuery::$db);
      $error = mysql_error();
      if (strlen($error) > 0) {
          throw new MysqlException("Mysql Error: " . $error);
      }
      return $result;
   }

   public function exec($debug = false) {
      $bound_query = $this->getBoundQuery();
      $result = $this->getMysqlResult($bound_query, $debug);
      return mysql_affected_rows(MysqlQuery::$db);
   }

   public function query($debug = false) {
      $bound_query = $this->getBoundQuery();
      $result = $this->getMysqlResult($bound_query, $debug);
      return new MysqlResult($result);
   }

   private function getBoundQuery() {
      $query = $this->query;
      $bound_query = $this->query;
      foreach ($this->params as $key => $value) {
         $match = '/\$' . ($key + 1) . '\b/';
         $query = preg_replace($match, "", $query);
         while (preg_match($match, $bound_query, $matches, PREG_OFFSET_CAPTURE) > 0) {
            $len = strlen($key) + 1;
            $bound_query = substr_replace($bound_query, $value, $matches[0][1], $len);
         }
      }
      if (preg_match('/\$\d+\b/', $query, $match)) {
         throw new MysqlException("Wrong number of params in query: " .  $query);
      }
      return $bound_query;
   }

   public function addParams($params) {
      foreach ($params as $param) {
         $this->addParam($param);
      }
   }

   public function addParam($value, $type = null) {
      $this->params[$this->param_count] = $this->sanitize($value);
      $this->param_count += 1;
   }

   public function sanitize($value, $type = null) {
      if (is_null($type)) {
         $type = '';
      }
      if (is_string($value)) {
         if ($this->magic_quotes_on) {
            $value = stripslashes($value);
         }
         if ($type == "html") {
            $value = Model::purifyHtml($value);
         } else if ($type != "raw") {
            $value = htmlentities($value);
         }
         $value = "'" . mysql_real_escape_string($value) . "'";
      } else if (is_numeric($value)) {
         $value = (string)$value;
      } else if (is_bool($value)) {
         $value = (string)(int)$value;
      } else if (is_null($value)) {
         $value = "NULL";
      } else /* array, object, or unknown */ {
         $value = "'" . mysql_real_escape_string(serialize($value)) . "'";
      }
      return $value;
   }

   public function begin() {
      $this->getMysqlResult("SET autocommit = 0");
      $this->getMysqlResult("START TRANSACTION");
   }

   public function rollback() {
      $this->getMysqlResult("ROLLBACK");
   }

   public function commit() {
      $this->getMysqlResult("COMMIT");
   }

   public function lastInsertId($table = null) {
      return mysql_insert_id();
   }

   public function describe($table) {
     $query = "DESCRIBE $table";
     $result = $this->getMysqlResult($query);
     return new MysqlResult($result);
   }

}

class MysqlResult extends SqlResult {
   private $result;
   private $row_data;
   private $position;

   public function __construct($pResult) {
      $this->result = $pResult;
      $this->row_data = null;
      $this->position = 0;
   }

   public function numRows() {
      return mysql_num_rows($this->result);
   }

   public function fetchOne() {
      return mysql_fetch_assoc($this->result);
   }

   public function fetchItem($itemName) {
      $this->rewind();
      return $this->row_data[$itemName];
   }

   /******************************
    * Iterator functions
    ******************************/

   public function current() {
      return $this->row_data;
   }

   public function key() {
      return $this->position;
   }

   public function next() {
      $this->row_data = mysql_fetch_assoc($this->result);
      $this->position++;
   }

   public function rewind() {
      $this->position = 0;
      if ($this->numRows() > 0) {
         mysql_data_seek($this->result, 0);
      }
      $this->row_data = mysql_fetch_assoc($this->result); 
   }

   public function valid() {
      return (boolean) $this->row_data;
   }
}

?>
