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
      $users[$key]['birthdate'] = $this->_formatDateToObj($user['birthdate']);
    }
    
    $this->_json(TRUE, array(
      'users' => $users
    ));
  }

  public function signup() {
    $fname = $this->_filter($this->input->post('fname'));
    $lname = $this->_filter($this->input->post('lname'));
    $email = $this->_filter($this->input->post('email'));
    $password = $this->input->post('password');
    $birthdate = $this->input->post('birthdate');
    $type = $this->input->post('type');

    $password = password_hash($password, PASSWORD_BCRYPT);

    $_birthdate = json_decode($birthdate, TRUE);
    $birthdate = $this->_formatObjToDate($_birthdate);

    $verification_code = $this->_randStr();

    $user = array(
      'fname' => $fname,
      'lname' => $lname,
      'email' => $email,
      'password' => $password,
      'birthdate' => $birthdate,
      'type' => $type,

      'verification_code' => $verification_code,

      'bio' => '',
      'img_src' => '',
      'job_tags' => '[]',
      'places' => '[]',
      'socials' => '[]',
      'contact' => '',
      'created_at' => time(),
      'updated_at' => time(),
      'settings' => '{}',
      'status' => 2
    );

    // send email first
    $send_data = array(
      'code' => $verification_code,
      'email' => $email,
      'fname' => $fname,
      'lname' => $lname
    );
    // $sent = $this->_send_mail($email, 'Email Verification', 'email/email_verification', $send_data);

    // if ($sent !== TRUE) {
    //   $this->_json(FALSE, array(
    //     'error' => 'Unable to send email verification.',
    //     'debug' => $sent
    //   ));
    // }

    $res = $this->users_model->insert($user);
    $this->_json($res);
  }

  public function profile() {
    $id = $this->input->post('id');

    if (!$id) {
      $this->_json(FALSE);
    }

    // do not get admin
    $users = $this->users_model->get(array(
      'id' => $id,
      'type !=' => 2
    ));

    if (!$users) {
      $this->_json(FALSE);
    }

    $user = $users[0];

    // decode json and birthdate
    $user['birthdate'] = $this->_formatDateToObj($user['birthdate']);
    $user['places'] = json_decode($user['places'], TRUE);
    $user['job_tags'] = json_decode($user['job_tags'], TRUE);
    $user['socials'] = json_decode($user['socials'], TRUE);

    $this->_json(TRUE, 'user', $user);
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
      'bio',
      // 'birthdate',
      'type',
      'contact',
      'status',
      'alsoPassword',
      'password',
      // 'places',
      // 'job_tags',
      // 'socials',
      'mode',
      // 'settings'
    );

    $img_src = FALSE;

    // upload image first
    // check if image exists
    if ($_FILES && $_FILES['file']) {
      $res = $this->_uploadImage('file', 'jpg|png|jpeg', 'uploads/images/');
      if (!$res['success']) {
        $this->_json(FALSE, 'error', strip_tags($res['error']));
      } else {
        $img_src = $res['data']['file_name'];
      }
    }

    // check if mode is edit
    if ($this->input->post('mode') == 'Edit') {
      array_push($post_values, 'id');
    }

    // turn name to variable
    foreach ($post_values as $key => $post) {
      $curr = $this->input->post($post);
      $$post = is_string($curr) ? $this->_filter($curr) : $curr;
    }

    // this is json string lol
    $places = $this->input->post('places');
    $job_tags = $this->input->post('job_tags');
    $socials = $this->input->post('socials');
    $settings = $this->input->post('settings');
    $birthdate = $this->input->post('birthdate');

    $_places = json_decode($places, TRUE);
    $_job_tags = json_decode($job_tags, TRUE);
    $_socials = json_decode($socials, TRUE);
    $_settings = json_decode($settings, TRUE);

    $_birthdate = json_decode($birthdate, TRUE);

    $birthdate = $this->_formatObjToDate($_birthdate);

    $user = array(
      'fname' => $fname,
      'lname' => $lname,
      'email' => $email,
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

    // change img src if yeah you get it
    if ($img_src !== FALSE) {
      $user['img_src'] = $img_src;
    }
    
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

    $uid = $this->session->userdata('user')['id'];
    $user = $this->users_model->get(array('id' => $uid))[0];
    $this->session->set_userdata(array(
      'user' => $user,
      'auth' => $user['type']
    ));

    $this->_json($res);
  }
}

?>
