<?php

require_once(dirname(__FILE__) . "/../brainz/model.php");

class GroupsModel extends Model {
   public function get_all() {
      $query = "SELECT groups.id
                     , groups.name
                FROM groups
                ORDER BY name";
      $result = $this->db->exec($query, array());
      return $result;
   }

   public function get_one($id) {
      $query = "SELECT groups.id
                     , groups.name
                FROM groups
                WHERE id = $1";
      $params = array($id);
      $result = $this->db->exec($query, $params)->fetch_one();
      return $result;
   }

   public function delete($id) {
      $query = "DELETE FROM groups
                WHERE id = $1";
      $params = array($id);
      return (boolean)$this->db->exec($query, $params);
   }

   public function insert($request) {
      $query = "INSERT INTO groups
                     ( id
                     , name)
                VALUES
                     (DEFAULT, $1)";
      $params = array($request['name']);
      return (boolean)$this->db->exec($query, $params);
   }

   public function update($id, $request) {
      $query = "UPDATE groups
                SET name = $2
                WHERE id = $1";
      $params = array($request['id'],
                      $request['name']);
      return (boolean)$this->db->exec($query, $params);
   }

}

?>
