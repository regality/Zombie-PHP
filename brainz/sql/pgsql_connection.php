<?php

require("sql_connection.php");

class PgSqlConnection extends SqlConnection {
    private $db = false;
    private $errors = Array();
    private $numErrors = 0;

    public function __construct($server, $user, $pass, $db) {
        $this->db = pg_pconnect("host=$server dbname=$db user=$user password=$pass");
        return $this->db;
    }

    public function __destruct() {
    }

    public function exec($query, $params = array(), $html_safe = true, $debug = false) {
         // Process the request.
         if ($debug) {
             echo "<pre>" . $query . "</pre><br />";
         }
         if ($html_safe) {
            foreach ($params as &$p) {
               $p = htmlspecialchars($p);
            }
         }
         $result = pg_query_params($this->db, $query, $params);
         if (!$result) {
             echo "<b>Query Failed:</b><pre>" . $query . "</pre><br />";
         }
         
         // Check for errors.
         $pg_error = pg_last_error();

         // If there are errors, put them in the error list
         if (strlen($pg_error) > 0) {
             $this->errors .= $pg_error;
             echo $pg_error;
             return false;
         } else {
             return new PgSqlResult($result);
         }
    }

    public function begin() {
        return (pg_query("BEGIN") ? true : false);
    }

    public function commit() {
        return (pg_query("COMMIT") ? true : false);
    }

   public function get_errors() {
      return $this->errors();
   }

   public function desc($table_name) {
      return pg_meta_data($this->db, $table_name);
   }
}

class PgSqlResult extends SqlResult {
    private $result; // MySQL result
    private $row;
    private $position;

    public function __construct($pResult) {
        $this->result = $pResult;
        $this->row = null;
        $this->position = 0;
    }

    public function num_rows() {
        return pg_num_rows($this->result);
    }

    public function fetch_one() {
        return pg_fetch_assoc($this->result);
    }

    public function fetch_item($itemName) {
        $this->rewind();
        return $this->row[$itemName];
    }

    /******************************
     * Iterator functions
     ******************************/

    public function current() {
        return $this->row;
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        $this->row = pg_fetch_assoc($this->result);
        $this->position++;
    }

    public function rewind() {
        $this->position = 0;
        if ($this->num_rows() > 0) {
            pg_result_seek($this->result, 0);
        }
        $this->row = pg_fetch_assoc($this->result); 
    }

    public function valid() {
        return (boolean) $this->row;
    }
}

?>
