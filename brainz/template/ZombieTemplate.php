<?php
# Copyright (c) 2011, Regaltic LLC.  This file is
# licensed under the General Public License version 3.
# See the LICENSE file.

require_once(__DIR__ . "/../../config/config.php");

abstract class ZombieTemplate {
   public $options;
   public $replace;
   public $files;

   function __construct($template, $app, $options = array()) {
      $this->config = getZombieConfig();
      $this->template = $template;
      $this->app = strtolower($app);
      $this->options = $options;
   }

   public function run() {
      $this->prepare();
      $this->templatePrepare();
      $this->execute();
      $this->templateExecute();
      $this->write();
   }

   function prepare() {
      $class = underscoreToClass($this->app);

      $options = array('template'    => $this->template,
                       'app'         => $this->app,
                       'create_app'  => true,
                       'create_model'=> true,
                       'views'       => array('index'),
                       'slug'        => $this->app,
                       'class'       => $class,
                       'model_class' => $class . "Model");
      $this->options = array_merge($this->options, $options);

      $this->replace = array('SLUG' => $this->app,
                             'CLASS_NAME' => $class);
      if (isset($this->options['table'])) {
         $this->replace['MODEL_CLASS_NAME'] = underscoreToClass($this->options['table'] . "_model");
      }

      $this->files = array();
      array_push(
         $this->files,
         new TemplateFile($this->config['zombie_root'] . "/apps/" . $this->app . "/" . $this->app . ".php",
                          $this->config['zombie_root'] . "/brainz/template/" . $this->template . "/app/app.php")
      );
   }

   function execute() {
      $this->createAppDirs() or die();
      foreach ($this->files as $file) {
         $file->replace($this->replace);
      }
   }

   function write() {
      foreach ($this->files as $file) {
         echo "writing " . $file->write_location . "\n";
         $file->write();
      }
   }

   abstract function templatePrepare(); // user defined
   abstract function templateExecute(); // user defined

   function addModel() {
      $model_file = $this->config['zombie_root'] . "/model/" . $this->options['table'] . ".php";
      if (!empty($this->options['table']) && !file_exists($model_file)) {
         array_push(
            $this->files,
            new TemplateFile($model_file,
                             $this->config['zombie_root'] . "/brainz/template/" . $this->template . "/model/model.php")
         );
      } else {
         echo "Model already exists. Not creating model.\n\n";
      }
   }

   function addView($view_name) {
      array_push(
         $this->files,
         new TemplateFile($this->config['zombie_root'] . "/apps/" . $this->app . "/views/" . $view_name . ".php",
                          $this->config['zombie_root'] . "/brainz/template/" . $this->template . "/app/views/" . $view_name . ".php")
      );
   }

   function addScript($script_name) {
      array_push(
         $this->files,
         new TemplateFile($this->config['zombie_root'] . "/apps/" . $this->app . "/views/scripts/" . $script_name . ".js",
                          $this->config['zombie_root'] . "/brainz/template/" . $this->template . "/app/views/scripts/" . $script_name . ".js")
      );
   }

   function addCSS($css_name) {
      array_push(
         $this->files,
         new TemplateFile($this->config['zombie_root'] . "/apps/" . $this->app . "/views/css/" . $css_name . ".css",
                          $this->config['zombie_root'] . "/brainz/template/" . $this->template . "/app/views/css/" . $css_name . ".css")
      );
   }

   function getField($field_name) {
      $field = new TemplateFile("/dev/null",
          $this->config['zombie_root'] . "/brainz/template/fields/" . $field_name . ".php");
      return $field;
   }

   function createAppDirs($override = false) {
      $app_dir = $this->config['zombie_root'] . "/apps/" . $this->app;
      echo "creating directory $app_dir\n";
      if (!@mkdir($app_dir)) {
         echo "app folder already exists, not creating app\n" .
              "run with overwrite_app=1 to override\n\n";
         return false;
      } else {
         echo "creating directory $app_dir/views\n";
         mkdir($app_dir . "/views");
         echo "creating directory $app_dir/views/css\n";
         mkdir($app_dir . "/views/css");
         echo "creating directory $app_dir/views/scripts\n";
         mkdir($app_dir . "/views/scripts");
         return true;
      }
   }

   function doesModelExist($override = false) {
      return file_exists($config['zombie_root'] . "/model/" . $this->get_opt('app') . ".php");
   }

   function getOpt($name) {
      return $this->options[$name];
   }

   function setOpt($name, $value) {
      $this->options[$name] = $value;
   }
}

class TemplateFile {
   function __construct($write_location, $template_location) {
      $this->write_location = $write_location;
      $this->template_location = $template_location;
      $this->file_contents = file_get_contents($this->template_location);
   }

   function getContents() {
      return $this->file_contents;
   }

   function write() {
      file_put_contents($this->write_location, $this->file_contents);
   }

   function replace($replacement_fields) {
      $this->file_contents = $this->replaceFields($replacement_fields, $this->file_contents);
   }

   function replaceFields($fields, $string) {
      foreach ($fields as $ml => $value) {
         $string = str_replace("<$ml>", $value, $string);
      }
      return $string;
   }
}

?>
