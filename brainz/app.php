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
   }

   /*
    * You must implement this function.
    */
   public abstract function execute($action, $request);

   /*
    * You must implement this function if you want to.
    */
   public function save($action, $request) {
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
      $groups = $this->session->get('groups');
      if (is_array($groups) && in_array($group_name, $groups)) {
         return true;
      } else {
         return false;
      }
   }

   public function run($action = null, $request = null) {
      if ($action == null && isset($_REQUEST['action'])) {
         $action = $_REQUEST['action'];
      }
      if (is_null($request)) {
         $request = $_REQUEST;
      }
      $this->safe_save($action, $request);
      $this->execute($action, $request);
   }

   public function safe_save($action, $request) {
      if (isset($_REQUEST['csrf'])) {
         $tokens = $this->session->get('csrf_tokens');
         if (!is_array($tokens)) {
            $tokens = array();
         }
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
      $this->save($action, $request);
   }

   public function get_csrf_token($num = 1) {
      $tokens = $this->session->get('csrf_tokens');
      if (!is_array($tokens)) {
         $tokens = array();
      }
      if ($num == 1) {
         $token = md5(rand() . time());
         $tokens[] = $token;
         array_push($tokens, $token);
      } else if ($num > 1) {
         if ($num > 10) {
            $num = 10;
         }
         $token_list = array();
         for ($i = 0; $i < $num; ++$i) {
            $token = md5(rand() . time());
            $token_list[] = $token;
            array_push($tokens, $token);
         }
      }
      if (count($tokens) > 10) {
         $tokens = array_slice($tokens, 1, 10);
      }
      $this->session->set('csrf_tokens', $tokens);
      return ($num > 1 ? $token_list : $token);
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
      $format = (isset($_REQUEST['format']) ? $_REQUEST['format'] : 'html');
      if ($action == null && isset($_REQUEST['action'])) {
         $action = $_REQUEST['action'];
      }

      if (!$this->session->is_set('username')) {
         if ($format == 'json') {
            $this->json['status'] = 'logged out';
            $this->render_json();
         } else {
            echo "logged out";
         }
         return;
      }
      if (isset($this->groups)) {
         $user_groups = $this->session->get('groups');
         if (!is_array($user_groups)) {
            if ($format == 'json') {
               $this->json['status'] = 'access denied';
               $this->render_json();
            } else {
               echo 'access denied';
            }
            return;
         }
         foreach ($this->groups as $group) {
            foreach ($user_groups as $user_group) {
               if ($user_group == $group) {
                  $this->safe_save($action, $_REQUEST);
                  $this->execute($action, $_REQUEST);
                  return;
               }
            }
         }
         if ($format == 'json') {
            $this->json['status'] = 'access denied';
            $this->render_json();
         } else {
            echo 'access denied';
         }
      } else {
         $this->safe_save($action, $_REQUEST);
         $this->execute($action, $_REQUEST);
      }
   }
}

?>
