<?php

require_once(dirname(__FILE__) . "/../brainz/model/model.php");

class <MODEL_CLASS_NAME> extends Model {
   public function get_all() {
      $query = "SELECT<SQL_FIELDS_COMMA_SEP>
                FROM <TABLE_NAME>
<SQL_JOINS>
                ORDER BY <TABLE_NAME>.id";
      $result = $this->db->exec($query, array());
      return $result;
   }

   public function get_one($id) {
      $query = "SELECT<SQL_FIELDS_COMMA_SEP>
                FROM <TABLE_NAME>
<SQL_JOINS>
                WHERE <TABLE_NAME>.id = $1";
      $params = array($id);
      $result = $this->db->exec($query, $params);
      if ($result) {
         $result = $result->fetch_one();
      }
      return $result;
   }

   public function delete($id) {
      $query = "DELETE FROM <TABLE_NAME>
                WHERE id = $1";
      $params = array($id);
      return (boolean)$this->db->exec($query, $params);
   }

   public function insert($request) {
      $query = "INSERT INTO <TABLE_NAME>
                     (<INSERT_FIELDS_COMMA_SEP>)
                VALUES
                     (DEFAULT, <INSERT_DOLLAR_PARAMS>)";
      $params = array(<INSERT_REQUEST_PARAMS>);
      return (boolean)$this->db->exec($query, $params);
   }

   public function update($id, $request) {
      $query = "UPDATE <TABLE_NAME>
                SET<SET_FIELDS_COMMA_SEP>
                WHERE id = $1";
      $params = array(<UPDATE_REQUEST_PARAMS>);
      return (boolean)$this->db->exec($query, $params);
   }

}

?>
