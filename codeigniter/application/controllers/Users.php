<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('users_model');
  }

  public function index() {
    $this->_json(array(
      'success' => TRUE,
      'users' => $this->users_model->get()
    ));
  }
}

?>
