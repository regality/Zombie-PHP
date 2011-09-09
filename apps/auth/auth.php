<?php

class Auth extends BasicController {
   public function index_run($request) {
      if (!isset($request['username']) ||
          !isset($request['password'])) {
         $this->json['status'] = "failed";
      } else {
         $users_model = new UsersModel();
         $user = $users_model->is_valid_user($request['username'],
                                             $request['password']);
         if ($user !== false) {
            $sess_vars['username'] = $request['username'];
            $sess_vars['name'] = $user['firstname'] . " " . $user['lastname'];
            $sess_vars['id'] = $user['id'];
            $sess_vars['groups'] = $user['groups'];
            $this->session->set($sess_vars);
            $this->json['status'] = "success";
            $this->json['app'] = "welcome";
         } else {
            $this->json['status'] = "failed";
         }
      }
   }

   public function logout_run($request) {
      $this->json['status'] = "success";
      $this->session->clear();
   }
}

?>
