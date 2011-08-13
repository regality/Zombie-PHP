#!/usr/bin/php
<?php

if (count($argv) < 2) {
   die ("Usage: zombie.php table_name\n");
}

require("brainz/config.php");
require("brainz/util/util.php");
require($db_file);
$db = new $db_class($db_host, $db_user, $db_pass, $database);

$slug = strtolower($argv[1]);
$table = $slug;
$class = underscore_to_class($slug);
$model_class = $class . "Model";

$replace['CLASS_NAME'] = $class;
$replace['MODEL_CLASS_NAME'] = $model_class;
$replace['SLUG'] = $slug;
$replace['TABLE_NAME'] = $slug;

$replace['AJAX_COMMA_SEP_FIELDS'] = '';
$replace['AJAX_COMMA_SEP_FIELDS_WID'] = '';
$replace['ENUM_OPTIONS'] = '';
$replace['FIELD_NAME'] = '';
$replace['FIELD_NAME_NICE'] = '';
$replace['HTML_EDIT_FIELDS'] = '';
$replace['HTML_EDIT_FIELDS'] = '';
$replace['HTML_FIELDS_TD'] = '';
$replace['HTML_FIELDS_TH'] = '';
$replace['INSERT_FIELDS_COMMA_SEP'] = '';
$replace['INSERT_DOLLAR_PARAMS'] = '';
$replace['INSERT_REQUEST_PARAMS'] = '';
$replace['JOIN_FIELD'] = '';
$replace['MODEL_GET_ALL'] = '';
$replace['REQUIRED_CLASS'] = '';
$replace['SET_FIELDS_COMMA_SEP'] = '';
$replace['SQL_FIELDS_COMMA_SEP'] = '';
$replace['SQL_JOINS'] = '';
$replace['UPDATE_REQUEST_PARAMS'] = '';

if (file_exists("model/$slug.php")) {
   die("model already exists\n");
}
@mkdir("apps/$slug") or die("directory already exists\n");
mkdir("apps/$slug/views");

$model_template = file_get_contents(dirname(__FILE__) . "/brainz/template/model/template.php");
$control_template = file_get_contents(dirname(__FILE__) . "/brainz/template/apps/sql_template/template.php");
$index_template = file_get_contents(dirname(__FILE__) . "/brainz/template/apps/sql_template/views/index.php");
$edit_template = file_get_contents(dirname(__FILE__) . "/brainz/template/apps/sql_template/views/edit.php");

$textbox_template = file_get_contents(dirname(__FILE__) . "/brainz/template/apps/sql_template/views/textbox.php");
$textarea_template = file_get_contents(dirname(__FILE__) . "/brainz/template/apps/sql_template/views/textarea.php");
$table_select_template = file_get_contents(dirname(__FILE__) . "/brainz/template/apps/sql_template/views/table_select.php");
$enum_select_template = file_get_contents(dirname(__FILE__) . "/brainz/template/apps/sql_template/views/enum_select.php");

$table_desc = $db->desc($table);
$i = 0;
foreach ($table_desc as $sql_field) {
   ++$i;
   $field_name = $sql_field['Field'];
   $field_name_nice = ucwords(str_replace("_", " ", $field_name));
   $field_type = $sql_field['Type'];
   if ($field_type == "text") {
      $html_type = "textarea";
   } else if (preg_match('/_id$/', $field_name)) {
      $html_type = "select";
   } else if (strpos($field_type, 'enum') === 0) {
      $html_type = "select";
   } else {
      $html_type = "input";
   }

   $replace['INSERT_FIELDS_COMMA_SEP'] .= " " . $sql_field['Field'] . "\n                     ,";

   $replace['UPDATE_REQUEST_PARAMS'] .= "\$request['$field_name'],\n                      ";

   $replace['SQL_FIELDS_COMMA_SEP'] .= 
      " " . $table . "." . $sql_field['Field'] . "\n                     ,";

   $replace['AJAX_COMMA_SEP_FIELDS_WID'] .= '                      "'. $field_name . '":$form.find("'. $html_type .'[name='. $field_name . "]\").val(),\n";

   if ($field_name != 'id') {
      $replace['AJAX_COMMA_SEP_FIELDS'] .= '                      "'. $field_name . '":$form.find("'. $html_type . '[name='. $field_name . "]\").val(),\n";

      if (preg_match('/_id$/', $field_name) == 0) {
         $replace['HTML_FIELDS_TD'] .= 
            "      <td><?= \$row['$field_name'] ?></td>\n";
         $replace['HTML_FIELDS_TH'] .= 
            "      <th>$field_name_nice</th>\n";
      }

      $replace['SET_FIELDS_COMMA_SEP'] .= 
         " " . $sql_field['Field'] . " = \$$i \n                  ,";

      $replace['INSERT_REQUEST_PARAMS'] .=
         "\$request['$field_name'],\n                      ";

      $replace['INSERT_DOLLAR_PARAMS'] .= "\$" . ($i - 1) . ", ";

      $replace['FIELD_NAME'] = $field_name;
      $replace['FIELD_NAME_NICE'] = $field_name_nice;
      if ($field_type == "text") {
         $replace['HTML_EDIT_FIELDS'] .= replace_fields($replace, $textarea_template);
      } else if (preg_match('/_id$/', $field_name)) {
         $other_table = substr($field_name, 0, strlen($field_name) - 3);
         $replace['MODEL_GET_ALL'] .= 
            "      \${$other_table}_model = \$this->get_model(\"$other_table\");\n" . 
            "      \$this->$other_table = \${$other_table}_model->get_all();\n";
         $join_field = get_table_join_field($other_table, $db);
         $replace['JOIN_FIELD'] = $join_field;
         if (strlen($join_field) > 0) {
            $replace['OTHER_TABLE_NAME'] = $other_table;
            $replace['SQL_JOINS'] .= "                LEFT JOIN $other_table ON $other_table.id = {$other_table}_id\n";
            $replace['SQL_FIELDS_COMMA_SEP'] .= " $other_table.$join_field {$other_table}_{$join_field}\n                     ,";
            $replace['HTML_FIELDS_TD'] .= 
               "      <td><?= \$row['{$other_table}_{$join_field}'] ?></td>\n";
            $field_name_nice = ucwords(str_replace("_", " ", $other_table . " " . $join_field));
            $replace['FIELD_NAME_NICE'] = $field_name_nice;
            $replace['HTML_FIELDS_TH'] .= 
               "      <th>$field_name_nice</th>\n";
            $replace['HTML_EDIT_FIELDS'] .= replace_fields($replace, $table_select_template);
         }
      } else if (strpos($field_type, 'enum') === 0) {
         $enums = explode(",",str_replace("'","",substr(substr($type,5),0, -1)));
         $replace['ENUM_OPTIONS'] = "";
         foreach ($enums as $enum) {
            $replace['ENUM_OPTIONS'] .= "            <option value=\"$enum\">$enum</option>\n";
         }
         $replace['HTML_EDIT_FIELDS'] .= replace_fields($replace, $enum_select_template);
      } else {
         $replace['HTML_EDIT_FIELDS'] .= replace_fields($replace, $textbox_template);
      }
   }

}

$replace['AJAX_COMMA_SEP_FIELDS'] = rtrim($replace['AJAX_COMMA_SEP_FIELDS'], "\n");
$replace['AJAX_COMMA_SEP_FIELDS_WID'] = rtrim($replace['AJAX_COMMA_SEP_FIELDS_WID'], "\n");
$replace['HTML_EDIT_FIELDS'] = rtrim($replace['HTML_EDIT_FIELDS'], "\n");
$replace['SQL_JOINS'] = rtrim($replace['SQL_JOINS'], "\n");
$replace['HTML_FIELDS_TH'] = rtrim($replace['HTML_FIELDS_TH'], "\n");
$replace['HTML_FIELDS_TD'] = rtrim($replace['HTML_FIELDS_TD'], "\n");
$replace['SQL_FIELDS_COMMA_SEP'] = rtrim($replace['SQL_FIELDS_COMMA_SEP'], " ,\n");
$replace['SET_FIELDS_COMMA_SEP'] = rtrim($replace['SET_FIELDS_COMMA_SEP'], " ,\n");
$replace['INSERT_DOLLAR_PARAMS'] = rtrim($replace['INSERT_DOLLAR_PARAMS'], " ,");
$replace['INSERT_FIELDS_COMMA_SEP'] = rtrim($replace['INSERT_FIELDS_COMMA_SEP'], " ,\n");
$replace['INSERT_REQUEST_PARAMS'] = rtrim($replace['INSERT_REQUEST_PARAMS'], " ,\n");
$replace['UPDATE_REQUEST_PARAMS'] = rtrim($replace['UPDATE_REQUEST_PARAMS'], " ,\n");

$model_file = replace_fields($replace, $model_template);
$control_file = replace_fields($replace, $control_template);
$index_file = replace_fields($replace, $index_template);
$edit_file = replace_fields($replace, $edit_template);

file_put_contents(dirname(__FILE__) . "/model/$slug.php", $model_file);
file_put_contents(dirname(__FILE__) . "/apps/$slug/$slug.php", $control_file);
file_put_contents(dirname(__FILE__) . "/apps/$slug/views/index.php", $index_file);
file_put_contents(dirname(__FILE__) . "/apps/$slug/views/edit.php", $edit_file);

function replace_fields($fields, $string) {
   foreach ($fields as $ml => $value) {
      $string = str_replace("<$ml>", $value, $string);
   }
   return $string;
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

?>
