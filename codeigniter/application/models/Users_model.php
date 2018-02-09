<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends MY_Custom_Model {
  public function get($user = FALSE) {
    $this->db->from('users');
    
    if ($user) {
      $this->db->where($user);
    }

    $query = $this->db->get();
    return $this->_res($query);
  }

  public function insert($user) {
    return $this->db->insert('users', $user);
  }
}

?>
