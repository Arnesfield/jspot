<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reviews_model extends MY_Custom_Model {
  public function get($where = FALSE, $getLatest = FALSE) {
    $created_at = 'r.created_at AS created_at';
    if ($getLatest) {
      $created_at = 'MAX(r.created_at) AS created_at';
    }

    $this->db
      ->select("
        r.id AS id,
        r.body AS body,
        r.rating AS rating,
        r.status AS status,
        $created_at,
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
        'u.status' => 1,
        're.status' => 1
      ));
    
    if ($where) {
      $this->db->where($where);
    }

    if ($getLatest) {
      $this->db->group_by('r.reviewer_id');
    }

    $this->db->order_by('r.created_at', 'DESC');
    
    $query = $this->db->get();
    return $this->_res($query);
  }

  public function insert($review) {
    return $this->db->insert('reviews', $review);
  }

}

?>
