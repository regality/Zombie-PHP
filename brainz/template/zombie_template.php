<?php

abstract class ZombieTemplate {
   public $options;
   public $replace;
   public $files;

   function __construct($template, $app, $options = array()) {
      require(__DIR__ . "/../config.php");
      $this->zombie_root = $zombie_root;
      $this->template = $template;
      $this->app = strtolower($app);
      $this->options = $options;
   }

   public function run() {
      $this->prepare();
      $this->template_prepare();
      $this->execute();
      $this->template_execute();
      $this->write();
   }

   function prepare() {
      $class = underscore_to_class($this->app);

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
                             'CLASS_NAME' => $class,
                             'MODEL_CLASS_NAME' => $class . "Model");

      $this->files = array();
      array_push(
         $this->files,
         new TemplateFile($this->zombie_root . "/apps/" . $this->app . "/" . $this->app . ".php",
                          $this->zombie_root . "/brainz/template/" . $this->template . "/app/app.php")
      );
   }

   function execute() {
      $this->create_app_dirs() or die();
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

   abstract function template_prepare(); // user defined
   abstract function template_execute(); // user defined

   function add_model() {
      array_push(
         $this->files,
         new TemplateFile($this->zombie_root . "/model/" . $this->app . ".php",
                          $this->zombie_root . "/brainz/template/" . $this->template . "/model/model.php")
      );
   }

   function add_view($view_name) {
      array_push(
         $this->files,
         new TemplateFile($this->zombie_root . "/apps/" . $this->app . "/views/" . $view_name . ".php",
                          $this->zombie_root . "/brainz/template/" . $this->template . "/app/views/" . $view_name . ".php")
      );
   }

   function get_field($field_name) {
      $field = new TemplateFile("/dev/null",
          $this->zombie_root . "/brainz/template/fields/" . $field_name . ".php");
      return $field;
   }

   function create_app_dirs($override = false) {
      $app_dir = $this->zombie_root . "/apps/" . $this->app;
      echo "creating directory $app_dir\n";
      if (!@mkdir($app_dir)) {
         echo "app folder already exists, not creating app\n" .
              "run with overwrite_app=1 to override\n\n";
         return false;
      } else {
         echo "creating directory $app_dir/views\n";
         mkdir($app_dir . "/views");
         return true;
      }
   }

   function does_model_exist($override = false) {
      return file_exists($zombie_root . "/model/" . $this->get_opt('app') . ".php");
   }

   function get_opt($name) {
      return $this->options[$name];
   }

   function set_opt($name, $value) {
      $this->options[$name] = $value;
   }
}

class TemplateFile {
   function __construct($write_location, $template_location) {
      $this->write_location = $write_location;
      $this->template_location = $template_location;
      $this->file_contents = file_get_contents($this->template_location);
   }

   function get_contents() {
      return $this->file_contents;
   }

   function write() {
      file_put_contents($this->write_location, $this->file_contents);
   }

   function replace($replacement_fields) {
      $this->file_contents = $this->replace_fields($replacement_fields, $this->file_contents);
   }

   function replace_fields($fields, $string) {
      foreach ($fields as $ml => $value) {
         $string = str_replace("<$ml>", $value, $string);
      }
      return $string;
   }
}

?>
