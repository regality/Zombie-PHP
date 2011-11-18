<?php

class WrongPasswordException extends Exception { }

class UsersModel extends ModelBase {
   public function getAll() {
      $query = new MysqlQuery(
         'SELECT users.id
               , users.username
               , users.firstname
               , users.lastname
          FROM users
          ORDER BY username'
      );
      return $query->query();
   }

   public function getOne($id) {
      $query = new MysqlQuery(
         'SELECT id
               , username
               , firstname
               , lastname
          FROM users
          WHERE id = $1'
      );
      $query->addParam($id);
      $result = $query->query()->fetchOne();
      if (is_array($result)) {
         $result['groups'] = $this->getUserGroups($result['id']);
      }
      return $result;
   }

   public function getUserGroups($id) {
      $query = new MysqlQuery(
         'SELECT name
          FROM users_groups
          LEFT JOIN groups on groups_id = groups.id
          WHERE users_id = $1'
      );
      $query->addParam($id);
      $result = $query->query();

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
      $user_query = new MysqlQuery(
         'DELETE FROM users
          WHERE id = $1'
      );
      $user_query->addParam($id);

      $group_query = new MysqlQuery(
         'DELETE FROM users_groups
          WHERE users_id = $1'
      );
      $group_query->addParam($id);

      return $user_query->exec() && $group_query->exec();
   }

   public function insert($username, $firstname, $lastname,
                          $password, array $groups) {
      $query = new MysqlQuery(
         'INSERT into users
          (id, username, firstname, lastname, salt, password)
          VALUES
          (DEFAULT, $1, $2, $3, $4, $5)'
      );

      $salt = $this->genBcryptSalt();
      $password = $this->bcrypt($password, $salt);

      $query->addParam($username);
      $query->addParam($firstname);
      $query->addParam($lastname);
      $query->addParam($salt);
      $query->addParam($password);

      if ($query->exec()) {
         $user_id = $query->lastInsertId('users');
         return $this->insertUsersGroups($user_id, $groups);
      } else {
         return false;
      }
   }

   public function getRSAKeys($passphrase) {
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

   public function genBcryptSalt() {
      require_once($this->config['zombie_root'] . "/zombie-core/util/rand.php");
      $rand_bits = strongRand(32);
      $rand_bits = preg_replace('/[\/=+]/', '', $rand_bits);
      $rand_bits = substr($rand_bits, 0, 22);
      $salt = '$2a$07$' . $rand_bits . '$';
      return $salt;
   }

   public function bcrypt($password, $salt) {
      $hash = crypt($password, $salt);
      return $hash;
   }

   public function insertUsersGroups($user_id, array $groups_arr) {
      $query = new MysqlQuery(
         'DELETE FROM users_groups
          WHERE users_id = $1'
      );
      $query->addParam($user_id);
      $query->exec();

      $query_str = 'INSERT INTO users_groups
                    (users_id, groups_id)
                    VALUES ';
      for ($i = 0; $i < count($groups_arr); ++$i) {
         $query_str .= '($1, $' . ($i + 2) . '),';
      }
      $query_str = rtrim($query_str, ',');
      $query = new MysqlQuery($query_str);
      $query->addParam($user_id);
      $query->addParams($groups_arr);
      return $query->exec();
   }

   public function update($id, $username, $firstname,
                          $lastname, array $groups) {
      $query = new MysqlQuery(
         'UPDATE users
          SET username = $2
            , firstname = $3
            , lastname = $4
          WHERE id = $1'
      );
      $query->addParam($id);
      $query->addParam($username);
      $query->addParam($firstname);
      $query->addParam($lastname);
      if ($query->exec() !== false) {
         return $this->insertUsersGroups($id, $groups);
      } else {
         return false;
      }
   }

   public function isValidUser($username, $password) {
      $query = new MysqlQuery(
         'SELECT id
               , firstname
               , lastname
               , salt
               , password
          FROM users
          WHERE username = $1'
      );
      $query->addParam($username);
      $result = $query->query();
      if ($result->numRows() == 1) {
         $user = $result->fetchOne();
         if ($this->bcrypt($password, $user['salt']) == $user['password']) {
            $user['groups'] = $this->getUserGroups($user['id']);
            return $user;
         } else {
            return false;
         }
      } else {
         return false;
      }
   }

   public function updateMyPassword($username,
                                    $old_password,
                                    $new_password) {
      $user = $this->isValidUser($username, $old_password);
      if ($user) {
         $user_id = $user['id'];
         return $this->updatePassword($user_id, $new_password);
      } else {
         throw new WrongPasswordException("Wrong password");
      }
   }

   public function updatePassword($user_id, $new_password) {
      $salt = $this->genBcryptSalt();
      $pass_hash = $this->bcrypt($new_password, $salt);
      $query = new MysqlQuery(
         'UPDATE users
          SET salt = $1
            , password = $2
          WHERE id = $3'
      );
      $query->addParam($salt);
      $query->addParam($pass_hash);
      $query->addParam($user_id);
      return $query->exec();
   }
}

?>
