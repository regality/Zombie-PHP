<?php

class MysqlException extends Exception { }

class MysqlDatabase extends SqlDatabase {
   public static $db = false;

   public function __construct($server, $username, $password, $database) {
      if (!$this->is_connected()) {
         MysqlDatabase::$db = mysql_pconnect($server, $username, $password);
         mysql_select_db($database, MysqlDatabase::$db);
      }
      return MysqlDatabase::$db;
   }

   public function __destruct() {
   }

   public function select_db($database) {
      mysql_select_db($database, MysqlDatabase::$db);
   }

   public function is_connected() {
     return (boolean) MysqlDatabase::$db;
   }

   public function exec($query, $params = array(),
                        $html_safe = true, $debug = false) {
      $o_query = $query;
      $p_query = $query;
      $matches = array();
      $key = 0;
      foreach ($params as $value) {
         ++$key;
         $match = '/\$' . $key . '\b/';
         $query = preg_replace($match, "", $query);
         while (preg_match($match, $p_query, $matches, PREG_OFFSET_CAPTURE) > 0) {
             $len = strlen($key) + 1;
             if (get_magic_quotes_gpc()) {
                $value = stripslashes($value);
             }
             if ($html_safe) {
                 $value = htmlspecialchars($value);
             }
             $sanitary = "'" . mysql_real_escape_string($value) . "'";
             $p_query = substr_replace($p_query, $sanitary, $matches[0][1], $len);
         }
      }
      if (preg_match('/\$\d+\b/', $query, $match)) {
         throw new MysqlException("Wrong number of params in query: " .  $o_query);
      } else {
         if ($debug) {
            echo "<pre>" . $p_query . "</pre>";
         }
         // Process the request.
         $result = mysql_query($p_query, MysqlDatabase::$db);

         // Check for errors.
         $my_error = mysql_error();
         if (strlen($my_error) > 0) {
             throw new MysqlException("Mysql Error: " . $my_error);
         }

         return new MysqlResult($result);
      }
   }

   public function last_insert_id($table = null) {
      return mysql_insert_id();
   }

   public function begin() {
   }

   public function commit() {
   }

   public function desc($table) {
     $query = "DESCRIBE $table";
     $result = $this->exec($query);
     return $result;
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

   public function num_rows() {
      return mysql_num_rows($this->result);
   }

   public function fetch_one() {
      return mysql_fetch_assoc($this->result);
   }

   public function fetch_item($itemName) {
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
      if ($this->num_rows() > 0) {
         mysql_data_seek($this->result, 0);
      }
      $this->row_data = mysql_fetch_assoc($this->result); 
   }

   public function valid() {
      return (boolean) $this->row_data;
   }
}

?>
