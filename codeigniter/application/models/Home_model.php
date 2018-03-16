<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends MY_Custom_Model {
  public function getCountOfUsers() {
    $query = $this->db
      ->select('
        SUM(type = 3) AS employers,
        SUM(type = 4) AS employees
      ')
      ->from('users')
      ->where(array(
        'status !=' => -1,
        'status !=' => 0
      ))
      ->get();
    return $this->_res($query);
  }

  public function getReviews($where = FALSE) {
    $this->db
      ->select("
        r.id AS id,
        r.body AS body,
        r.rating AS rating,
        r.status AS status,
        r.created_at AS created_at,
        u.id AS u_id,
        u.fname AS u_fname,
        u.lname AS u_lname,
        u.img_src AS u_img_src,
        re.id AS re_id,
        re.fname AS re_fname,
        re.lname AS re_lname,
        re.img_src AS re_img_src
      ")
      ->from('reviews r')
      ->join('users u', 'u.id = r.user_id')
      ->join('users re', 're.id = r.reviewer_id')
      ->where(array(
        'r.status' => 1,
        'u.status !=' => -1,
        'u.status !=' => 0,
        're.status !=' => -1,
        're.status !=' => 0
      ));
    
    if ($where) {
      $this->db->where($where);
    }

    $this->db
      ->order_by('r.created_at', 'DESC')
      ->limit(6);
    
    $query = $this->db->get();
    return $this->_res($query);
  }
}

?>
