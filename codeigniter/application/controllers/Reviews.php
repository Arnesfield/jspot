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
    if ($id = $this->input->post('id')) {
      $where['r.user_id'] = $id;
    } else {
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

    // fetch reviewer info
    $this->load->model('users_model');
    $reviewer = $this->users_model->get(array('id' => $reviewer_id))[0];
    $user = $this->users_model->get(array('id' => $uid))[0];
    $email = $user['email'];

    $data = array(
      'user_id' => $uid,
      'reviewer_id' => $reviewer_id,
      'body' => $body,
      'rating' => $rating,
      'created_at' => time(),
      'status' => 1
    );

    $send_data = array(
      'id' => $uid,
      'name' => $reviewer['fname'].' '.$reviewer['lname'],
      'body' => $body,
      'rating' => $rating,
      'email' => $email,
      'fname' => $user['fname'],
      'lname' => $user['lname']
    );

    $sent = $this->_send_mail($email, 'Someone has posted a review', 'email/review', $send_data);

    if ($sent !== TRUE) {
      $this->_json(FALSE, array(
        'error' => 'Unable to send email.',
        'debug' => $sent
      ));
    }

    $res = $this->reviews_model->insert($data);
    $this->_json($res);
  }

  public function statistics() {
    $id = $this->input->post('id');
    $reviews = $this->reviews_model->getRatings($id);
    $rates = $this->reviews_model->_to_col($reviews, 'rating');

    $ratings = array();
    for ($i = 1; $i <= 5; $i++) {
      $ratings[$i] = 0;
    }

    foreach ($rates as $rating) {
      $ratings[$rating] += 1;
    }

    $this->_json(TRUE, array(
      'ratings' => $ratings
    ));
  }

  public function delete() {
    $id = $this->input->post('id');
    $res = $this->reviews_model->update(array(
      'status' => 0,
    ), array('id' => $id));
    $this->_json($res);
  }
}

?>
