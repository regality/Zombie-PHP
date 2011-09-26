<?php

class GroupsModel extends ModelBase {
   public function getAll() {
      $query = new MysqlQuery(
         'SELECT groups.id
               , groups.name
          FROM groups
          ORDER BY groups.id'
      );
      return $query->query();
   }

   public function getOne($id) {
      $query = new MysqlQuery(
         'SELECT groups.id
               , groups.name
          FROM groups
          WHERE groups.id = $1'
      );
      $query->addParam($id);
      return $query->query()->fetchOne();
   }

   public function delete($id) {
      $query = new MysqlQuery(
         "DELETE FROM groups
          WHERE id = $1"
      );
      $query->addParam($id);
      $this->deleteUsersGroups($id);
      return $query->exec();
   }

   public function deleteUsersGroups($group_id) {
      $query = new MysqlQuery(
         "DELETE FROM users_groups
          WHERE groups_id = $1"
      );
      $query->addParam($group_id);
      return $query->exec();
   }

   public function insert($name) {
      $query = new MysqlQuery(
         "INSERT INTO groups
          (id, name)
          VALUES
          (DEFAULT, $1)"
      );
      $query->addParam($name);
      return $query->exec();
   }

   public function update($id, $name) {
      $query = new MysqlQuery(
         "UPDATE groups
          SET name = $2
          WHERE id = $1"
      );
      $query->addParam($id);
      $query->addParam($name);
      return $query->exec();
   }

}

?>
