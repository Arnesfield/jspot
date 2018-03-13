<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs_model extends MY_Custom_Model {
  public function simpleGet($id) {
    $this->db
      ->from('jobs')
      ->where('id', $id);
    $query = $this->db->get();
    return $this->_res($query);
  }
  public function get($id = NULL, $where = NULL) {
    $this->db
      ->select('
        j.*,
        u.fname AS creator_fname,
        u.lname AS creator_lname,
        u.img_src AS creator_img_src
      ')
      ->from('jobs j')
      ->join('users u', 'u.id = j.created_by')
      ->where('j.status !=', -1);
    
    if ($id) {
      $this->db->where('j.created_by', $id);
    }

    if ($where) {
      $this->db->where($where);
    }

    $this->db
      ->order_by('j.updated_at', 'DESC')
      ->order_by('j.created_at', 'DESC');

    $query = $this->db->get();
    return $this->_res($query);
  }

  public function insert($data) {
    return $this->db->insert('jobs', $data);
  }

  public function update($data, $where) {
    return $this->db
      ->set($data)
      ->where($where)
      ->update('jobs');
  }
}

?>
