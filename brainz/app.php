<?php

require_once("error.php");

abstract class App {
   protected $session;
   protected $save_status;
   protected $json;

   function __construct($db = null, $sess = null) {
      require("config.php");

      $this->zombie_root = $zombie_root;
      $this->app_root = $app_root;
      $this->web_root = $web_root;
      $this->domain = $domain;
      if ($db == null) {
         // Set up database
         require_once($db_file);
         $this->sql = new $db_class($db_server, $db_user,
                                    $db_pass, $database);
      }
      if ($sess == null) {
         // Set up session
         require_once($sess_file);
         $this->session = new $sess_class();
      }
   }

   function __destruct() {
      $this->session->save();
   }

   /*
    * You must implement this function.
    */
   public abstract function execute($action);

   /*
    * You must implement this function if you want to.
    */
   public function save($action) {
   }

   public function render($filename) {
      foreach (get_object_vars($this) as $var => $val) {
         $$var = $val;
      }
      include($this->app_root . "/" . $filename);
      render_errors();
   }

   public function render_json() {
      echo json_encode($this->json);
   }

   public function in_group($group_name) {
      $groups = $this->session->get('group');
      if (is_array($groups) && in_array($group_name, $groups)) {
         return true;
      } else {
         return false;
      }
   }

   public function run($action = null) {
      if ($action == null && isset($_REQUEST['action'])) {
         $action = $_REQUEST['action'];
      }
      $this->safe_save($action);
      $this->execute($action);
   }

   public function safe_save($action) {
      if (isset($_SERVER['referer'])) {
         $domain = parse_url($_SERVER['referer']);
         $domain = $domain['host'];
         if ($domain != $this->domain) {
            $this->save_status = "bad referer";
            return;
         }
      }
      if (isset($_REQUEST['csrf'])) {
         $tokens = $this->session->get('csrf_tokens');
         $req_token = $_REQUEST['csrf'];
         $match = false;
         foreach ($tokens as $key => $token) {
            if ($req_token == $token) {
               $match = true;
               unset($tokens[$key]);
               $this->session->set('csrf_tokens', $tokens);
               break;
            }
         }
         if (!$match) {
            $this->save_status = "bad csrf";
            return;
         }
      } else {
         $this->save_status = "no csrf";
         return;
      }
      $this->save_status = "success";
      $this->save($action);
   }

   public function get_csrf_token() {
      $tokens = $this->session->get('csrf_tokens');
      if (!is_array($tokens)) {
         $tokens = array();
      }
      $token = md5(rand()); // find something better...
      array_push($tokens, $token);
      if (count($tokens) > 10) {
         $tokens = array_slice($tokens, 1, 10);
      }
      $this->session->set('csrf_tokens', $tokens);
      return $token;
   }

   public function dynamic_url() {
      return "http://" . $this->domain . "/";
   }

   public function static_url() {
      return "http://" . $this->domain . "/";
   }

}

abstract class SecureApp extends App {
   public function run($action = null) {
      if ($action == null && isset($_REQUEST['action'])) {
         $action = $_REQUEST['action'];
      }

      if (!$this->session->is_set('username')) {
         echo "access denied";
         return;
      }
      if (isset($this->groups)) {
         $user_groups = $this->session->get('groups');
         if (!is_array($user_groups)) {
            echo "access denied";
            return;
         }
         foreach ($this->groups as $group) {
            foreach ($user_groups as $user_group) {
               if ($user_group == $group) {
                  $this->safe_save($action);
                  $this->execute($action);
                  return;
               }
            }
         }
         echo "access denied";
      } else {
         $this->safe_save($action);
         $this->execute($action);
      }
   }
}

?>
