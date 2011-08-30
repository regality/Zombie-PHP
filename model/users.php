<?php

require_once(dirname(__FILE__) . "/../brainz/model/sql_model.php");

class UsersModel extends SqlModel {
   public function get_all() {
      $query = 'SELECT users.id
                     , users.username
                     , users.firstname
                     , users.lastname
                     , users.password
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
                FROM users
                WHERE id = $1';
      $params = array($id);
      $result = $this->db->exec($query, $params)->fetch_one();
      if (is_array($result)) {
         $result['groups'] = $this->get_user_groups($result['id']);
      }
      return $result;
   }

   public function get_user_groups($id) {
      $query = 'SELECT name
                FROM users_groups
                LEFT JOIN groups on groups_id = groups.id
                WHERE users_id = $1';
      $params = array($id);
      $result = $this->db->exec($query, $params);

      if ($result) {
         $groups_arr = array();
         foreach ($result as $group) {
            array_push($groups_arr, $group['name']);
         }
         return $groups_arr;
      } else {
         return false;
      }
   }

   public function delete($id) {
      $user_query = "DELETE FROM users
                     WHERE id = $1";
      $group_query = "DELETE FROM users_groups
                      WHERE users_id = $1";
      $params = array($id);
      return (boolean)$this->db->exec($user_query, $params) &&
             (boolean)$this->db->exec($group_query, $params);
   }

   public function insert($request) {
      $query = 'INSERT into users
                  ( id
                  , username
                  , firstname
                  , lastname
                  , salt
                  , password)
                VALUES
                  (DEFAULT, $1, $2, $3, $4, $5)';

      $salt = $this->gen_bcrypt_salt();

      $params = array();
      $params[] = $request['username'];
      $params[] = $request['firstname'];
      $params[] = $request['lastname'];
      $params[] = $salt;
      $params[] = $this->bcrypt($request['password'], $salt);

      if ((boolean) $this->db->exec($query, $params)) {
         $user_id = $this->db->last_insert_id('users');
         return $this->insert_users_groups($user_id, $request['groups']);
      } else {
         return false;
      }
   }

   public function gen_rsa_keys($passphrase) {
      // generate a 1024 bit rsa private key, returns a php resource, save to file
      $privateKey = openssl_pkey_new(array(
         'private_key_bits' => 512,
         'private_key_type' => OPENSSL_KEYTYPE_RSA,
      ));
      openssl_pkey_export($privateKey, $privateKeyStr, $passphrase);
       
      // get the public key $keyDetails['key'] from the private key;
      $keyDetails = openssl_pkey_get_details($privateKey);
      $publicKeyStr = $keyDetails['key'];

      return array("public" => $publicKeyStr,
                   "private" => $privateKeyStr);
   }

   public function gen_bcrypt_salt() {
      $rand_bits = fread(fopen('/dev/random', 'r'), 32);
      $rand_bits = base64_encode($rand_bits);
      $rand_bits = preg_replace('/[\/=+]/', '', $rand_bits);
      $rand_bits = substr($rand_bits, 0, 22);
      $salt = '$2a$07$' . $rand_bits . '$';
      return $salt;
   }

   public function bcrypt($password, $salt) {
      $hash = crypt($password, $salt);
      return $hash;
   }

   public function insert_users_groups($user_id, $groups_arr) {
      $query = 'DELETE FROM users_groups
                WHERE users_id = $1';
      $params = array($user_id);
      $this->db->exec($query, $params);

      $query = 'INSERT INTO users_groups
                 (users_id, groups_id)
                VALUES ';
      $i = 1;
      foreach ($groups_arr as $group_id) {
         ++$i;
         $query .= '($1, $' . $i . '),';
         array_push($params, $group_id);
      }
      $query = rtrim($query, ',');
      return (boolean) $this->db->exec($query, $params);
   }

   public function update($id, $request) {
      $query = 'UPDATE users
                SET username = $2 
                  , firstname = $3 
                  , lastname = $4 
                WHERE id = $1';
      $params = array();
      $params[] = $request['id'];
      $params[] = $request['username'];
      $params[] = $request['firstname'];
      $params[] = $request['lastname'];
      if ((boolean) $this->db->exec($query, $params)) {
         return $this->insert_users_groups($request['id'], $request['groups']);
      } else {
         return false;
      }
   }

   public function is_valid_user($username, $password) {
      $query = "SELECT id
                     , firstname
                     , lastname
                     , salt
                     , password
                FROM users
                WHERE username = $1";
      $params = array($username);
      $result = $this->db->exec($query, $params);
      if ($result->num_rows() == 1) {
         $user = $result->fetch_one();
         if ($this->bcrypt($password, $user['salt']) == $user['password']) {
            $user['groups'] = $this->get_user_groups($user['id']);
            return $user;
         } else {
            return false;
         }
      } else {
         return false;
      }
   }
}

?>
