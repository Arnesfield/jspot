<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tags_model extends MY_Custom_Model {
  public function get($tag = FALSE) {
    $this->db
      ->from('tags')
      ->where('status', 1);
    
    if ($tag) {
      $this->db->where($tag);
    }

    $query = $this->db->get();
    return $this->_res($query);
  }

  public function insert($tag) {
    return $this->db->insert('tags', $tag);
  }

  public function insertMultiple($tags) {
    if (!$tags) {
      return TRUE;
    }
    // get tags with name
    $q = $this->db
      ->from('tags')
      ->where_in('name', $tags)
      ->where('status', 1)
      ->get();

    $tagsWithNames = $this->_res($q);

    $inDbPlaces = $this->_to_col($tagsWithNames);
    // remove tags in $tags that are in db
    $filtered = $this->_remove_existing($tags, $inDbPlaces);
    // do not continue if there is nothing new
    if (!$filtered) {
      return true;
    }
    // assert that filtered does not exist in db
    // add these to array
    // turn tags to array
    $toAdd = array();
    foreach ($filtered as $key => $tag) {
      array_push($toAdd, array(
        'name' => $tag,
        'status' => 1
      ));
    }
    return $this->db->insert_batch('tags', $toAdd);
  }
}

?>
