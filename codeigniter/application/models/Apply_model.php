<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apply_model extends MY_Custom_Model {
  public function getByUid($uid, $where = FALSE) {
    $this->db
      ->select('a.*')
      ->from('apply a')
      ->join('jobs j', 'j.id = a.job_id')
      ->where(array(
        'a.user_id' => $uid,
        'a.status !=' => -1,
        'j.status !=' => -1,
        'j.status !=' => 2
      ));

    if ($where) {
      $this->db->where($where);
    }

    $query = $this->db->get();
    return $this->_res($query);
  }
  public function insert($data) {
    return $this->db->insert('apply', $data);
  }
}

?>
