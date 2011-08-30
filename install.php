<?php

include('brainz/config.php');
include('model/groups.php');
include('model/users.php');

$groups_model = new GroupsModel();
$users_model = new UsersModel();
$db = new MysqlConnection("","","","");

$install_sql = file_get_contents("brainz/install.mysql");
$sql_commands = explode(";", $install_sql);

foreach ($sql_commands as $sql) {
   if (strlen(trim($sql))) {
      $db->exec($sql);
   }
}

$groups_model->insert(array("name" => "admin"));
$groups_model->insert(array("name" => "users"));

$groups = $groups_model->get_all();
$user_groups = array();
foreach ($groups as $group) {
   if ($group['name'] == 'admin' ||
       $group['name'] == 'users') {
      array_push($user_groups, $group['id']);
   }
}

$new_user = array(
   "username" => "admin",
   "firstname" => "rob",
   "lastname" => "zombie",
   "password" => hash("sha256", "brainz"),
   "groups" => $user_groups
);
$users_model->insert($new_user);

?>
