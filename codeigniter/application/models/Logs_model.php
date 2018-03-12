<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs_model extends MY_Custom_Model {
  public function getViews($where = FALSE, $where_in = FALSE, $where_in_col = 'job_id') {
    $this->db->from('views');
    
    if ($where) {
      $this->db->where($where);
    }
    
    if ($where_in) {
      $this->db->where_in($where_in_col, $where_in);
    }
    
    $query = $this->db->get();
    return $this->_res($query);
  }

  public function insertView($id, $vid, $type = 'users') {
    $data = array(
      'viewer_id' => $vid,
      'viewed_at' => time()
    );

    if ($type == 'users') {
      $data['user_id'] = $id;
      $data['job_id'] = 0;
    } else if ($type == 'jobs') {
      $data['user_id'] = 0;
      $data['job_id'] = $id;
    }
    return $this->db->insert('views', $data);
  }

  public function getJobIdsOfUser($uid) {
    $this->db
      ->select('j.*')
      ->from('jobs j')
      ->join('users u', 'u.id = j.created_by')
      ->where(array(
        'u.id' => $uid,
        'u.type' => 3
      ));
    $query = $this->db->get();
    return $this->_res($query);
  }
}

?>
