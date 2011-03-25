<?php
require_once(__DIR__ . "/../../brainz/app.php");

class Auth extends App {
   public function execute($action, $request) {
      $this->session->clear();
      if (isset($request['logout'])) {
         $this->json['status'] = "logged out";
      } else if (!isset($request['username']) ||
                 !isset($request['password'])) {
         $this->json['status'] = "failed";
      } else {
         $query = "SELECT id
                        , firstname
                        , lastname
                        , groups
                   FROM users
                   WHERE username = $1
                   AND password = $2";
         $params = array($request['username'], $request['password']);
         $result = $this->sql->exec($query, $params);
         if ($result->num_rows() == 1) {
            $user = $result->fetch_one();
            $sess_vars['username'] = $request['username'];
            $sess_vars['name'] = $user['firstname'] . " " . $user['lastname'];
            $sess_vars['id'] = $user['id'];
            $sess_vars['groups'] = unserialize($user['groups']);
            $this->session->set($sess_vars);
            $this->json['status'] = "success";
         } else {
            $this->json['status'] = "failed";
         }
      }
      $this->render_json();
   }
}

?>
