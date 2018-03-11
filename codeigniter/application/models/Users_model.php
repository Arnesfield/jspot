<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends MY_Custom_Model {
  public function get($user = FALSE) {
    $this->db
      ->from('users')
      ->where('status !=', -1);
    
    if ($user) {
      $this->db->where($user);
    }

    $query = $this->db->get();
    return $this->_res($query);
  }

  public function search($search = FALSE) {
    // search in fname, lname, 
    $this->db
      ->from('users')
      ->where('type !=', 2)
      ->where('status', 1);

    if ($search) {
      $search = strtolower($search);
      $this->db->where("(
        MATCH(fname) AGAINST('$search') OR
        MATCH(lname) AGAINST('$search') OR
        MATCH(places) AGAINST('$search') OR
        MATCH(job_tags) AGAINST('$search') OR
        LOWER(fname) LIKE '%$search%' OR
        LOWER(lname) LIKE '%$search%' OR
        LOWER(places) LIKE '%$search%' OR
        LOWER(job_tags) LIKE '%$search%'
      )", NULL, FALSE);
    }

    $query = $this->db->get();
    return $this->_res($query);
  }

  public function suggest($cond = FALSE, $type = FALSE) {
    $this->db
      ->from('users')
      ->where('type !=', 2)
      ->where('status', 1);

    if ($cond) {
      $this->db->where($cond, NULL, FALSE);
    }

    if ($type) {
      $this->db->where('type', $type);
    }

    $query = $this->db->get();
    return $this->_res($query);
  }

  public function insert($user) {
    return $this->db->insert('users', $user);
  }

  public function update($id, $user) {
    return $this->db->update('users', $user, array('id' => $id));
  }
}

?>
