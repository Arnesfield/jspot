<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reviews extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('reviews_model');
  }

  public function index() {
    // if has post id, get reviews received by that id
    // if none, get reviews given by sess id
    $where = array();
    $tbl = '';
    if ($id = $this->input->post('id')) {
      $tbl = 'u';
      $where['r.user_id'] = $id;
    } else {
      $tbl = 're';
      $where['r.reviewer_id'] = $this->session->userdata('user')['id'];
    }

    $reviews = $this->reviews_model->get($where);
    
    $this->_json(TRUE, 'reviews', $reviews);
  }

  public function review() {
    $reviewer_id = $this->session->userdata('user')['id'];
    $uid = $this->input->post('id');
    $body = $this->_filter($this->input->post('body'));
    $rating = $this->input->post('rating');

    $data = array(
      'user_id' => $uid,
      'reviewer_id' => $reviewer_id,
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
