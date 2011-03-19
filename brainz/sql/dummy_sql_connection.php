<?php

require("sql_connection.php");

class DummySqlConnection extends SqlConnection {
    private $errors = "";

    public function __construct() {
    }

    public function __destruct() {
    }

    public function exec($query, $params = array(), $debug = false) {
    }

    public function begin() {
    }

    public function commit() {
    }

    public function get_errors() {
    }
}

class DummySqlResult extends SqlResult {
    public function __construct($result) {
    }

    public function num_rows() {
    }

    public function fetch_one() {
    }

    public function fetch_item($itemName) {
    }

    /******************************
     * Iterator functions
     ******************************/

    public function current() {
    }

    public function key() {
    }

    public function next() {
    }

    public function rewind() {
    }

    public function valid() {
    }
}

?>
