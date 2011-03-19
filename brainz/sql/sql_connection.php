<?php

abstract class SqlConnection {
    private $errors = "";

    abstract public function __construct($server, $username,
                                         $password, $database);
    abstract public function __destruct();
    abstract public function exec($query, $params = array(),
                                  $html_safe = true, $debug = false);
    abstract public function begin();
    abstract public function commit();
    abstract public function get_errors();
}

abstract class SqlResult implements Iterator {
    abstract public function __construct($result);
    abstract public function num_rows();
    abstract public function fetch_one();
    abstract public function fetch_item($itemName);

    /******************************
     * Iterator functions

    abstract public function current();
    abstract public function key();
    abstract public function next();
    abstract public function rewind();
    abstract public function valid();
    */
}

?>
