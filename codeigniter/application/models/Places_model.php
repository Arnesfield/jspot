<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Places_model extends MY_Custom_Model {
  public function get($place = FALSE) {
    $this->db
      ->from('places')
      ->where('status', 1);
    
    if ($place) {
      $this->db->where($place);
    }

    $query = $this->db->get();
    return $this->_res($query);
  }

  public function insert($place) {
    return $this->db->insert('places', $place);
  }

  public function insertMultiple($places) {
    if (!$places) {
      return TRUE;
    }
    // get places with name
    $q = $this->db
      ->from('places')
      ->where_in('name', $places)
      ->where('status', 1)
      ->get();
    $placesWithNames = $this->_res($q);

    $inDbPlaces = $this->_to_col($placesWithNames);
    // remove places in $places that are in db
    $filtered = $this->_remove_existing($places, $inDbPlaces);
    // do not continue if there is nothing new
    if (!$filtered) {
      return true;
    }
    // assert that filtered does not exist in db
    // add these to array
    // turn places to array
    $toAdd = array();
    foreach ($filtered as $key => $place) {
      array_push($toAdd, array(
        'name' => $place,
        'status' => 1
      ));
    }
    
    return $this->db->insert_batch('places', $toAdd);
  }
}

?>
