<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apply extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('apply_model');
  }

  public function index() {
    $uid = $this->session->userdata('user')['id'];
    $jid = $this->input->post('jid');
    $subject = $this->_filter($this->input->post('subject'));
    $body = $this->_filter($this->input->post('body'));
    $length = $this->input->post('length') ? $this->input->post('length') : 0;

    $file_names = array();

    // upload first
    // if all upload successful, proceed to add info to db
    for ($i = 0; $i < $length; $i++) {
      $res = $this->_uploadImage('file_'.$i);
      if (!$res['success']) {
        $this->_json(FALSE, 'error', strip_tags($res['error']));
      } else {
        array_push($file_names, $res['data']['file_name']);
      }
    }
    
    $json_file_names = json_encode($file_names);

    $data = array(
      'user_id' => $uid,
      'job_id' => $jid,
      'subject' => $subject,
      'body' => $body,
      'files' => $json_file_names,
      'created_at' => time(),
      'updated_at' => time(),
      'status' => 1
    );
    $res = $this->apply_model->insert($data);

    if (!$res) {
      $this->_json($res, 'error', 'Unable to submit your application.');
    }

    $this->_json($res);
  }

  public function my() {
    // get uid
    $uid = $this->session->userdata('user')['id'];
    
    // get applications with job info using uid
    $applications = $this->apply_model->getWithJobsByUid($uid);
    $applications = $this->_formatJobsArray($applications, function($jobs, $key, $job) {
      $jobs[$key]['a_files'] = json_decode($job['a_files'], TRUE);
      return $jobs;
    });

    $this->_json(TRUE, array(
      'applications' => $applications
    ));
  }

  public function delete() {
    $id = $this->input->post('id');
    $data = array(
      'updated_at' => time(),
      'status' => -1
    );
    $where = array('id' => $id);
    $res = $this->apply_model->update($data, $where);
    $this->_json($res);
  }
}

?>
