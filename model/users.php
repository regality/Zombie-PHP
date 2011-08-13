<?php

require_once(dirname(__FILE__) . "/../brainz/model/model.php");

class UsersModel extends Model {
   public function get_all() {
      $query = 'SELECT users.id
                     , users.username
                     , users.firstname
                     , users.lastname
                     , users.password
                     , users.groups
                FROM users
                ORDER BY username';
      $result = $this->db->exec($query);
      return $result;
   }

   public function get_one($id) {
      $query = 'SELECT users.id
                     , users.username
                     , users.firstname
                     , users.lastname
                     , users.password
                     , users.groups
                FROM users
                WHERE id = $1';
      $params = array($id);
      $result = $this->db->exec($query, $params)->fetch_one();
      $result['groups'] = unserialize($result['groups']);
      return $result;
   }

   public function delete($id) {
      $query = "DELETE FROM users
                WHERE id = $1";
      $params = array($id);
      return (boolean)$this->db->exec($query, $params);
   }

   public function insert($request) {
      $query = 'INSERT into users
                  ( id
                  , username
                  , firstname
                  , lastname
                  , password
                  , groups)
                VALUES
                  (DEFAULT, $1, $2, $3, $4, $5)';

      $params = array();
      $params[] = $request['username'];
      $params[] = $request['firstname'];
      $params[] = $request['lastname'];
      $params[] = $request['password'];
      $params[] = serialize($request['groups']);
      return (boolean)$this->db->exec($query, $params, false);
   }

   public function update($id, $request) {
      $query = 'UPDATE users
                SET username = $2 
                  , firstname = $3 
                  , lastname = $4 
                  , groups = $5
                WHERE id = $1';
      $params = array();
      $params[] = $request['id'];
      $params[] = $request['username'];
      $params[] = $request['firstname'];
      $params[] = $request['lastname'];
      $params[] = serialize($request['groups']);
      return (boolean)$this->db->exec($query, $params, false);
   }

   public function is_valid_user($username, $password) {
      $query = "SELECT id
                     , firstname
                     , lastname
                     , groups
                FROM users
                WHERE username = $1
                AND password = $2";
      $params = array($username, $password);
      $result = $this->db->exec($query, $params);
      return $result;
   }
}

?>
