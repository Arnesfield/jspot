<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('login_model');
  }

  public function index() {
    $email = $this->input->post('email');
    $password = $this->input->post('password');

    // get user with email
    $user = array('email' => $email);
    $users = $this->login_model->get($user);

    // user types
    // 2 = admin
    // 3 = normal

    if (!$users) {
      $this->_json(FALSE, array(
        'error' => 'Invalid email or password.'
      ));
    }

    // if exists
    $user = $users[0];
    // check for password
    $verified = password_verify($password, $user['password']);

    if (!$verified) {
      $this->_json(FALSE, array(
        'error' => 'Invalid email or password.'
      ));
    }

    // check status
    if ($user['status'] == 0) {
      $this->_json(FALSE, array(
        'error' => 'This account has been suspended.'
      ));
    }

    // check status
    if ($user['status'] == 2) {
      $this->_json(FALSE, array(
        'error' => 'Verify your account first.'
      ));
    }

    // set session
    $this->session->set_userdata(array(
      'user' => $user,
      'auth' => $user['type']
    ));

    $this->_json(TRUE, array(
      'user' => $user
    ));
  }
}

?>
