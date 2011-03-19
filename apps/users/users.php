<?php
require_once("../brainz/app.php");

class Users extends SecureApp {
   public $groups = array("admin");

   public function execute($action) {
      switch ($action) {
         case "edit":
            $this->edit_user($_REQUEST['id']);
            break;
         case "get":
            $this->user_json($_REQUEST['id']);
            break;
         default:
            $this->list_users();
      }
   }

   function list_users() {
      $query = 'SELECT * FROM users';
      $this->users = $this->sql->exec($query);
      $this->render("users/view.php");
   }

   function edit_user($id) {
      $this->user = $this->get_user($id);
      $this->groups = $this->get_groups();
      $this->render("users/edit.php");
   }

   function user_json($id) {
      echo json_encode($this->get_user($id));
   }

   function get_groups() {
      $query = 'SELECT id, name FROM groups ORDER BY NAME';
      $groups = $this->sql->exec($query);
      return $groups;
   }

   function get_user($id) {
      $query = 'SELECT * FROM users WHERE id = $1';
      $params = array($id);
      $user = $this->sql->exec($query, $params)->fetch_one();
      $user['groups'] = unserialize($user['groups']);
      return $user;
   }
}

?>
