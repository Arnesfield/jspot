<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Places extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('places_model');
  }

  public function index() {
    $places = $this->places_model->get();
    // only save names
    foreach ($places as $key => $place) {
      $places[$key] = $place['name'];
    }
    $this->_json(TRUE, array(
      'places' => $places
    ));
  }
}

?>
