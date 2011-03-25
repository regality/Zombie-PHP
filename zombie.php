#!/usr/bin/php
<?php

if (count($argv) < 3) {
   die("usage: {$argv[0]} create app|form\n");
} else {
   run_command($argv);
}

function run_command($argv) {
   $command = $argv[1];
   switch ($command) {
      case "create-app":
      case "create":
         create_app($argv[2]);
         break;
      case "create-form":
         create_form($argv[2], $argv[3]);
         break;
      default:
         echo "unkown command '" . $command . "'\n";
   }
   echo "done\n";
}

function create_form($app, $table) {
   if (strlen($app) == 0) {
      echo "no app name specified";
      return;
   } else if (strlen($table) == 0) {
      echo "no table name specified";
      return;
   }
   require('brainz/config.php');
   require('brainz/' . $db_file);
   $sql = new $db_class($db_server, $db_user, $db_pass, $database);
   $table_data = $sql->desc($table);
   //print_r($table_data);
   $edit_view = "<div class=\"close-button\" id=\"close-$app\"></div>\n" .
                "<div class=\"form\">\n" .
                "<table>\n";
   $list_view_open = "<div class=\"modal content-modal\" id=\"$app-modal\">\n" .
                     "   <div id=\"$app-ajax\" class=\"awesome basic-ajax\"></div>\n" .
                     "</div>\n" .
                     "<a href=\"#\" id=\"$app-new\">New +</a>\n" .
                     "<table>\n" .
                     "   <tr>\n";
   $list_view_close = "   <?php foreach (\$$table as \$row): ?>\n" .
                      "   <tr>\n";
   $ajax_fields = '';
   foreach ($table_data as $column => $meta) {
      if (is_int($column)) {
         $column = $meta['Field'];
      }
      if ($column != 'id') {
         if (ends_in($column, '_id')) {
            $label = slug_to_nice(substr($column, 0, -3));
            $foreign = substr($column, 0, -3);
            $list_view_close .= "      <td><?= \$row['{$foreign}_name'] ?></td>\n";
         } else {
            $label = slug_to_nice($column);
            $list_view_close .= "      <td><?= \$row['$column'] ?></td>\n";
         }
         $list_view_open .= "      <th>$label</th>\n";

         $edit_view .= "   <tr>\n";
         $edit_view .= "      <td>\n";
         $edit_view .= label($label);
         $edit_view .= "      </td>\n";
         $edit_view .= "      <td>\n";
      }
      if (isset($meta['type'])) {
         $type = $meta['type'];
      } else if (isset($meta['Type'])) {
         $type = $meta['Type'];
      } else {
         $type = "varchar";
      }

      if (isset($meta['not null'])) {
         $not_null = $meta['not null'];
      } else if (isset($meta['Null'])) {
         $not_null = ($meta['Null'] == "YES" ? false : true);
      } else {
         $not_null = false;
      }

      $ajax_type = (ends_in($column, '_id') ? 'select' : 'input');
      if ($type == "text") {
         $ajax_type = "textarea";
      }
      $ajax_fields .= "                      \"$column\":\$form.find(\"{$ajax_type}[name=$column]\").val(),\n";
      if ($column == 'id') {
         $edit_view .= hidden($column, $table);
      } else if (ends_in($column, "_id")) {
         $foreign = substr($column, 0, -3);
         $edit_view .= select_table($column, $table, $foreign, $not_null);
      } else if (strpos($type, 'varchar') === 0 ||
                 strpos($type, 'char') === 0 ||
                 $type == 'bpchar' ||
                 strpos($type, 'int') === 0) {
         $edit_view .= input_box($column, $table, $not_null);
      } else if ($type == 'bool') {
         $edit_view .= checkbox($column, $table, $not_null);
      } else if ($type == 'text') {
         $edit_view .= textarea($column, $table, $not_null);
      } else if (strpos($type, 'enum') === 0) {
         $enum = explode(",",str_replace("'","",substr(substr($type,5),0, -1)));
         $edit_view .= select_enum($column, $enum, $not_null);
      } else {
         $query = "select enumlabel from pg_enum where enumtypid = $1::regtype";
         $params = array($type);
         $enum = $sql->exec($query, $params);
         $edit_view .= select_enum($column, $enum, $not_null);
      }
      if ($column != 'id') {
         $edit_view .= "      </td>\n";
         $edit_view .= "   </tr>\n";
      }
   }
   $edit_view .= <<<EOD
   <tr>
      <td colspan='2'>
         <button class="$app-<?= \$form_action ?>"><?= \$form_action ?></button>
      </td>
   </tr>
</table>
</div>

EOD;
   $list_view_open .= "      <th></th>\n" .
                      "      <th></th>\n" .
                      "   </tr>\n";

   $list_view_close .= "      <td><a class=\"$app-edit\" href=\"#\" {$table}_id=\"<?= \$row['id'] ?>\">edit</a></td>\n" .
                       "      <td><a class=\"$app-delete\" href=\"#\" {$table}_id=\"<?= \$row['id'] ?>\">delete</a></td>\n" .
                       "   </tr>\n" .
                       "   <?php endforeach ?>\n" .
                       "</table>\n";
   $list_view_js = <<<EOD
<script type="text/javascript">
$(document).ready(function() {
   $(".$app-edit").click(function(e) {
      e.preventDefault();
      $("#$app-modal").fadeIn();
      $.ajax({"data":{"app":"$app",
                      "action":"edit",
                      "id":$(this).attr("{$table}_id")},
              "dataType":"html",
              "success":function(data) {
                  $("#$app-ajax").html(data);
              }
      });
   });

   $(".$app-delete").click(function(e) {
      e.preventDefault();
      \$row = $(this).parents("tr");
      $.ajax({"data":{"app":"$app",
                      "action":"delete",
                      "id":$(this).attr("{$table}_id")},
              "success":function(data) {
                  \$row.remove();
              }
      });
   });

   $("#$app-new").click(function(e) {
      e.preventDefault();
      $("#$app-modal").fadeIn();
      $.ajax({"data":{"app":"$app",
                      "action":"new"},
              "dataType":"html",
              "success":function(data) {
                  $("#$app-ajax").html(data);
              }
      });
   });

   $(".$app-create").die('click').live('click', function() {
      \$form = $(this).parents("div.form");
      if (!verify_form(\$form)) {
         alert("Some required fields are msising.");
         return;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"$app",
$ajax_fields
                      "action":"create"},
              "success":function(data) {
                  $("#$app-modal").fadeOut();
                  loadApp("$app", 0);
              }
      });
   });

   $(".$app-update").die('click').live('click', function() {
      \$form = $(this).parents("div.form");
      if (!verify_form(\$form)) {
         alert("Some required fields are msising.");
         return;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"$app",
$ajax_fields
                      "action":"update"},
              "success":function(data) {
                  $("#$app-modal").fadeOut();
                  loadApp("$app", 0);
              }
      });
   });

   $("#close-$app").die('click').live('click', function() {
      $("#$app-modal").fadeOut();
      $("#$app-edit").fadeOut(function() {
         $("#$app-edit").remove();
      });
   });
});
</script>

EOD;

   $list_view = $list_view_open . $list_view_close . $list_view_js;


   $class_file = open_class(slug_to_class($app)) .
                 table_class($app, $table, $table_data) .
                 close_class();

   mkdir("apps/$app") or die("directory already exists\n");
   file_put_contents("apps/$app/$app.php", $class_file);
   file_put_contents("apps/$app/view.php", $list_view);
   file_put_contents("apps/$app/edit.php", $edit_view);
}

function create_app($app) {
   if (strlen($app) == 0) {
      echo "no app name specified";
      return;
   }
   echo "creating app $app\n";
   mkdir("apps/$app");
   $class = slug_to_class($app);
   $class_file = basic_class($class, $app);
   $view_file = basic_view($class, $app);

   file_put_contents("apps/$app/$app.php", $class_file);
   file_put_contents("apps/$app/view.php", $view_file);
}

function slug_to_class($slug) {
   $class = str_replace(' ','', ucwords(str_replace('_', ' ', $slug)));
   return $class;
}

function slug_to_nice($slug) {
   $nice = ucwords(str_replace('_', ' ', $slug));
   return $nice;
}

/*******************************
 * Returns a simple app
 *******************************/
function basic_class($class, $app) {
   return open_class($class) .
          "      \$this->render(\"$app/view.php\");\n" .
          close_class();

}

function open_class($class) {
$open_class = <<<EOD
<?php
require_once(__DIR__ . " /../../brainz/app.php");

class $class extends App {
   public function execute(\$action, \$request) {

EOD;
return $open_class;
}

function close_class() {
$close_class = <<<EOD
   }
}

?>

EOD;
return $close_class;
}

function table_class($app, $table, $table_data) {
   $class_query = "      \$select = 'SELECT ";
   $comma = '';
   $foreign_queries = '';
   $joins = '';
   $insert_query = "         \$query = 'INSERT into $table\n";
   $insert_comma = "                     ( ";
   $insert_values = "                   )\n                   VALUES\n" .
                    "                     (";
   $values_comma = '';
   $update_query = "         \$query = 'UPDATE $table SET\n";
   $update_comma = "                       ";
   $params_list = '';
   $params_id = '';
   
   $i = 0;
   foreach ($table_data as $column => $meta) {
      if (is_int($column)) {
         $column = $meta['Field'];
      }
      ++$i;

      $insert_query .= $insert_comma . $column . "\n";
      if ($column == 'id') {
         $insert_values .= $values_comma . 'DEFAULT';
         $id_index = $i;
         $params_id = "         \$params[] = \$request['$column'];\n";
      } else {
         $k = $i - (isset($id_index) ? 1 : 0);
         $insert_values .= $values_comma . "\$$k";
         $update_query .= $update_comma . $column . " = \$$i " . "\n";
         $update_comma = "                     , ";
         $params_list .= "         \$params[] = \$request['$column'];\n";
      }
      $class_query .= $comma . $table . '.' . $column . "\n";

      $comma = "                      , ";
      $insert_comma = "                     , ";
      $values_comma = ", ";
      if (ends_in($column, '_id')) {
         $foreign = substr($column, 0, -3);
         $class_query .= $comma . $foreign . ".name {$foreign}_name\n";
         $foreign_queries .=
                  "         \$query_$foreign = 'SELECT id, name FROM $foreign ORDER BY name';\n" .
                  "         \$this->$foreign = \$this->sql->exec(\$query_$foreign);\n";
         $joins .= "      \$join .= ' LEFT JOIN $foreign on {$foreign}_id = {$foreign}.id ';\n";
      }
   }
   $insert_query .= $insert_values . ")';\n";
   $update_query .= "                   WHERE id = \$$id_index';";
   $class_query .= "                 FROM $table';\n";

   $class_content = <<<EOD
      \$id = (isset(\$request['id']) ? \$request['id'] : null);
      if (\$action != 'new') {
         \$this->get_$table(\$id, \$request);
      }
      \$this->get_other_tables();

      if (\$action == 'edit') {
         if (\$this->{$table}->num_rows() == 0) {
            echo "ERROR: item not found";
            return;
         } else {
            \$this->{$table} = \$this->{$table}->fetch_one();
         }
      }

      if (\$action == 'edit' || \$action == 'new') {
         \$this->form_action = (\$action == 'new' ? 'create' : 'update');
         \$this->render('$app/edit.php');
      } else if (\$action == '') {
         \$this->render('$app/view.php');
      } else {
         \$this->render_json();
      }
   }

   public function save(\$action, \$request) {
      if (\$action == 'create') {
$insert_query
         \$params = array();
$params_list
         if (\$this->sql->exec(\$query, \$params)) {
            \$this->json['status'] = "success";
         } else {
            \$this->json['status'] = "failed";
         }
      } else if (\$action == 'update') {
$update_query
         \$params = array();
$params_id
$params_list
         if (\$this->sql->exec(\$query, \$params)) {
            \$this->json['status'] = "success";
         } else {
            \$this->json['status'] = "failed";
         }
      } else if (\$action == 'delete') {
         \$query = 'DELETE FROM $table WHERE id = $1';
         \$params = array(\$request['id']);
         if (\$this->sql->exec(\$query, \$params)) {
            \$this->json['status'] = "success";
         } else {
            \$this->json['status'] = "failed";
         }
      }
   }

   public function get_$table(\$id, \$request) {
$class_query
      \$join = '';
$joins
      \$where = '';
      \$params = array();
      if (!is_null(\$id)) {
         \$where = " WHERE {$table}.id = $1";
         \$params[] = \$request['id'];
      }
      \$query = \$select . \$join . \$where;
      \$this->{$table} = \$this->sql->exec(\$query, \$params);
   }

   public function get_other_tables() {
$foreign_queries

EOD;
   return $class_content;
}

/*******************************
 * Returns a very simple view
 *******************************/
function basic_view($app) {
$basic_view = <<<EOD
<div class="$app">
$app app.
</div>
EOD;

return $basic_view;
}

function hidden($field_name, $table) {
   $id = <<<EOD
   <?php if (isset(\${$table}['$field_name'])): ?>
      <input type="hidden" value="<?= \${$table}['$field_name'] ?>" name="$field_name" />
   <?php endif ?>

EOD;
   return $id;
}

function label($name) {
   $label = <<<EOD
         <label>$name</label>

EOD;
   return $label;
}

/*******************************
 * Returns a text field.
 *******************************/
function input_box($field_name, $table, $required = false) {
   $class = ($required ? 'class="required"' : '');
   $box = <<<EOD
         <input $class type="text" name="$field_name" value="<?= (isset(\${$table}['$field_name']) ? htmlentities(\${$table}['$field_name']) : '') ?>" />

EOD;
   return $box;
}

/*******************************
 * Returns a text area.
 *******************************/
function textarea($field_name, $table, $required = false) {
   $class = ($required ? 'class="required"' : '');
   $box = <<<EOD
         <textarea $class name="$field_name"><?= (isset(\${$table}['$field_name']) ? htmlentities(\${$table}['$field_name']) : '') ?></textarea>

EOD;
   return $box;
}

/*******************************
 * Returns a checkbox.
 *******************************/
function checkbox($field_name, $table, $required = false) {
   $class = ($required ? 'class="required"' : '');
   $box = <<<EOD
         <input $class type="checkbox" value="<?= (isset(\${$table}['$field_name']) ? htmlentities(\${$table}['$field_name']) : '') ?>" />

EOD;
   return $box;
}

/*******************************
 * Returns select for choosing
 * from a separate table.
 *******************************/
function select_table($field_name, $table, $foreign, $required = false) {
   $class = ($required ? 'class="required"' : '');
   $box = <<<EOD
         <select name="$field_name" $class>
         <option value=""></option>
         <?php foreach (\$$foreign as \$option): ?>
            <option value="<?= \$option['id'] ?>" <?= ((isset(\${$table}['$field_name']) && (\${$table}['$field_name'] == \$option['id'])) ? 'selected' : '') ?>><?= \$option['name'] ?></option>
         <?php endforeach ?>
         </select>

EOD;
   return $box;
}

function select_enum($field_name, $options, $required = false) {
   $class = ($required ? 'class="required"' : '');
   $box = <<<EOD
         <select $class name="$field_name">
            <option value=""></option>

EOD;
   foreach ($options as $option) {
      $box .= "            <option value=\"{$option['enumlabel']}\">" .
              "{$option['enumlabel']}</option>\n";
   }
   $box .= <<<EOD
         </select>

EOD;
   return $box;
}

function ends_in($haystack, $needle) {
   if ($haystack === $needle) {
      return true;
   } else if (strlen($haystack) < strlen($needle)) {
      return false;
   } else if (strpos($haystack, $needle) !== false &&
             (strpos($haystack, $needle) -
              strlen($haystack) +
              strlen($needle) === 0)) {
      return true;
   } else {
      return false;
   }
}

?>
