<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reviews_model extends MY_Custom_Model {
  public function get($where = FALSE) {
    $this->db
      ->from('reviews')
      ->where('status', 1);
    
    if ($where) {
      $this->db->where($where);
    }

    $query = $this->db->get();
    return $this->_res($query);
  }

  public function insert($review) {
    return $this->db->insert('reviews', $review);
  }

}

?>
