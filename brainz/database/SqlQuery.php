<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

require_once(__DIR__ . "/../util/util.php");
require_once(__DIR__ . "/../../config/config.php");

abstract class SqlException extends Exception { }

abstract class SqlQuery {
   abstract public function clear();
   abstract public function clearParams();
   abstract public function clearQuery();
   abstract public function exec($debug = false);
   abstract public function query($debug = false);
   abstract public function addParam($value, $type = null);
   abstract public function addParams($params);
   abstract public function begin();
   abstract public function rollback();
   abstract public function commit();
   abstract public function lastInsertId();
   abstract public function describe($table);
}

abstract class SqlResult implements Iterator {
   abstract public function __construct($pResult);
   abstract public function numRows();
   abstract public function fetchOne();
   abstract public function fetchItem($itemName);
}

?>
