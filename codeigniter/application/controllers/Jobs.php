<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs extends MY_Custom_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('jobs_model');
  }

  public function index() {
    $this->load->model('apply_model');

    $uid = $this->session->userdata('user')['id'];
    $id = $this->input->post('id') ? $this->input->post('id') : FALSE;
    $jobs = $this->jobs_model->get($id, array('j.status !=' => 0));
    
    // also get jobs applied by uid
    $jobsAppliedBySess = $this->apply_model->getByUid($uid, array(
      'a.status !=' => -1,
      'a.status !=' => 0,
    ));
    
    $jobs = $this->_formatJobsArray($jobs);
    $appliedIds = $this->jobs_model->_to_col($jobsAppliedBySess, 'job_id');

    // cast to int
    foreach ($appliedIds as $key => $value) {
      $appliedIds[$key] = (int)$value;
    }

    $this->_json(TRUE, array(
      'jobs' => $jobs,
      'appliedIds' => $appliedIds
    ));
  }

  public function my() {
    // get uid
    $uid = $this->session->userdata('user')['id'];
    $jobs = $this->jobs_model->get($uid);
    $jobs = $this->_formatJobsArray($jobs);

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

  public function delete() {
    $id = $this->input->post('id');

    if (!$id) {
      $this->_json(FALSE);
    }

    $res = $this->jobs_model->update(array(
      'status' => -1,
      'updated_at' => time()
    ), array(
      'id' => $id
    ));
    $this->_json($res);
  }
}

?>
