<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reviews extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('reviews_model');
  }

  public function index() {
    $uid = $this->input->post('id') ? $this->input->post('id') : $this->session->userdata('user')['id'];
    // get all reviews of received
  }

  public function review() {
    $reviewer_id = $this->session->userdata('user')['id'];
    $uid = $this->input->post('id');
    $subject = $this->_filter($this->input->post('subject'));
    $body = $this->_filter($this->input->post('body'));
    $rating = $this->input->post('rating');

    $data = array(
      'user_id' => $uid,
      'reviewer_id' => $reviewer_id,
      'subject' => $subject,
      'body' => $body,
      'rating' => $rating,
      'created_at' => time(),
      'status' => 1
    );
    $res = $this->reviews_model->insert($data);
    $this->_json($res);
  }
}

?>
