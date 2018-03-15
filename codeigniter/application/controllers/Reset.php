<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reset extends MY_View_Controller {
  public function password() {
    $code = $this->uri->segment('3');
    echo $code;
  }
}

?>
