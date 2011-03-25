<?php
require_once(__DIR__ . " /../../brainz/app.php");

class Users extends SecureApp {
   public $groups = array('admin');

   public function execute($action, $request) {
      $id = (isset($request['id']) ? $request['id'] : null);
      if ($action != 'new') {
         $this->get_users($id, $request);
      }
      $this->get_other_tables();

      if ($action == 'edit') {
         if ($this->users->num_rows() == 0) {
            echo "ERROR: item not found";
            return;
         } else {
            $this->users = $this->users->fetch_one();
            $this->users['groups'] = unserialize($this->users['groups']);
         }
      }

      if ($action == 'edit' || $action == 'new') {
         $this->form_action = ($action == 'new' ? 'create' : 'update');
         $this->render('users/edit.php');
      } else if ($action == '') {
         $this->render('users/view.php');
      } else {
         $this->render_json();
      }
   }

   public function save($action, $request) {
      if ($action == 'create') {
         $query = 'INSERT into users
                     ( id
                     , username
                     , firstname
                     , lastname
                     , password
                     , groups
                   )
                   VALUES
                     (DEFAULT, $1, $2, $3, $4, $5)';

         $params = array();
         $params[] = $request['username'];
         $params[] = $request['firstname'];
         $params[] = $request['lastname'];
         $params[] = $request['password'];
         $params[] = serialize($request['groups']);

         if ($this->sql->exec($query, $params, false)) {
            $this->json['status'] = "success";
         } else {
            $this->json['status'] = "failed";
         }
      } else if ($action == 'update') {
         $query = 'UPDATE users SET
                       username = $2 
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

         if ($this->sql->exec($query, $params, false)) {
            $this->json['status'] = "success";
         } else {
            $this->json['status'] = "failed";
         }
      } else if ($action == 'delete') {
         $query = 'DELETE FROM users WHERE id = $1';
         $params = array($request['id']);
         if ($this->sql->exec($query, $params)) {
            $this->json['status'] = "success";
         } else {
            $this->json['status'] = "failed";
         }
      }
   }

   public function get_users($id, $request) {
      $select = 'SELECT users.id
                      , users.username
                      , users.firstname
                      , users.lastname
                      , users.password
                      , users.groups
                 FROM users';
      $order = ' ORDER BY username';
      $where = '';
      $params = array();
      if (!is_null($id)) {
         $where = " WHERE users.id = $1";
         $params[] = $request['id'];
      }
      $query = $select . $where . $order;
      $this->users = $this->sql->exec($query, $params);
   }

   public function get_other_tables() {
      $query = 'SELECT id, name FROM groups ORDER BY name';
      $this->groups = $this->sql->exec($query);
   }
}

?>
