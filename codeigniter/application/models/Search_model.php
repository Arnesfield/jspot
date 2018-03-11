<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_model extends MY_Custom_Model {
  public function searchJobs($search = FALSE) {
    // search in title, description, location, job_tags
    $this->db
      ->select('
        j.*,
        u.fname AS creator_fname,
        u.lname AS creator_lname,
        u.img_src AS creator_img_src
      ')
      ->from('jobs j')
      ->join('users u', 'u.id = j.created_by')
      ->where('j.status !=', -1)
      ->where('u.type !=', 2)
      ->where('u.status', 1);

    if ($search) {
      $search = strtolower($search);
      $this->db
        ->where("(
          MATCH(j.title) AGAINST('$search') OR
          MATCH(j.description) AGAINST('$search') OR
          MATCH(j.location) AGAINST('$search') OR
          MATCH(j.job_tags) AGAINST('$search') OR
          LOWER(j.title) LIKE '%$search%' OR
          LOWER(j.description) LIKE '%$search%' OR
          LOWER(j.location) LIKE '%$search%' OR
          LOWER(j.job_tags) LIKE '%$search%'
        )", NULL, FALSE);
    }

    $this->db
      ->order_by('updated_at', 'DESC')
      ->order_by('created_at', 'DESC');

    $query = $this->db->get();
    return $this->_res($query);
  }

  public function suggestJobs($cond = FALSE, $age = FALSE, $type = FALSE) {
    $this->db
      ->select('
        j.*,
        u.fname AS creator_fname,
        u.lname AS creator_lname,
        u.img_src AS creator_img_src
      ')
      ->from('jobs j')
      ->join('users u', 'u.id = j.created_by')
      ->where('j.status !=', -1)
      ->where('u.type !=', 2)
      ->where('u.status', 1);
    
    if ($cond) {
      $this->db->where($cond, NULL, FALSE);
    }

    if ($age) {
      $this->db->like('j.age_group', $age);
    }

    if ($type) {
      $this->db->where('u.type', $type);
    }

    $this->db
      ->order_by('j.updated_at', 'DESC')
      ->order_by('j.created_at', 'DESC');

    $query = $this->db->get();
    return $this->_res($query);
  }
}

?>
