<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reviews_model extends MY_Custom_Model {
  public function get($where = FALSE) {
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

    $this->db->order_by('r.created_at', 'DESC');
    
    $query = $this->db->get();
    return $this->_res($query);
  }

  public function getRatings($uid) {
    $this->db
      ->select('
        r.id AS id,
        r.rating AS rating,
        r.reviewer_id AS reviewer_id,
        r.created_at AS created_at
      ')
      ->from('reviews r')
      ->join('users u', 'u.id = r.user_id')
      ->join('users re', 're.id = r.reviewer_id')
      ->join('
        (
          SELECT user_id, reviewer_id, MAX(created_at) AS created_at, status
          FROM reviews
          WHERE status = 1
          GROUP BY reviewer_id, user_id
        ) b
      ', '
        r.user_id = b.user_id AND
        r.reviewer_id = b.reviewer_id AND
        r.created_at = b.created_at
      ', 'INNER', FALSE)
      ->where('r.user_id', $uid)
      ->where(array(
        'r.status' => 1,
        'u.status !=' => -1,
        'u.status !=' => 0,
        're.status !=' => -1,
        're.status !=' => 0
      ));

    $query = $this->db->get();
    return $this->_res($query);
  }

  public function insert($review) {
    return $this->db->insert('reviews', $review);
  }

  public function update($data, $where) {
    return $this->db
      ->set($data)
      ->where($where)
      ->update('reviews');
  }

}

?>
