<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('home_model');
  }

  public function getReviews() {
    $reviews = $this->home_model->getReviews();
    $this->_json(TRUE, 'reviews', $reviews);
  }

  public function getUsersCount() {
    $count = $this->home_model->getCountOfUsers()[0];
    $this->_json(TRUE, array(
      'employers' => $count['employers'],
      'employees' => $count['employees']
    ));
  }
}

?>
