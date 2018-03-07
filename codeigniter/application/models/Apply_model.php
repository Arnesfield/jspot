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

  public function getWithJobsByUid($uid, $where = FALSE) {
    $this->db
      ->select('
        a.id AS a_id,
        a.user_id AS a_user_id,
        a.subject AS a_subject,
        a.body AS a_body,
        a.files AS a_files,
        a.created_at AS a_created_at,
        a.status AS a_status,
        j.*,
        u.fname AS creator_fname,
        u.lname AS creator_lname,
        u.img_src AS creator_img_src,
        ap.fname AS applier_fname,
        ap.lname AS applier_lname,
        ap.img_src AS applier_img_src
      ')
      ->from('apply a')
      ->join('jobs j', 'j.id = a.job_id')
      ->join('users u', 'u.id = j.created_by')
      ->join('users ap', 'ap.id = a.user_id')
      ->where(array(
        'a.user_id' => $uid,
        'a.status !=' => -1,
        'j.status !=' => -1,
        'u.status' => 1,
      ));

    if ($where) {
      $this->db->where($where);
    }

    $this->db
      ->order_by('a.created_at', 'DESC');
    
    $query = $this->db->get();
    return $this->_res($query);
  }

  public function insert($data) {
    return $this->db->insert('apply', $data);
  }

  public function update($data, $where) {
    return $this->db
      ->set($data)
      ->where($where)
      ->update('apply');
  }
}

?>
