<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apply extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('apply_model');
    $this->load->model('jobs_model');
    $this->load->model('users_model');
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
      'interview_date' => 0,
      'interview_time' => '00:00:00',
      'created_at' => time(),
      'updated_at' => time(),
      'status' => 1
    );

    // get name of creator of jid
    $actualJob = $this->jobs_model->simpleGet($jid)[0];
    $actualCreatorOfJob = $this->users_model->get(array('id' => $actualJob['created_by']))[0];
    $actualApplier = $this->users_model->get(array('id' => $uid))[0];
    $email = $actualCreatorOfJob['email'];

    // send mail first
    $send_data = array(
      'id' => $uid,
      'name' => $actualApplier['fname'].' '.$actualApplier['lname'],
      'title' => $actualJob['title'],
      'subject' => $subject,
      'body' => $body,
      'email' => $email,
      'fname' => $actualCreatorOfJob['fname'],
      'lname' => $actualCreatorOfJob['lname']
    );

    $sent = $this->_send_mail($email, $subject, 'email/apply', $send_data);

    if ($sent !== TRUE) {
      $this->_json(FALSE, array(
        'error' => 'Unable to send email.',
        'debug' => $sent
      ));
    }

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
    $applications = $this->apply_model->getWithJobs(array(
      'a.user_id' => $uid
    ));
    $applications = $this->formatApplyArray($applications);

    $this->_json(TRUE, array(
      'applications' => $applications
    ));
  }

  public function applicants($return = FALSE) {
    // get uid
    $uid = $this->session->userdata('user')['id'];
    $jid = $this->input->post('jid') ? $this->input->post('jid') : FALSE;

    $where = array('j.created_by' => $uid);
    if ($jid !== FALSE) {
      $where['j.id'] = $jid;
    }
    
    // get applications with job info using uid
    $applicants = $this->apply_model->getWithJobs($where);
    $applicants = $this->formatApplyArray($applicants);

    if ($return) {
      return $applicants;
    } else {
      $this->_json(TRUE, array(
        'applicants' => $applicants
      ));
    }
  }

  public function jobs() {
    // get uid
    $this->load->model('jobs_model');

    $uid = $this->session->userdata('user')['id'];
    $jobs = $this->jobs_model->get($uid);
    $jobs = $this->_formatJobsArray($jobs);

    $applicants = $this->applicants(TRUE);

    $this->_json(TRUE, array(
      'jobs' => $jobs,
      'applicants' => $applicants
    ));
  }

  private function formatApplyArray($applications) {
    return $this->_formatJobsArray($applications, function($jobs, $key, $job) {
      $jobs[$key]['a_files'] = json_decode($job['a_files'], TRUE);
      $jobs[$key]['a_interview_time'] = substr($job['a_interview_time'], 0, strrpos($job['a_interview_time'], ':'));
      return $jobs;
    });
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

  public function update() {
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    $date = $this->input->post('date') ? $this->input->post('date') : FALSE;
    $time = $this->input->post('time') ? $this->input->post('time') : FALSE;

    $data = array('status' => $status);

    if ($date) {
      $data['interview_date'] = strtotime($date);
    }
    if ($time) {
      $data['interview_time'] = $time . ':00';
    }

    $where = array('id' => $id);

    $msg = '';
    $bodyMsg = '';

    if ($status == 0) {
      $msg = 'Your application has been denied';
      $bodyMsg = 'You have been denied';
    } else if ($status == 1) {
      $msg = 'Your application is pending';
      $bodyMsg = 'You are pending';
    } else if ($status == 2) {
      $msg = 'Your application has been accepted';
      $bodyMsg = 'You have been accepted';
    } else if ($status == 3) {
      $msg = 'You are hired';
      $bodyMsg = 'You have been hired';
    }

    // using the id of application, get applier, and send email to that applier
    $apply = $this->apply_model->getWithJobs(array(
      'a.id' => $id
    ))[0];
    $email = $apply['applier_email'];

    // send email first
    $send_data = array(
      'id' => $apply['applier_id'],
      'email' => $email,
      'fname' => $apply['applier_fname'],
      'lname' => $apply['applier_lname'],
      'msg' => $msg,
      'body' => $bodyMsg,
      'title' => $apply['title']
    );

    if ($date) {
      $send_data['date'] = $data['interview_date'];
    }
    if ($time) {
      $send_data['time'] = $data['interview_time'];
    }

    $sent = $this->_send_mail($email, $msg, 'email/job', $send_data);

    if ($sent !== TRUE) {
      $this->_json(FALSE, array(
        'error' => 'Unable to send email.',
        'debug' => $sent
      ));
    }

    $res = $this->apply_model->update($data, $where);
    $this->_json($res);
  }
}

?>
