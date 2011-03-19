<?php
require_once("../brainz/app.php");

class Auth extends App {
   public function execute($action) {
      $this->session->clear();
      if (isset($_REQUEST['logout'])) {
         $json = array("status" => "logged_out");
      } else if (!isset($_REQUEST['username']) ||
                 !isset($_REQUEST['password'])) {
         $json = array("status" => "failed");
         return;
      } else {
         $query = 'SELECT id
                        , firstname
                        , lastname
                        , groups
                   FROM users
                   WHERE username = $1
                   AND password = $2';

         $params = array($_REQUEST['username'], $_REQUEST['password']);
         $result = $this->sql->exec($query, $params);
         if ($result->num_rows() == 1) {
            $user = $result->fetch_one();
            $sess_vars['username'] = $_REQUEST['username'];
            $sess_vars['name'] = $user['firstname'] . " " . $user['lastname'];
            $sess_vars['id'] = $user['id'];
            $sess_vars['groups'] = unserialize($user['groups']);
            $this->session->set($sess_vars);
            $json = array("status" => "success");
         } else {
            $json = array("status" => "failed");
         }
      }
      echo json_encode($json);
   }
}

?>
