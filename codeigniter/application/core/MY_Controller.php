<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
  public function __construct() {
    parent::__construct();
  }
}

/**
 * View Controller
 * 
 * Extend to this controller to load views.
 */
class MY_View_Controller extends MY_Controller {
  
  /**
   * Loads URL helper.
   */
  public function __construct() {
    parent::__construct();
    $this->load->helper('url');
  }

  /**
   * Loads a view in the views/ directory.
   *
   * @param mixed $view Name of the view or an array of names of the views in views/ to be loaded
   * @param mixed $data A string title for the view or an associative array to be passed in the $view
   * @param boolean $view_only Loads the $view only; otherwise, header and footer in views/templates/ are also loaded
   * @return void
   */
  protected function _view($view = 'pages/home', $data = array(), $view_only = FALSE) {
    $header = 'templates/header';
    $footer = 'templates/footer';

    // create array of views
    $views = array();

    // add header
    if (!$view_only) {
      array_push($views, $header);
    }

    // turn $view into array
    if (!is_array($title = $view)) {
      $view = array($view);
    }
    // add $view
    $views = array_merge($views, $view);

    // add footer
    if (!$view_only) {
      array_push($views, $footer);
    }

    // turn $data into array with title
    if (is_string($data)) {
      $data = array('title' => $data);
    }

    // set title if data is null or empty
    if (empty($data) && is_string($title)) {
      $data['title'] = ucfirst(basename($title));
    }

    // load $views
    foreach ($views as $index => $view) {
      // check if page exists
      if (!file_exists(APPPATH . 'views/' . $view . '.php')) {
        show_404();
      }
      // load view
      $this->load->view($view, $index === 0 ? $data : NULL);
    }

  }

  public function _redirect($to = '../#/error') {
    redirect(base_url($to));
  }
}

/**
 * My custom controller
 * 
 * Extends MY_View_Controller
 */
class MY_Custom_Controller extends MY_View_Controller {
  public function __construct() {
    parent::__construct();
    // prevent access if not post request
    if ($this->input->method(TRUE) !== 'POST') {
      $this->_redirect();
    }
    // load lib
    $this->load->library('session');
  }

  public function _filter($str) {
    return strip_tags(trim(addslashes($str)));
  }

  public function _json($success, $arr = array(), $val = NULL) {
    // if $arr is string, make it an array with $val
    if (is_string($arr)) {
      $arr = array($arr => $val);
    }

    $master = array_merge($arr, array('success' => $success));

    echo json_encode($master);
    exit;
  }

  public function _formatObjToDate($date) {
    return date('Y-m-d', strtotime(
      $date['month'].' '.
      $date['day'].' '.
      $date['year']
    ));
  }

  public function _formatDateToObj($raw_date) {
    $date = strtotime($raw_date);
    $month = date('F', $date);
    $day = date('d', $date);
    $year = date('Y', $date);

    return array(
      'month' => $month,
      'day' => $day,
      'year' => $year
    );
  }

  // send email
  private $_EMAIL = 'mail.arnesfield@gmail.com';

  protected function _send_mail($to, $subject, $view, $data, $from_name = 'JSpot Project Team') {
    $this->load->library('email');
    
    // true on third param on view
    $this->email->from($this->_EMAIL, $from_name);
    $this->email->to($to);
    $this->email->subject($subject);
    $this->email->message($this->load->view($view, $data, TRUE));
    
    if ($this->email->send()) {
      return TRUE;
    }
    else {
      return $this->email->print_debugger();
    }
  }

  public function _randStr() {
    return substr(md5(uniqid(rand(), true)), 16, 16);
  }

  public function _uploadImage($file_name, $allowed_types = FALSE, $path = 'uploads/attach/') {
    if (!$allowed_types) {
      $allowed_types = 'jpg|png|pdf|jpeg';
    }

    $config = array(
      'upload_path' => './../'.$path,
      'allowed_types' => $allowed_types,
      'max_size' => 5*1000*1024,
      'file_name' => 'F_' . time()
    );

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload($file_name)) {
      $error = $this->upload->display_errors();
      return array(
        'success' => FALSE,
        'error' => $error
      );
    }

    return array(
      'success' => TRUE,
      'data' => $this->upload->data()
    );
  }

  public function _formatJobsArray($jobs, $cb = FALSE) {
    // format date and json string
    foreach ($jobs as $key => $job) {
      $jobs[$key]['dateFrom'] = strtotime($job['dateFrom']);
      $jobs[$key]['dateTo'] = strtotime($job['dateTo']);
      $jobs[$key]['location'] = json_decode($job['location'], TRUE);
      $jobs[$key]['job_tags'] = json_decode($job['job_tags'], TRUE);
      $jobs[$key]['age_group'] = json_decode($job['age_group'], TRUE);
      if ($cb) {
        $jobs = $cb($jobs, $key, $job);
      }
    }
    return $jobs;
  }

  public function _getAge($time) {
    $date = date('Y-m-d', strtotime($time));

    $dobObject = new DateTime($date);
    $nowObject = new DateTime();

    $diff = $dobObject->diff($nowObject);

    return $diff->y;
  }

  public function _getClosestAge($time) {
    $age = $this->_getAge($time);
    if ($age >= 55) {
      return 55;
    } else if ($age >= 45) {
      return 45;
    } else if ($age >= 35) {
      return 35;
    } else if ($age >= 25) {
      return 25;
    } else if ($age >= 18) {
      return 18;
    }

    return FALSE;
  }

  public function _usersDecode($users, $col = FALSE) {
    $fields = array(
      'places' => 'places',
      'job_tags' => 'job_tags',
      'socials' => 'socials'
    );

    if ($col) {
      foreach ($fields as $key => $value) {
        $fields[$key] = $value;
      }
    }

    foreach ($users as $key => $user) {
      $users[$key][$fields['places']] = json_decode($user[$fields['places']], TRUE);
      $users[$key][$fields['job_tags']] = json_decode($user[$fields['job_tags']], TRUE);
      $users[$key][$fields['socials']] = json_decode($user[$fields['socials']], TRUE);
    }
    return $users;
  }

  public function _concat($arr) {
    $str = '';
    foreach ($arr as $key => $value) {
      $str .= $value.' ';
    }
    return trim($str);
  }
}

?>