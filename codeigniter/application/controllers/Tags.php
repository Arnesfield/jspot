<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tags extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('tags_model');
  }

  public function index() {
    $tags = $this->tags_model->get();
    // only save names
    foreach ($tags as $key => $tag) {
      $tags[$key] = $tag['name'];
    }
    $this->_json(array(
      'success' => TRUE,
      'tags' => $tags
    ));
  }
}

?>
