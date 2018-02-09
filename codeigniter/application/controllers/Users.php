<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('users_model');
  }

  public function index() {
    $this->_json(array(
      'success' => TRUE,
      'users' => $this->users_model->get()
    ));
  }

  // adds and updates!
  public function add() {
    $post_values = array(
      'email',
      'fname',
      'lname',
      'img_src',
      'bio',
      'type',
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
    if ($this->input->post('mode') == 'edit') {
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

    $user = array(
      'fname' => $fname,
      'lname' => $lname,
      'email' => $email,
      'img_src' => $img_src,
      'bio' => $bio,
      'type' => $type,
      'job_tags' => $job_tags,
      'places' => $places,
      'socials' => $socials,
      'status' => $status,
      'updated_at' => time(),
      'settings' => $settings
    );
    
    if ($mode != 'edit') {
      $user['created_at'] = time();
    }

    if ($alsoPassword) {
      $user['password'] = $password = password_hash($password, PASSWORD_BCRYPT);
    }

    $this->load->model(array('places_model', 'tags_model'));

    if ($mode == 'edit') {
      $res = $this->users_model->update($id, $user);
    }
    else {
      $res = $this->users_model->insert($user);
    }
    $this->places_model->insertMultiple($_places);
    $this->tags_model->insertMultiple($_job_tags);

    $this->_json('success', $res);
  }
}

?>
