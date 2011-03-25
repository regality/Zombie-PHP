<?php

require("sql_connection.php");

class MySqlConnection extends SqlConnection {
    private $db = false;
    private $errors = "";

    public function __construct($server, $username, $password, $database) {
        $this->db = mysql_connect($server, $username, $password);
        mysql_select_db($database, $this->db);
        return $this->db;
    }

    public function __destruct() {
    }

    public function selectDb($database) {
        mysql_select_db($database, $this->db);
    }

    public function exec($query, $params = array(), $html_safe = true, $debug = false) {
        $p_query = $query;
        $matches = array();
        $key = 0;
        foreach ($params as $value) {
            ++$key;
            $match = '/\$' . $key . '\b/';
            $query = preg_replace($match, "", $query);
            while (preg_match($match, $p_query, $matches, PREG_OFFSET_CAPTURE) > 0) {
                $len = strlen($key) + 1;
                $value = stripslashes($value);
                $sanitary = "'" . mysql_real_escape_string(htmlspecialchars($value)) . "'";
                $p_query = substr_replace($p_query, $sanitary, $matches[0][1], $len);
            }
        }
        if (preg_match('/\$\d+\b/', $query, $match)) {
            trigger_error("Unkown SQL param in query: '" .
                          $match[0] . "'", E_USER_WARNING);
            return null;
        } else {
            if ($debug) {
               echo "<pre>" . $p_query . "</pre>";
            }
            // Process the request.
            $result = mysql_query($p_query, $this->db);

            // Check for errors.
            $my_error = mysql_error();

            // If there are errors, put them in the error list
            if (strlen($my_error) > 0) {
                $this->errors .= $my_error;
                return false;
            } else {
                return new MySqlResult($result);
            }
        }
    }

   public function get_errors() {
      return $this->errors;
   }

   public function begin() {
   }

   public function commit() {
   }
}

class MySqlResult extends SqlResult {
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
