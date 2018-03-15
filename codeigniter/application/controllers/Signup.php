<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends MY_View_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('users_model');
  }

  public function verify() {
    $code = $this->uri->segment('3');
    $res = $this->users_model->updateWhere(array(
      'status' => 1,
      'verification_code' => ''
    ), array(
      'verification_code' => $code
    ));
    $this->_redirect('../#/login/1');
  }
}

?>
