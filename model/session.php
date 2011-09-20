<?php

class SessionModel extends ModelBase {
   public function getSession($id) {
      $query = "SELECT data
                FROM session 
                WHERE id = $1";
      $params = array($id);
      $session = $this->db->exec($query, $params);
      if ($session->num_rows() == 1) {
         return $session->fetch_item('data');
      } else {
         return false;
      }
   }

   public function insert($id, $data) {
      $data = serialize($data);
      $query = "INSERT INTO session
                (id, data, last_access)
                VALUES
                ($1, $2, NOW())";
      $params = array($id, $data);
      return (boolean)$this->db->exec($query, $params, false);
   }

   public function update($id, $data) {
      $data = serialize($data);
      $query = "UPDATE session
                SET data = $1
                  , last_access = NOW()
                WHERE id = $2";
      $params = array($data, $id);
      return (boolean)$this->db->exec($query, $params, false);
   }

   public function updateId($new_id, $old_id) {
      $query = "UPDATE session
                SET id = $1
                WHERE id = $2";
      $params = array($new_id, $old_id);
      return (boolean)$this->db->exec($query, $params, false);
   }

   public function clearOld($timeout) {
      // only works for mysql?
      $query = "DELETE FROM session
                WHERE last_access < DATE_SUB(NOW(), INTERVAL " . intval($timeout) . " SECOND)";
      $this->db->exec($query);
   }

   public function destroy($id) {
      $query = 'DELETE FROM session WHERE id = $1';
      $params = array($id);
      return (boolean)$this->db->exec($query, $params);
   }
}

?>
