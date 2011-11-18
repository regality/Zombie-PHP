<?php

class Auth extends Controller {
   public function indexRun($request) {
      if (!isset($request['username']) ||
          !isset($request['password'])) {
         $this->data['status'] = "failed";
      } else {
         $users_model = new UsersModel();
         $user = $users_model->isValidUser($request['username'],
                                           $request['password']);
         if ($user !== false) {
            $sess_vars['username'] = $request['username'];
            $sess_vars['name'] = $user['firstname'] . " " . $user['lastname'];
            $sess_vars['id'] = $user['id'];
            $sess_vars['groups'] = $user['groups'];
            $this->session->set($sess_vars);
            $this->session->regenerateId();
            $this->data['status'] = "success";
            $this->data['app'] = "welcome";
         } else {
            $this->data['status'] = "failed";
         }
      }
   }

   public function logoutRun($request) {
      $this->session->destroy();
      $this->data['status'] = "success";
   }
}

?>
