<?php

namespace App\Controllers;

class Dynamic_form extends Security_Controller {
    protected $Dynamic_form;

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
        $this->Dynamic_form = model('App\Models\Dynamic_form');
    }
    public function index($slug = "") {
        $view_data["model_info"] = 1;
        return $this->template->rander("dynamic_form/index", $view_data);
    }

    //prepere the data for dynamic form list    
    
    function list_data() {
        
        $result = $this->Dynamic_form->get_details()->getResult();
        echo json_encode(array("data" => $result));
    }
    
    /* open new member modal */

    public function modal_form() {

        $projects = $this->Projects_model->get_projects_id_and_name()->getResult();
        $view_data['projects'] = $projects;

        // print_r($view_data); exit;
        // $view_data["custom_fields"] = 1;

        return $this->template->view('dynamic_form/modal_form', $view_data);
    }

    public function get_tasks_by_project($project_id) {
        // print_r($project_id); exit;
        $tasks = $this->Tasks_model->get_tasks_by_project($project_id)->getResult();
        echo json_encode($tasks);
    }
    
}