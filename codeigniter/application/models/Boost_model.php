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

  public function getUsers($where, $fulltext = FALSE) {
    $this->db
      ->select('
        u.*,
        b.created_at AS b_created_at,
        MAX(b.ends_at) AS b_ends_at
      ')
      ->from('boosts b')
      ->join('users u', 'u.id = b.ref_id')
      ->where(array(
        'b.status' => 1,
        'b.tbl_name' => 'users'
      ));
    
    if ($where) {
      $this->db->where($where);
    }

    if ($fulltext) {
      $this->db->where($fulltext, NULL, FALSE);
    }

    $this->db
      ->group_by('b.ref_id')
      ->order_by('created_at', 'DESC')
      ->order_by('ends_at', 'DESC');
    
    $query = $this->db->get();
    return $this->_res($query);
  }

  public function getJobs($where, $fulltext = FALSE) {
    // also include creator
    $this->db
      ->select('
        j.*,
        b.created_at AS b_created_at,
        u.fname AS creator_fname,
        u.lname AS creator_lname,
        u.img_src AS creator_img_src,
        MAX(b.ends_at) AS b_ends_at
      ')
      ->from('boosts b')
      ->join('jobs j', 'j.id = b.ref_id')
      ->join('users u', 'u.id = j.created_by')
      ->where(array(
        'b.status' => 1,
        'b.tbl_name' => 'jobs',
        'j.status !=' => -1
      ));
    
    if ($where) {
      $this->db->where($where);
    }

    if ($fulltext) {
      $this->db->where($fulltext, NULL, FALSE);
    }

    $this->db
      ->group_by('b.ref_id')
      ->order_by('created_at', 'DESC')
      ->order_by('ends_at', 'DESC');
    
    $query = $this->db->get();
    return $this->_res($query);
  }
}

?>
