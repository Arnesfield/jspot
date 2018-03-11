<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Boost extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('boost_model');
  }

  public function index() {
    
  }

  public function suggest() {
    // get all boosts not yet ended
    $users = $this->boost_model->getUsers(array(
      'b.ends_at >' => time()
    ));
    $jobs = $this->boost_model->getUsers(array(
      'b.ends_at >' => time()
    ));

    $this->_json(TRUE, array(
      'jobs' => $jobs,
      'users' => $users
    ));
  }
}
?>