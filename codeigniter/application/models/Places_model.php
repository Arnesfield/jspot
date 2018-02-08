<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Places_model extends MY_Custom_Model {
  public function get($place = FALSE) {
    $this->db
      ->from('places')
      ->where('status', 1);
    
    if ($place) {
      $this->db->where($place);
    }

    $query = $this->db->get();
    return $this->_res($query);
  }
}

?>
