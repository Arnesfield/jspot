<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('search_model');
    $this->load->model('users_model');
  }

  public function index() {
    $search = $this->_filter($this->input->post('search'));
    
    $jobs = array();
    $users = array();

    // do always search when user sess not exists
    if ($search || !$this->session->userdata('user')) {
      $jobs = $this->search_model->searchJobs($search);
      $jobs = $this->_formatJobsArray($jobs);
      $users = $this->users_model->search($search);
      $users = $this->_usersDecode($users);
    } else {
      $jobs = $this->suggestedJobs(TRUE);
      $users = $this->suggestedUsers(TRUE);
    }

    $this->_json(TRUE, array(
      'jobs' => $jobs,
      'users' => $users
    ));
  }

  public function suggestedUsers($return = FALSE) {
    $uid = $this->session->userdata('user')['id'];
    $tempUsers = $this->users_model->get(array('id' => $uid));
    
    $type = FALSE;
    $cond = FALSE;

    if ($tempUsers) {
      $user = $tempUsers[0];
      if ($user['type'] == 3) {
        $type = 4;
      } else if ($user['type'] == 4) {
        $type = 3;
      }
      $places = $this->_concat(json_decode($user['places'], TRUE));
      $job_tags = $this->_concat(json_decode($user['job_tags'], TRUE));

      $cond = "(
        MATCH(places) AGAINST('$places') OR
        MATCH(job_tags) AGAINST('$job_tags')
      )";
    }

    $users = $this->users_model->suggest($cond, $type);
    $users = $this->_usersDecode($users);

    if ($return) {
      return $users;
    } else {
      $this->_json(TRUE, array(
        'jobs' => array(),
        'users' => $users
      ));
    }
  }

  public function suggestedJobs($return = FALSE) {
    $uid = $this->session->userdata('user')['id'];

    $users = $this->users_model->get(array('id' => $uid));

    $cond = FALSE;
    $age = FALSE;
    $type = FALSE;

    if ($users) {
      $user = $users[0];
      if ($user['type'] == 3) {
        $type = 4;
      } else if ($user['type'] == 4) {
        $type = 3;
      }

      $places = $this->_concat(json_decode($user['places'], TRUE));
      $job_tags = $this->_concat(json_decode($user['job_tags'], TRUE));
      $age = $this->_getClosestAge($user['birthdate']);

      $cond = "(
        MATCH(j.location) AGAINST('$places') OR
        MATCH(j.job_tags) AGAINST('$job_tags')
      )";
    }

    $jobs = $this->search_model->suggestJobs($cond, $age, $type);
    $jobs = $this->_formatJobsArray($jobs);

    if ($return) {
      return $jobs;
    } else {
      $this->_json(TRUE, array(
        'jobs' => $jobs,
        'users' => array()
      ));
    }
  }
}

?>
