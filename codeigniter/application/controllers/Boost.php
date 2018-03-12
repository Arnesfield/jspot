<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Boost extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('boost_model');
  }

  public function index() {
    $id = $this->input->post('id') ? $this->input->post('id') : $this->session->userdata('user')['id'];
    $name = $this->input->post('name') ? $this->input->post('name') : 'users';
    $res = $this->boost_model->insert(array(
      'ref_id' => $id,
      'tbl_name' => $name,
      'created_at' => time(),
      'ends_at' => strtotime('+1 day'),
      'status' => 1
    ));
    $this->_json($res);
  }

  public function profileCheck() {
    $uid = $this->input->post('id') ? $this->input->post('id') : $this->session->userdata('user')['id'];

    // check for latest boost
    $allow = TRUE;
    $boosts = $this->boost_model->getLatestBoost($uid);
    $json = array('allow' => $allow);
    if ($boosts) {
      $boost = $boosts[0];
      // if not yet ended
      $allow = $boost['ends_at'] <= time();
      
      $json = array(
        'allow' => $allow,
        'endsAt' => date('F j, Y H:i', $boost['ends_at'])
      );
    }

    $this->_json(TRUE, $json);
  }

  public function suggest() {
    // get all boosts not yet ended
    $users = $this->boost_model->getUsers(array(
      'b.ends_at >' => time()
    ));
    $jobs = $this->boost_model->getJobs(array(
      'b.ends_at >' => time()
    ));

    $this->_json(TRUE, array(
      'jobs' => $jobs,
      'users' => $users
    ));
  }
}
?>