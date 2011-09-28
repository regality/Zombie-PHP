<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

require_once(__DIR__ . "/../../../config/config.php");
require_once(__DIR__ . "/../../util/autoload.php");
require_once(__DIR__ . "/../ZombieTemplate.php");

class MysqlCrudTemplate extends ZombieTemplate {
   public function templatePrepare() {
      if (!isset($this->options['table'])) {
         die("table option required:\nzombie.php create-app app=<app> template=mysql_crud table=<table>\n");
      }
      $config = getZombieConfig();
      $this->addView('index');
      $this->addView('edit');
      $this->addScript('main');
      $this->addModel();

      $this->replace['AJAX_COMMA_SEP_FIELDS'] = '';
      $this->replace['AJAX_COMMA_SEP_FIELDS_WID'] = '';
      $this->replace['ENUM_OPTIONS'] = '';
      $this->replace['FIELD_NAME'] = '';
      $this->replace['FIELD_NAME_NICE'] = '';
      $this->replace['HTML_EDIT_FIELDS'] = '';
      $this->replace['HTML_EDIT_FIELDS'] = '';
      $this->replace['HTML_FIELDS_TD'] = '';
      $this->replace['HTML_FIELDS_TH'] = '';
      $this->replace['INSERT_FIELDS_COMMA_SEP'] = '';
      $this->replace['INSERT_DOLLAR_PARAMS'] = '';
      $this->replace['INSERT_FUNC_PARAMS_APP'] = '';
      $this->replace['INSERT_FUNC_PARAMS_MODEL'] = '';
      $this->replace['INSERT_REQUEST_PARAMS'] = '';
      $this->replace['INSERT_REQUEST_PARAMS'] = '';
      $this->replace['JOIN_FIELD'] = '';
      $this->replace['MODEL_GET_ALL'] = '';
      $this->replace['QUERY_INSERT_ADD_PARAMS'] = '';
      $this->replace['REQUIRED_CLASS'] = '';
      $this->replace['SET_FIELDS_COMMA_SEP'] = '';
      $this->replace['SQL_FIELDS_COMMA_SEP'] = '';
      $this->replace['SQL_JOINS'] = '';
      $this->replace['TABLE_NAME'] = $this->options['table'];
      $this->replace['UPDATE_REQUEST_PARAMS'] = '';

      $query = new MysqlQuery();
      $table_desc = $query->describe($this->options['table']);
      $i = 0;
      $field_templates = array();
      foreach ($table_desc as $sql_field) {
         ++$i;
         $validators = array();
         $field_name = $sql_field['Field'];
         $field_name_nice = ucwords(str_replace("_", " ", $field_name));
         $field_type = $sql_field['Type'];
         $is_join = false;

         if ($sql_field['Null'] == 'NO') {
            array_push($validators, "required");
         }

         if ($field_type == "text") {
            $html_type = "textarea";
            $field_template = $this->getField("textarea");
         } else if (preg_match('/_id$/', $field_name)) {
            $html_type = "select";
            $is_join = true;
            $field_template = $this->getField("table_select");
         } else if (strpos($field_type, 'enum') === 0) {
            $html_type = "select";
            $field_template = $this->getField("enum_select");
         } else {
            $html_type = "input";
            $field_template = $this->getField("textbox");
            $matches = array();
            if (preg_match('/char\((\d+)\)$/', $field_type, $matches)) {
               $len = $matches[1];
               $v = "maxlen=" . $len;
               array_push($validators, $v);
            } else if (preg_match('/^int/', $field_type, $matches)) {
               array_push($validators, "int");
            } else if (preg_match('/^decimal|^float|^double/', $field_type, $matches)) {
               array_push($validators, "number");
            }
         }

         if (count($validators) > 0) {
            $this->replace['VALIDATE'] = "validate=\"" . implode(",", $validators) . "\" ";
         } else {
            $this->replace['VALIDATE'] = "";
         }

         $this->replace['INSERT_FIELDS_COMMA_SEP'] .= " " . $sql_field['Field'] . "\n          ,";

         $this->replace['UPDATE_REQUEST_PARAMS'] .= "\$request['$field_name'],\n                      ";

         $this->replace['SQL_FIELDS_COMMA_SEP'] .= 
            " " . $this->options['table'] . "." . $sql_field['Field'] . "\n               ,";

         $this->replace['AJAX_COMMA_SEP_FIELDS_WID'] .= '                      "'. $field_name . '":form.find("'. $html_type .'[name='. $field_name . "]\").val(),\n";

         if ($field_name != 'id') {
            $this->replace['AJAX_COMMA_SEP_FIELDS'] .= '                      "'. $field_name . '":form.find("'. $html_type . '[name='. $field_name . "]\").val(),\n";

            if (!$is_join) {
               $this->replace['HTML_FIELDS_TD'] .= 
                  "      <td><?= \$row['$field_name'] ?></td>\n";
               $this->replace['HTML_FIELDS_TH'] .= 
                  "      <th>$field_name_nice</th>\n";
            }

            $this->replace['INSERT_FUNC_PARAMS_APP'] .=
               '$request[\'' . $field_name . "'],\n" . str_repeat(" ", 32 + strlen($this->replace['TABLE_NAME']));
            $this->replace['INSERT_FUNC_PARAMS_MODEL'] .=
               '$' . $field_name . ",\n                          ";
            $this->replace['QUERY_INSERT_ADD_PARAMS'] .=
               '      $query->addParam($' . $field_name . ');' . "\n";
            $this->replace['SET_FIELDS_COMMA_SEP'] .= 
               " " . $sql_field['Field'] . " = \$$i \n            ,";

            $this->replace['INSERT_REQUEST_PARAMS'] .=
               "\$request['$field_name'],\n                      ";

            $this->replace['INSERT_DOLLAR_PARAMS'] .= "\$" . ($i - 1) . ", ";

            $this->replace['FIELD_NAME'] = $field_name;
            $this->replace['FIELD_NAME_NICE'] = $field_name_nice;
            if ($field_type == "text") {
               $field_template->replace($this->replace);
               $this->replace['HTML_EDIT_FIELDS'] .= $field_template->getContents();
            } else if ($is_join) {
               $other_table = substr($field_name, 0, strlen($field_name) - 3);
               $other_table_model_class = underscoreToClass($other_table . '_' . 'model');
               $this->replace['MODEL_GET_ALL'] .= 
                  "      \${$other_table}_model = new $other_table_model_class();\n" . 
                  "      \$this->data['$other_table'] = \${$other_table}_model->getAll();\n";
               $join_field = $this->getTableJoinField($other_table);
               $this->replace['JOIN_FIELD'] = $join_field;
               if (strlen($join_field) > 0) {
                  $this->replace['OTHER_TABLE_NAME'] = $other_table;
                  $this->replace['SQL_JOINS'] .= "          LEFT JOIN $other_table ON $other_table.id = {$other_table}_id\n";
                  $this->replace['SQL_FIELDS_COMMA_SEP'] .= " $other_table.$join_field {$other_table}_{$join_field}\n               ,";
                  $this->replace['HTML_FIELDS_TD'] .= 
                     "      <td><?= \$row['{$other_table}_{$join_field}'] ?></td>\n";
                  $field_name_nice = ucwords(str_replace("_", " ", $other_table . " " . $join_field));
                  $this->replace['FIELD_NAME_NICE'] = $field_name_nice;
                  $this->replace['HTML_FIELDS_TH'] .= 
                     "      <th>$field_name_nice</th>\n";
                  $field_template->replace($this->replace);
                  $this->replace['HTML_EDIT_FIELDS'] .= $field_template->getContents();
               }
            } else if (strpos($field_type, 'enum') === 0) {
               $enums = explode(",",str_replace("'","",substr(substr($field_type,5),0, -1)));
               $this->replace['ENUM_OPTIONS'] = "";
               foreach ($enums as $enum) {
                  $this->replace['ENUM_OPTIONS'] .= "            <option value=\"$enum\">$enum</option>\n";
               }
               $field_template->replace($this->replace);
               $this->replace['HTML_EDIT_FIELDS'] .= $field_template->getContents();
            } else {
               $field_template->replace($this->replace);
               $this->replace['HTML_EDIT_FIELDS'] .= $field_template->getContents();
            }
         }

      }

      $this->replace['AJAX_COMMA_SEP_FIELDS'] = rtrim($this->replace['AJAX_COMMA_SEP_FIELDS'], "\n");
      $this->replace['AJAX_COMMA_SEP_FIELDS_WID'] = rtrim($this->replace['AJAX_COMMA_SEP_FIELDS_WID'], "\n");
      $this->replace['HTML_EDIT_FIELDS'] = rtrim($this->replace['HTML_EDIT_FIELDS'], "\n");
      $this->replace['SQL_JOINS'] = rtrim($this->replace['SQL_JOINS'], "\n");
      $this->replace['HTML_FIELDS_TH'] = rtrim($this->replace['HTML_FIELDS_TH'], "\n");
      $this->replace['HTML_FIELDS_TD'] = rtrim($this->replace['HTML_FIELDS_TD'], "\n");
      $this->replace['SQL_FIELDS_COMMA_SEP'] = rtrim($this->replace['SQL_FIELDS_COMMA_SEP'], " ,\n");
      $this->replace['SET_FIELDS_COMMA_SEP'] = rtrim($this->replace['SET_FIELDS_COMMA_SEP'], " ,\n");
      $this->replace['INSERT_DOLLAR_PARAMS'] = rtrim($this->replace['INSERT_DOLLAR_PARAMS'], " ,");
      $this->replace['INSERT_FIELDS_COMMA_SEP'] = rtrim($this->replace['INSERT_FIELDS_COMMA_SEP'], " ,\n");
      $this->replace['INSERT_REQUEST_PARAMS'] = rtrim($this->replace['INSERT_REQUEST_PARAMS'], " ,\n");
      $this->replace['UPDATE_REQUEST_PARAMS'] = rtrim($this->replace['UPDATE_REQUEST_PARAMS'], " ,\n");
      $this->replace['INSERT_FUNC_PARAMS_APP'] = rtrim($this->replace['INSERT_FUNC_PARAMS_APP'], " ,\n");
      $this->replace['INSERT_FUNC_PARAMS_MODEL'] = rtrim($this->replace['INSERT_FUNC_PARAMS_MODEL'], " ,\n");

   }

   public function templateExecute() {
   }

   function getTableJoinField($table) {
      $query = new MysqlQuery();
      $fields = $query->describe($table);
      $field_name = '';
      foreach ($fields as $field) {
         if ($field['Field'] == 'name') {
            return 'name';
         }
         if ($field_name == '' &&
             $field['Field'] != 'id' &&
             preg_match('/_id$/', $field['Field']) == 0) {
            $field_name = $field['Field'];
         }
      }
      return $field_name;
   }

}

?>
