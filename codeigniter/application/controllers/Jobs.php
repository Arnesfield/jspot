<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('jobs_model');
  }

  public function index() {
    $jobs = $this->jobs_model->get();

    // format date
    foreach ($jobs as $key => $job) {
      $jobs[$key]['dateFrom'] = $this->_formatDateToObj($job['dateFrom']);
      $jobs[$key]['dateTo'] = $this->_formatDateToObj($job['dateTo']);
    }

    $this->_json(TRUE, array(
      'jobs' => $jobs
    ));
  }

  public function my() {
    // get uid
    $uid = $this->session->userdata('user')['id'];
    $jobs = $this->jobs_model->get($uid);

    // convert location and job_tags to json array
    foreach ($jobs as $key => $job) {
      // also format date to unix timestamp
      $jobs[$key]['dateFrom'] = strtotime($job['dateFrom']);
      $jobs[$key]['dateTo'] = strtotime($job['dateTo']);
      $jobs[$key]['location'] = json_decode($job['location'], TRUE);
      $jobs[$key]['job_tags'] = json_decode($job['job_tags'], TRUE);
      $jobs[$key]['age_group'] = json_decode($job['age_group'], TRUE);
    }

    $this->_json(TRUE, array(
      'jobs' => $jobs
    ));
  }

  public function add() {
    $mode = $this->input->post('mode');
    $title = $this->_filter($this->input->post('title'));
    $description = $this->_filter($this->input->post('description'));
    $timeFrom = $this->input->post('timeFrom');
    $timeTo = $this->input->post('timeTo');
    $dateFrom = $this->input->post('dateFrom');
    $dateTo = $this->input->post('dateTo');
    $age_group = $this->input->post('age_group');

    // make sure to add these in db
    $location = $this->input->post('location');
    $job_tags = $this->input->post('job_tags');

    // format date
    $dateFrom = $this->_formatObjToDate($dateFrom);
    $dateTo = $this->_formatObjToDate($dateTo);

    // get uid
    $uid = $this->session->userdata('user')['id'];

    $data = array(
      'title' => $title,
      'description' => $description,
      'timeFrom' => $timeFrom,
      'timeTo' => $timeTo,
      'dateFrom' => $dateFrom,
      'dateTo' => $dateTo,
      'age_group' => $age_group,
      'location' => $location,
      'job_tags' => $job_tags,
      'created_by' => $uid,
      'updated_at' => time(),
      'status' => 1
    );

    // if mode is create, update created at
    if ($mode == 'Create') {
      $data['created_at'] = time();
    }

    $this->load->model('places_model');
    $this->load->model('tags_model');

    $_location = json_decode($location, TRUE);
    $_job_tags = json_decode($job_tags, TRUE);

    $this->places_model->insertMultiple($_location);
    $this->tags_model->insertMultiple($_job_tags);

    $res = $this->jobs_model->insert($data);
    $this->_json($res);
  }
}

?>
