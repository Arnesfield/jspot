<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs_model extends MY_Custom_Model {
  public function getUserViews($where = FALSE) {
    $this->db->from('views');
    
    if ($where) {
      $this->db->where($where);
    }
    
    $query = $this->db->get();
    return $this->_res($query);
  }

  public function insertView($uid, $vid) {
    $data = array(
      'user_id' => $uid,
      'viewer_id' => $vid,
      'viewed_at' => time()
    );
    return $this->db->insert('views', $data);
  }
}

?>
