<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs_model extends MY_Custom_Model {
  public function get($id = NULL, $where = NULL) {
    $this->db
      ->select('
        j.*,
        u.fname AS creator_fname,
        u.lname AS creator_lname
      ')
      ->from('jobs j')
      ->join('users u', 'u.id = j.created_by')
      ->where('j.status !=', -1);
    
    if ($id) {
      $this->db->where('created_by', $id);
    }

    if ($where) {
      $this->db->where($where);
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
