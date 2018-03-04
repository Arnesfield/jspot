<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apply_model extends MY_Custom_Model {
  public function insert($data) {
    return $this->db->insert('apply', $data);
  }
}

?>
