<?php

require_once(__DIR__ . "/../util/error.php");
require_once(__DIR__ . "/../util/util.php");
require_once(__DIR__ . "/../util/mobile.php");
require_once(__DIR__ . "/../config.php");

abstract class Controller {
   protected $session;
   protected $save_status;
   protected $json;
   static protected $mobile_set = false;

   public function __construct() {
      $this->config = get_zombie_config();
      $this->json = array();
      $this->is_page = false;
      $this->view_base = class_to_underscore(get_class($this));
      $sess_class = underscore_to_class($this->config['session']['type'] . '_' . 'session');
      $this->session = $sess_class::get_session();
      $this->mobile_init();
   }

   public function __destruct() {
   }

   public function mobile_init() {
      if (!Controller::$mobile_set && isset($_GET['mobile'])) {
         $this->is_mobile = (boolean)$_GET['mobile'];
         $mobile_device = is_mobile($_SERVER['HTTP_USER_AGENT']);
         if (!$this->is_mobile && $mobile_device) {
            $cookie = 'o';
         } else if ($this->is_mobile) {
            $cookie = 'y';
         } else {
            $cookie = 'n';
         }
         setcookie('m', $cookie, time() + 86400);
         $_COOKIE['m'] = $cookie;
         Controller::$mobile_set = true;
      } else if (!isset($_COOKIE['m'])) {
         $this->is_mobile = is_mobile($_SERVER['HTTP_USER_AGENT']);
         $cookie = ($this->is_mobile ? 'y' : 'n');
         setcookie('m', $cookie, time() + 31536000);
         $_COOKIE['m'] = $cookie;
      } else {
         $this->is_mobile = ($_COOKIE['m'] == 'y' ? true : false);
      }
   }

   public function init() {
   }

   public function run($action = null, $request = null) {
      $this->prepare($action, $request);
      $this->init();
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
      try {
         $run_func = $this->action . "_run";
         $this->save_safe($this->action, $this->request);
         if (method_exists($this, $run_func)) {
            $this->$run_func($this->request);
         } else if (method_exists($this, 'default_run')) {
            $this->view = 'default';
            $this->default_run($this->request);
         } else {
            include($this->config['config']['zombie_root'] .
                    '/apps/home/views/404.php');
            return;
         }
      } catch (Exception $e) {
         if ($this->config['config']['env'] == 'dev') {
            $this->error((string)$e);
         } else {
            $this->error($e->getMessage());
         }
      }
      $this->render();
   }

   public function render() {
      if ($this->format == 'json') {
         $errors = get_error_array();
         if (!empty($errors)) {
            $this->json['php_errors'] = $errors;
         }
         if (!empty($this->errors)) {
            $this->json['errors'] = $this->errors;
         }
         if (!empty($this->warnings)) {
            $this->json['warnings'] = $this->warnings;
         }
         if (!empty($this->messages)) {
            $this->json['messages'] = $this->messages;
         }
         $this->render_json();
      } else {
         $file = $this->config['config']['zombie_root'] . "/apps/" . $this->view_base . 
                 "/views/" . $this->view . ".php";
         if (file_exists($file)) {
            foreach (get_object_vars($this) as $var => $val) {
               $$var = $val;
            }
            if ($this->is_page) {
               if (!method_exists($menu, 'run')) {
                  require_once($this->config['config']['zombie_root'] . '/apps/menu/menu.php');
                  $menu = new Menu(); 
               }
               if (isset($token)) {
                  $token= $this->get_csrf_token();
               }
               include($this->config['config']['zombie_root'] . "/apps/home/views/open.php");
               include($file);
               include($this->config['config']['zombie_root'] . "/apps/home/views/close.php");
            } else {
               include($file);
            }
         }
         render_errors_js();
         $this->render_js_mesg();
      }
   }

   public function render_js_mesg() {
      if (!empty($this->errors) ||
          !empty($this->warnings) ||
          !empty($this->messages)) {
         echo '<script type="text/javascript">';
         if (!empty($this->errors)) {
            foreach ($this->errors as $error) {
               echo "undead.ui.error(\"" . 
                    htmlentities($error, ENT_QUOTES) . "\");\n";
            }
         }
         if (!empty($this->warnings)) {
            foreach ($this->warnings as $warning) {
               echo "undead.ui.warn(\"" . 
                    htmlentities($warning, ENT_QUOTES) . "\");\n";
            }
         }
         if (!empty($this->messages)) {
            foreach ($this->messages as $message) {
               echo "undead.ui.message(\"" . 
                    htmlentities($message, ENT_QUOTES) . "\");\n";
            }
         }
         echo '</script>';
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
               if ($domain != $this->config['config']['domain']) {
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

   public function error($mesg) {
      if (empty($this->errors)) {
         $this->errors = array();
      }
      array_push($this->errors, $mesg);
   }

   public function warn($mesg) {
      if (empty($this->warnings)) {
         $this->warnings = array();
      }
      array_push($this->warnings, $mesg);
   }

   public function message($mesg) {
      if (empty($this->messages)) {
         $this->messages = array();
      }
      array_push($this->messages, $mesg);
   }

}

?>
