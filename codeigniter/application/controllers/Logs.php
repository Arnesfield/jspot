<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('logs_model');
    $this->load->model('jobs_model');
  }

  public function index() {
    
  }

  public function views() {
    // get all logs of user
    // get sess if id not set
    $uid = $this->input->post('id') ? $this->input->post('id') : $this->session->userdata('user')['id'];
    $jobs = $this->getJobsViews($uid);

    // get 30 days
    $views = $this->logs_model->getViews(array(
      'user_id' => $uid,
      'viewed_at >=' => strtotime('-30 day', strtotime('00:00:00'))
    ));

    $myViews = $this->createMagicDates($views);

    $this->_json(TRUE, array(
      'views' => $myViews,
      'jobs' => $jobs['jobs'],
      'titles' => $jobs['titles']
    ));
  }

  private function createMagicDates($views) {
    $TODAY = strtotime('00:00:00');
    $myViews = array();
    for ($i = 30; $i >= -1; $i--) {
      $day = $i + 1;
      $dayAfter = $i;
      
      $day = strtotime("-$day day", $TODAY);
      $dayAfter = strtotime("-$dayAfter day", $TODAY);
      $usersWhoAlreadyViewed = array();
      
      $thisDay = date('M j', $day);

      // make day to 0 if not existing yet
      if (!array_key_exists($thisDay, $myViews)) {
        $myViews[$thisDay] = 0;
      }

      foreach ($views as $key => $view) {
        $userViewing = $view['viewer_id'];
        // if user already viewed, do not include
        if (in_array($userViewing, $usersWhoAlreadyViewed)) {
          continue;
        }

        // add that user to already viewed
        array_push($usersWhoAlreadyViewed, $userViewing);

        $viewedDate = $view['viewed_at'];

        // do not add date if the user already viewed at that day
        if ($day <= $viewedDate && $viewedDate < $dayAfter) {
          $myViews[$thisDay] += 1;
        }
      }
    }
    return $myViews;
  }

  private function getJobsViews($uid) {
    $jobs = $this->logs_model->getJobIdsOfUser($uid);

    if (!$jobs) {
      return array(
        'jobs' => array(),
        'titles' => array()
      );
    }
    
    // make jobs id => title
    $jobTitles = array();
    foreach ($jobs as $key => $job) {
      $jobTitles[$job['id']] = $job['title'];
    }

    $jobIds = $this->logs_model->_to_col($jobs, 'id');

    // get 30 days
    $jobViews = $this->logs_model->getViews(array(
      'user_id' => 0,
      'viewed_at >=' => strtotime('-30 day')
    ), $jobIds);

    // make job views into id => value
    $newJobViews = array();
    foreach ($jobViews as $key => $job) {
      // make it an array!
      if (!array_key_exists($job['job_id'], $newJobViews)) {
        $newJobViews[$job['job_id']] = array();
      }
      array_push($newJobViews[$job['job_id']], $job);
    }

    // now after that's done
    // loop on every newJobViews
    // then convert those values into magic
    foreach ($newJobViews as $key => $job) {
      $newJobViews[$key] = $this->createMagicDates($job);
    }

    $allExistingKeys = array_keys($newJobViews);
    $allCurrentKeys = array_keys($jobTitles);
    // only retain titles with existing keys
    foreach ($allCurrentKeys as $key => $value) {
      // if this key is not included in existing
      if (!in_array($value, $allExistingKeys)) {
        // remove dat job title
        unset($jobTitles[$value]);
      }
    }

    return array(
      'jobs' => $newJobViews,
      'titles' => $jobTitles
    );
  }

  public function viewed() {
    if (!$this->session->userdata('user')) {
      $this->_json(FALSE);
    }
    
    $vid = $this->session->userdata('user')['id'];
    $uid = $this->input->post('id');
    $type = $this->input->post('type') ? $this->input->post('type') : 'users';
    // only insert view if user exists and if not the same user
    if ($uid == $vid && $type == 'users') {
      $this->_json(FALSE);
    }

    if ($type == 'jobs') {
      // get the creator of that job
      $jobs = $this->jobs_model->simpleGet($uid);
      if (!$jobs) {
        $this->_json(FALSE);
      }
      
      $job = $jobs[0];
      
      // do not insert if the creator views their own job
      if ($job['created_by'] == $vid) {
        $this->_json(FALSE);
      }
    }
    
    $res = $this->logs_model->insertView($uid, $vid, $type);
    $this->_json($res);
  }
}
?>