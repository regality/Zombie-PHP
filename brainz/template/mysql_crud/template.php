<?php

require_once(__DIR__ . "/../zombie_template.php");

class MysqlCrudTemplate extends ZombieTemplate {
   public function template_prepare() {
      if (!isset($this->options['table'])) {
      print_r($this->options);
         die("table option required:\nzombie.php mysql_crud <app> table=<table>\n");
      }
      require(__DIR__ . "/../../config.php");
      require($db_file);
      $this->db = new $db_class($db_host, $db_user, $db_pass, $database);
      $this->add_view('index');
      $this->add_view('edit');
      $this->add_model();

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
      $this->replace['INSERT_REQUEST_PARAMS'] = '';
      $this->replace['JOIN_FIELD'] = '';
      $this->replace['MODEL_GET_ALL'] = '';
      $this->replace['REQUIRED_CLASS'] = '';
      $this->replace['SET_FIELDS_COMMA_SEP'] = '';
      $this->replace['SQL_FIELDS_COMMA_SEP'] = '';
      $this->replace['SQL_JOINS'] = '';
      $this->replace['TABLE_NAME'] = $this->options['table'];
      $this->replace['UPDATE_REQUEST_PARAMS'] = '';

      $table_desc = $this->db->desc($this->options['table']);
      $i = 0;
      $field_templates = array();
      foreach ($table_desc as $sql_field) {
         ++$i;
         $field_name = $sql_field['Field'];
         $field_name_nice = ucwords(str_replace("_", " ", $field_name));
         $field_type = $sql_field['Type'];
         $is_join = false;
         if ($field_type == "text") {
            $html_type = "textarea";
            $field_template = $this->get_field("textarea");
         } else if (preg_match('/_id$/', $field_name)) {
            $html_type = "select";
            $is_join = true;
            $field_template = $this->get_field("table_select");
         } else if (strpos($field_type, 'enum') === 0) {
            $html_type = "select";
            $field_template = $this->get_field("enum_select");
         } else {
            $html_type = "input";
            $field_template = $this->get_field("textbox");
         }

         $this->replace['INSERT_FIELDS_COMMA_SEP'] .= " " . $sql_field['Field'] . "\n                     ,";

         $this->replace['UPDATE_REQUEST_PARAMS'] .= "\$request['$field_name'],\n                      ";

         $this->replace['SQL_FIELDS_COMMA_SEP'] .= 
            " " . $this->options['table'] . "." . $sql_field['Field'] . "\n                     ,";

         $this->replace['AJAX_COMMA_SEP_FIELDS_WID'] .= '                      "'. $field_name . '":$form.find("'. $html_type .'[name='. $field_name . "]\").val(),\n";

         if ($field_name != 'id') {
            $this->replace['AJAX_COMMA_SEP_FIELDS'] .= '                      "'. $field_name . '":$form.find("'. $html_type . '[name='. $field_name . "]\").val(),\n";

            if (!$is_join) {
               $this->replace['HTML_FIELDS_TD'] .= 
                  "      <td><?= \$row['$field_name'] ?></td>\n";
               $this->replace['HTML_FIELDS_TH'] .= 
                  "      <th>$field_name_nice</th>\n";
            }

            $this->replace['SET_FIELDS_COMMA_SEP'] .= 
               " " . $sql_field['Field'] . " = \$$i \n                  ,";

            $this->replace['INSERT_REQUEST_PARAMS'] .=
               "\$request['$field_name'],\n                      ";

            $this->replace['INSERT_DOLLAR_PARAMS'] .= "\$" . ($i - 1) . ", ";

            $this->replace['FIELD_NAME'] = $field_name;
            $this->replace['FIELD_NAME_NICE'] = $field_name_nice;
            if ($field_type == "text") {
               $field_template->replace($this->replace);
               $this->replace['HTML_EDIT_FIELDS'] .= $field_template->get_contents();
            } else if ($is_join) {
               $other_table = substr($field_name, 0, strlen($field_name) - 3);
               $this->replace['MODEL_GET_ALL'] .= 
                  "      \${$other_table}_model = \$this->get_model(\"$other_table\");\n" . 
                  "      \$this->$other_table = \${$other_table}_model->get_all();\n";
               $join_field = $this->get_table_join_field($other_table, $this->db);
               $this->replace['JOIN_FIELD'] = $join_field;
               if (strlen($join_field) > 0) {
                  $this->replace['OTHER_TABLE_NAME'] = $other_table;
                  $this->replace['SQL_JOINS'] .= "                LEFT JOIN $other_table ON $other_table.id = {$other_table}_id\n";
                  $this->replace['SQL_FIELDS_COMMA_SEP'] .= " $other_table.$join_field {$other_table}_{$join_field}\n                     ,";
                  $this->replace['HTML_FIELDS_TD'] .= 
                     "      <td><?= \$row['{$other_table}_{$join_field}'] ?></td>\n";
                  $field_name_nice = ucwords(str_replace("_", " ", $other_table . " " . $join_field));
                  $this->replace['FIELD_NAME_NICE'] = $field_name_nice;
                  $this->replace['HTML_FIELDS_TH'] .= 
                     "      <th>$field_name_nice</th>\n";
                  $field_template->replace($this->replace);
                  $this->replace['HTML_EDIT_FIELDS'] .= $field_template->get_contents();
               }
            } else if (strpos($field_type, 'enum') === 0) {
               $enums = explode(",",str_replace("'","",substr(substr($field_type,5),0, -1)));
               $this->replace['ENUM_OPTIONS'] = "";
               foreach ($enums as $enum) {
                  $this->replace['ENUM_OPTIONS'] .= "            <option value=\"$enum\">$enum</option>\n";
               }
               $field_template->replace($this->replace);
               $this->replace['HTML_EDIT_FIELDS'] .= $field_template->get_contents();
            } else {
               $field_template->replace($this->replace);
               $this->replace['HTML_EDIT_FIELDS'] .= $field_template->get_contents();
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

   }

   public function template_execute() {
   }

   function get_table_join_field($table, $db) {
      $fields = $db->desc($table);
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
