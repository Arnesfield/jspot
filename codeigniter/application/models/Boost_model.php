<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Boost_model extends MY_Custom_Model {
  public function getLatestBoost($id, $tbl_name = 'users') {
    $this->db
      ->from('boosts')
      ->where(array(
        'ref_id' => $id,
        'tbl_name' => $tbl_name,
        'status' => 1
      ))
      ->order_by('ends_at', 'DESC')
      ->order_by('created_at', 'DESC')
      ->limit(1);

    $query = $this->db->get();
    return $this->_res($query);
  }

  public function insert($data) {
    return $this->db->insert('boosts', $data);
  }

  public function getUsers($where) {
    $this->db
      ->select('
        u.*,
        b.created_at AS b_created_at,
        b.ends_at AS b_ends_at
      ')
      ->from('boosts b')
      ->join('users u', 'u.id = b.ref_id')
      ->where(array(
        'b.status' => 1,
        'tbl_name' => 'users'
      ));
    
    if ($where) {
      $this->db->where($where);
    }

    $this->db
      ->order_by('created_at', 'DESC')
      ->order_by('ends_at', 'DESC');
    
    $query = $this->db->get();
    return $this->_res($query);
  }

  public function getJobs($where) {
    $this->db
      ->select('
        j.*,
        b.created_at AS b_created_at,
        b.ends_at AS b_ends_at
      ')
      ->from('boosts b')
      ->join('jobs j', 'j.id = b.ref_id')
      ->where(array(
        'b.status' => 1,
        'tbl_name' => 'jobs'
      ));
    
    if ($where) {
      $this->db->where($where);
    }

    $this->db
      ->order_by('created_at', 'DESC')
      ->order_by('ends_at', 'DESC');
    
    $query = $this->db->get();
    return $this->_res($query);
  }
}

?>
