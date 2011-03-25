<?php
require_once(__DIR__ . " /../../brainz/app.php");

class Groups extends App {
   public function execute($action, $request) {
      $id = (isset($request['id']) ? $request['id'] : null);
      if ($action != 'new') {
         $this->get_groups($id, $request);
      }
      $this->get_other_tables();

      if ($action == 'edit') {
         if ($this->groups->num_rows() == 0) {
            echo "ERROR: item not found";
            return;
         } else {
            $this->groups = $this->groups->fetch_one();
         }
      }

      if ($action == 'edit' || $action == 'new') {
         $this->form_action = ($action == 'new' ? 'create' : 'update');
         $this->render('groups/edit.php');
      } else if ($action == '') {
         $this->render('groups/view.php');
      } else {
         $this->render_json();
      }
   }

   public function save($action, $request) {
      if ($action == 'create') {
         $query = 'INSERT into groups
                     ( id
                     , name
                   )
                   VALUES
                     (DEFAULT, $1)';

         $params = array();
         $params[] = $request['name'];

         if ($this->sql->exec($query, $params)) {
            $this->json['status'] = "success";
         } else {
            $this->json['status'] = "failed";
         }
      } else if ($action == 'update') {
         $query = 'UPDATE groups SET
                       name = $2 
                   WHERE id = $1';
         $params = array();
         $params[] = $request['id'];

         $params[] = $request['name'];

         if ($this->sql->exec($query, $params)) {
            $this->json['status'] = "success";
         } else {
            $this->json['status'] = "failed";
         }
      } else if ($action == 'delete') {
         $query = 'DELETE FROM groups WHERE id = $1';
         $params = array($request['id']);
         if ($this->sql->exec($query, $params)) {
            $this->json['status'] = "success";
         } else {
            $this->json['status'] = "failed";
         }
      }
   }

   public function get_groups($id, $request) {
      $select = 'SELECT groups.id
                      , groups.name
                 FROM groups';

      $join = '';

      $where = '';
      $params = array();
      if (!is_null($id)) {
         $where = " WHERE groups.id = $1";
         $params[] = $request['id'];
      }
      $query = $select . $join . $where;
      $this->groups = $this->sql->exec($query, $params);
   }

   public function get_other_tables() {

   }
}

?>
