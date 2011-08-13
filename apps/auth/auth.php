<?php
require_once(__DIR__ . "/../../brainz/app/app.php");

class Auth extends App {
   public function index_run($request) {
      if (isset($request['logout'])) {
         $this->json['status'] = "logged out";
         $this->session->clear();
      } else if (!isset($request['username']) ||
                 !isset($request['password'])) {
         $this->json['status'] = "failed";
      } else {
         $users_model = $this->get_model("users");
         $result = $users_model->is_valid_user($request['username'],
                                               $request['password']);
         if ($result->num_rows() == 1) {
            $user = $result->fetch_one();
            $sess_vars['username'] = $request['username'];
            $sess_vars['name'] = $user['firstname'] . " " . $user['lastname'];
            $sess_vars['id'] = $user['id'];
            $sess_vars['groups'] = unserialize($user['groups']);
            $this->session->set($sess_vars);
            $this->json['status'] = "success";
            $this->json['app'] = "welcome";
         } else {
            $this->json['status'] = "failed";
         }
      }
   }
}

?>
