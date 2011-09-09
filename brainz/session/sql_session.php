<?php

require_once(dirname(__FILE__) . "/../config.php");

class SqlSession extends Session {
   private static $instance;
   public $sql;
   public $state;

   public static function get_session() {
      if (!isset(SqlSession::$instance)) {
         SqlSession::$instance = new SqlSession();
      }
      return SqlSession::$instance;
   }

   public function __construct() {
      $config = get_zombie_config();
      $this->timeout = $config['session']['timeout'];
      require_once($config['database']['file']);
      $db_class = underscore_to_class($config['database']['type'] .'_' .
                                      'database');
      $this->sql = new $db_class($config['database']['host'],
                                 $config['database']['user'],
                                 $config['database']['pass'],
                                 $config['database']['database']);
      $this->clear_old();
      $this->session = false;
      if (isset($_COOKIE['s'])) {
         $this->session_id = $_COOKIE['s'];
         $query = "SELECT data FROM session WHERE id = $1";
         $params = array($this->session_id);
         $this->session = $this->sql->exec($query, $params)->fetch_item('data');
      }
      if (!$this->session) {
         $this->create();
      } else {
         $this->session = unserialize($this->session);
         if (!is_array($this->session)) {
            $this->session = array();
            echo 'bad session';
         }
      }
   }

   public function __destruct() {
      $this->save();
   }

   public function get_array() {
      return $this->session;
   }

   public function clear_old() {
      // only works for mysql?
      $query = "DELETE FROM session WHERE last_access < DATE_SUB(NOW(), INTERVAL " $this->timeout . " SECOND)";
      $this->sql->exec($query);
   }

   public function save() {
      $session = serialize($this->session);
      if ($this->state == 'new') {
         $query = "INSERT INTO session
                   (id, data, last_access)
                   VALUES
                   ($1, $2, NOW())";
         $params = array($this->session_id, $session);
      } else {
         $query = "UPDATE session
                   SET data = $1
                     , last_access = NOW()
                   WHERE id = $2";
         $params = array($session, $this->session_id);
      }
      $this->sql->exec($query, $params, false, $debug);
      $this->state = 'saved';
   }

   public function create() {
      $this->session_id = md5(time() . rand() . rand());
      setcookie('s',
                $this->session_id,
                time() + $this->timeout,
                '/',
                $_SERVER['SERVER_NAME'],
                false,
                true);
      $this->state = 'new';
      $this->session = array();
   }

   public function set($a, $b = null) {
      if (is_array($a)) {
         $this->session = array_merge($this->session, $a);
      } else {
         $this->session[$a] = $b;
      }
   }

   public function get($key) {
      if (isset($this->session[$key])) {
         return $this->session[$key];
      } else {
         return false;
      }
   }

   public function is_set($key) {
      return isset($this->session[$key]);
   }

   public function clear() {
      setcookie('s','',time() - 1);
      $query = 'DELETE FROM session WHERE id = $1';
      $params = array($this->session_id);
      $this->session = array();
      $this->create();
   }
}

?>
