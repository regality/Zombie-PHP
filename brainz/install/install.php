<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

include(__DIR__ . '/../util/autoload.php');

$groups_model = new GroupsModel();
$users_model = new UsersModel();

$install_sql = file_get_contents(__DIR__ . "/install.mysql");
$sql_commands = explode(";", $install_sql);

foreach ($sql_commands as $sql) {
   if (strlen(trim($sql))) {
      $query = new MysqlQuery($sql);
      $query->exec();
   }
}

$groups_model->insert("admin");
$groups_model->insert("users");

$groups = $groups_model->getAll();
$user_groups = array();
foreach ($groups as $group) {
   if ($group['name'] == 'admin' ||
       $group['name'] == 'users') {
      array_push($user_groups, $group['id']);
   }
}

echo "Info for admin user:\n";
echo "username: ";
$username = trim(fgets(STDIN));

echo "firstname: ";
$firstname = trim(fgets(STDIN));

echo "lastname: ";
$lastname = trim(fgets(STDIN));

echo "password: ";
$password = hash("sha256", trim(fgets(STDIN)));

$users_model->insert($username,
                     $firstname,
                     $lastname,
                     $password,
                     $user_groups);

?>
