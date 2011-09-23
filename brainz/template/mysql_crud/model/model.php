<?php

class <MODEL_CLASS_NAME> extends ModelBase {
   public function getAll() {
      $query = new MysqlQuery(
         'SELECT<SQL_FIELDS_COMMA_SEP>
          FROM <TABLE_NAME>
<SQL_JOINS>
          ORDER BY <TABLE_NAME>.id'
      );
      return $query->query();
   }

   public function getOne($id) {
      $query = new MysqlQuery(
         'SELECT<SQL_FIELDS_COMMA_SEP>
          FROM <TABLE_NAME>
<SQL_JOINS>
          WHERE <TABLE_NAME>.id = $1'
      );
      $query->addParam($id);
      return $query->query()->fetchOne();
   }

   public function delete($id) {
      $query = new MysqlQuery(
         'DELETE FROM <TABLE_NAME>
          WHERE id = $1'
      );
      $query->addParam($id);
      return $query->exec();
   }

   public function insert(<INSERT_FUNC_PARAMS_MODEL>) {
      $query = new MysqlQuery(
         'INSERT INTO <TABLE_NAME>
          (<INSERT_FIELDS_COMMA_SEP>)
          VALUES
          (DEFAULT, <INSERT_DOLLAR_PARAMS>)'
      );
<QUERY_INSERT_ADD_PARAMS>
      return $query->exec();
   }

   public function update($id,
                          <INSERT_FUNC_PARAMS_MODEL>) {
      $query = new MysqlQuery(
         'UPDATE <TABLE_NAME>
          SET<SET_FIELDS_COMMA_SEP>
          WHERE id = $1'
      );
      $query->addParam($id);
<QUERY_INSERT_ADD_PARAMS>
      return $query->exec();
   }

}

?>
