<?php

require_once(__DIR__ . "/../util/error.php");
require_once(__DIR__ . "/../util/util.php");
require_once(__DIR__ . "/../util/autoload.php");
require_once(__DIR__ . "/../util/mobile.php");

abstract class App {
   protected $session;
   protected $save_status;
   protected $json;

   function __construct($sess = null) {
      require(__DIR__ . "/../config.php");

      $this->zombie_root = $zombie_root;
      $this->app_root = $app_root;
      $this->web_root = $web_root;
      $this->domain = $domain;
      $this->json = array();
      $this->is_page = false;
      $this->is_mobile = is_mobile($_SERVER['HTTP_USER_AGENT']);
      if ($sess == null) {
         require_once($sess_file);
         $this->session = $sess_class::get_session();
      }
   }

   function __destruct() {
   }

   public function render() {
      if ($this->format == 'json') {
         $errors = get_error_array();
         if (is_array($errors)) {
            if (isset($this->json['errors'])) {
               $this->json['errors'] = array_merge($this->json['errors'], $errors);
            } else {
               $this->json['errors'] = $errors;
            }
         }
         $this->render_json();
      } else {
         $file = $this->app_root . "/" . class_to_underscore(get_class($this)) . 
                 "/views/" . $this->view . ".php";
         if (file_exists($file)) {
            foreach (get_object_vars($this) as $var => $val) {
               $$var = $val;
            }
            if ($this->is_page) {
               if (!method_exists($menu, 'run')) {
                  require_once($this->app_root . '/menu/menu.php');
                  $menu = new Menu(); 
               }
               if (isset($token)) {
                  $token= $this->get_csrf_token();
               }
               include($this->app_root . "/home/views/open.php");
               include($file);
               include($this->app_root . "/home/views/close.php");
            } else {
               include($file);
            }
         }
         render_errors_js();
      }
   }

   public function render_json() {
      echo json_encode($this->json);
   }

   public function in_group($group_name) {
      $groups = $this->session->get('groups');
      if (is_array($groups) && in_array($group_name, $groups)) {
         return true;
      } else {
         return false;
      }
   }

   public function run($action = null, $request = null) {
      $this->prepare($action, $request);
      $this->execute();
   }

   public function prepare($action, $request) {
      if (is_null($action) && !empty($_REQUEST['action'])) {
         $this->action = $_REQUEST['action'];
      } else if (is_null($action)) {
         $this->action = 'index';
      } else {
         $this->action = $action;
      }
      $this->view = $this->action;

      if (is_null($request)) {
         $this->request = $_REQUEST;
      } else {
         $this->request = $request;
      }
      $this->format = (isset($this->request['format']) ? $this->request['format'] : 'html');
   }

   public function execute() {
      $run_func = $this->action . "_run";
      $this->save_safe($this->action, $this->request);
      if (method_exists($this, $run_func)) {
         $this->$run_func($this->request);
         $this->render();
      } else if (method_exists($this, 'default_run')) {
         $this->view = 'default';
         $this->default_run($this->request);
         $this->render();
      } else {
         include($this->app_root . '/home/views/404.php');
      }
   }

   public function save_safe($action, $request) {
      $save_func = $action . "_save";
      if (!method_exists($this, $save_func)) {
         return;
      }
      if (isset($_REQUEST['csrf'])) {
         $token = $this->session->get('csrf_token');
         $req_token = $_REQUEST['csrf'];
         $match = ($req_token == $token ? true : false);
         if (!$match) {
            $this->save_status = "bad csrf";
            return;
         } else {
            if (isset($_SERVER['referer'])) {
               $domain = parse_url($_SERVER['referer']);
               $domain = $domain['host'];
               if ($domain != $this->domain) {
                  $this->save_status = "bad referer";
                  return;
               }
            }
         }
      } else {
         $this->save_status = "no csrf";
         return;
      }
      $this->save_status = "success";
         $this->$save_func($request);
   }

   public function get_csrf_token() {
      $token = $this->session->get('csrf_token');
      if (!$token) {
         $token = md5(rand() . time());
         $this->session->set('csrf_token', $token);
      }
      return $token;
   }

   public function get_model($model) {
      require(dirname(__FILE__) . "/../../model/$model.php");
      $class = underscore_to_class($model) . "Model";
      return new $class();
   }

   public function dynamic_url() {
      return "http://" . $this->domain . "/";
   }

   public function static_url() {
      return "http://" . $this->domain . "/";
   }

}

?>
