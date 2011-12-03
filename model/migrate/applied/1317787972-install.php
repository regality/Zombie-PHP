<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

require_once(__DIR__ . "/../../../zombie-core/util/rand.php");

$query = new MysqlQuery('
   CREATE TABLE users (
      id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(100) NOT NULL UNIQUE,
      firstname VARCHAR(100) NOT NULL,
      lastname VARCHAR(100) NOT NULL,
      salt CHAR(31) NOT NULL,
      password CHAR(60) NOT NULL
   )
');
$query->exec();

$query = new MysqlQuery('
   CREATE TABLE groups (
      id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(50) UNIQUE
   )
');
$query->exec();

$query = new MysqlQuery('
   CREATE TABLE users_groups (
      users_id INT NOT NULL,
      groups_id INT NOT NULL,
      INDEX users_groups_users_in (users_id),
      INDEX users_groups_groups_in (groups_id)
   )
');
$query->exec();

$query = new MysqlQuery('
   CREATE TABLE session (
      id CHAR(40) NOT NULL PRIMARY KEY,
      data VARCHAR(8192),
      last_access DATETIME NOT NULL,
      INDEX session_access_in (last_access)
   )
');
$query->exec();

$query = new MysqlQuery('
   INSERT INTO groups
   (name)
   VALUES
   ($1),
   ($2)
');
$query->addParam("admin");
$query->addParam("users");

echo "Info for admin user:\n";
echo "username: ";
$username = trim(fgets(STDIN));

echo "firstname: ";
$firstname = trim(fgets(STDIN));

echo "lastname: ";
$lastname = trim(fgets(STDIN));

echo "password: ";
$password = hash("sha256", trim(fgets(STDIN)));

$rand_bits = strongRand(32);
$rand_bits = preg_replace('/[\/=+]/', '', $rand_bits);
$rand_bits = substr($rand_bits, 0, 22);
$salt = '$2a$07$' . $rand_bits . '$';
$hash = crypt($password, $salt);

$query = new MysqlQuery('
   INSERT INTO users VALUES
   (username,
    firstname,
    lastname,
    salt,
    password)
   VALUES
   ($1, $2, $3, $4, $5)
');
$query->addParam($username);
$query->addParam($firstname);
$query->addParam($lastname);
$query->addParam($salt);
$query->addParam($password);

?>
