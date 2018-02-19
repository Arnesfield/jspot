<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('users_model');
  }

  public function index() {
    $users = $this->users_model->get();
    // convert all users birthdate to object
    foreach ($users as $key => $user) {
      $birthdate = strtotime($user['birthdate']);
      $month = date('F', $birthdate);
      $day = date('d', $birthdate);
      $year = date('Y', $birthdate);

      $users[$key]['birthdate'] = array(
        'month' => $month,
        'day' => $day,
        'year' => $year
      );
    }
    
    $this->_json(TRUE, array(
      'users' => $users
    ));
  }

  public function duplicateEmailCheck() {
    $email = $this->_filter($this->input->post('email'));
    $id = $this->input->post('id') ? $this->input->post('id') : FALSE;

    $where = array('email' => $email);
    
    $users = $this->users_model->get($where);

    if (!$users) {
      $res['exists'] = false;
      $this->_json(TRUE, $res);
    }

    $user = $users[0];
    
    // if id is set
    // if same id, then does not exist
    if ($id && $id == $user['id']) {
      $res['exists'] = false;
      $this->_json(TRUE, $res);
    }

    $res['exists'] = true;
    $this->_json(TRUE, $res);
  }

  public function delete() {
    $id = $this->input->post('id') ? $this->input->post('id') : FALSE;
    if (!$id) {
      $this->_json(FALSE);
    }

    $res = $this->users_model->update($id, array(
      'status' => -1,
      // also update time
      'updated_at' => time()
    ));
    $this->_json($res);
  }

  // adds and updates!
  public function add() {
    $post_values = array(
      'email',
      'fname',
      'lname',
      'img_src',
      'bio',
      'birthdate',
      'type',
      'contact',
      'status',
      'alsoPassword',
      'password',
      'img_src',
      'places',
      'job_tags',
      'socials',
      'mode',
      'settings'
    );

    // check if mode is edit
    if ($this->input->post('mode') == 'Edit') {
      array_push($post_values, 'id');
    }

    // turn name to variable
    foreach ($post_values as $key => $post) {
      $curr = $this->input->post($post);
      $$post = is_string($curr) ? $this->_filter($curr) : $curr;
    }

    $_places = $places;
    $_job_tags = $job_tags;
    $_socials = $socials;
    $_settings = $settings;

    $places = json_encode($places);
    $job_tags = json_encode($job_tags);
    $socials = json_encode($socials);
    $settings = json_encode($settings);

    $birthdate = date('Y-m-d', strtotime(
      $birthdate['month'].' '.
      $birthdate['day'].' '.
      $birthdate['year']
    ));

    $user = array(
      'fname' => $fname,
      'lname' => $lname,
      'email' => $email,
      'img_src' => $img_src,
      'bio' => $bio,
      'birthdate' => $birthdate,
      'type' => $type,
      'contact' => $contact,
      'job_tags' => $job_tags,
      'places' => $places,
      'socials' => $socials,
      'status' => $status,
      'updated_at' => time(),
      'settings' => $settings
    );
    
    if ($mode != 'Edit') {
      $user['created_at'] = time();
    }
    
    if (json_decode($alsoPassword)) {
      $user['password'] = $password = password_hash($password, PASSWORD_BCRYPT);
    }

    $this->load->model(array('places_model', 'tags_model'));

    if ($mode == 'Edit') {
      $res = $this->users_model->update($id, $user);
    }
    else {
      $res = $this->users_model->insert($user);
    }
    $this->places_model->insertMultiple($_places);
    $this->tags_model->insertMultiple($_job_tags);

    $this->_json($res);
  }
}

?>
