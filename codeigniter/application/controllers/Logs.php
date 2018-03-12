<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('logs_model');
  }

  public function index() {
    
  }

  public function views() {
    // get all logs of user
    // get sess if id not set
    $uid = $this->input->post('id') ? $this->input->post('id') : $this->session->userdata('user')['id'];

    // get 30 days
    $views = $this->logs_model->getUserViews(array(
      'user_id' => $uid,
      'viewed_at >=' => strtotime('-30 day')
    ));

    $myViews = array();
    for ($i = 30; $i >= 0; $i--) {
      $day = $i + 1;
      $dayAfter = $i;
      
      $day = strtotime("-$day day");
      $dayAfter = strtotime("-$dayAfter day");
      $usersWhoAlreadyViewed = array();
      
      $thisDay = date('M j', $dayAfter);

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

    $this->_json(TRUE, array(
      'views' => $myViews
    ));
  }

  public function viewed() {
    if (!$this->session->userdata('user')) {
      $this->_json(FALSE);
    }
    
    $vid = $this->session->userdata('user')['id'];
    $uid = $this->input->post('id');
    // only insert view if user exists and if not the same user
    if ($uid == $vid) {
      $this->_json(FALSE);
    }
    
    $res = $this->logs_model->insertView($uid, $vid);
    $this->_json($res);
  }
}
?>