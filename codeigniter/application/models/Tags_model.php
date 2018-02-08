<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tags_model extends MY_Custom_Model {
  public function get($tag = FALSE) {
    $this->db
      ->from('tags')
      ->where('status', 1);
    
    if ($tag) {
      $this->db->where($tag);
    }

    $query = $this->db->get();
    return $this->_res($query);
  }
}

?>
