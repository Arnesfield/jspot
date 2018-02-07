<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends MY_Custom_Controller {
  public function index() {
    session_destroy();
    $user = $this->session->userdata('user');
    $this->_json('success', $user && TRUE);
  }
}

?>
