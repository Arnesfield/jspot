<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs_model extends MY_Custom_Model {
  public function get($id = NULL) {
    $this->db
      ->from('jobs')
      ->where('status', 1);
    
    if ($id) {
      $this->db->where('created_by', $id);
    }

    $this->db
      ->order_by('updated_at', 'DESC')
      ->order_by('created_at', 'DESC');

    $query = $this->db->get();
    return $this->_res($query);
  }

  public function insert($data) {
    return $this->db->insert('jobs', $data);
  }
}

?>
