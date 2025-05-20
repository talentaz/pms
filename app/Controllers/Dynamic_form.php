<?php

namespace App\Controllers;

class Dynamic_form extends Security_Controller {
    

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
        // $this->Dynamic_form_model = model('App\Models\Dynamic_form_model');
    }
    public function index($slug = "") {
        $view_data["model_info"] = 1;
        return $this->template->rander("dynamic_form/index", $view_data);
    }

    //prepere the data for dynamic form list    
    function list_data() {
        
        $list_data = $this->Dynamic_form_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data){
       $row_data = array(
            $data->id,
            $data->title,
            $data->project_title,
            $data->task_title,

       );
       $row_data[] = modal_anchor(get_uri("expenses/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_expense'), "data-post-id" => $data->id))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_expense'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("expenses/delete"), "data-action" => "delete-confirmation"));
       return $row_data;
    }
    
    /* open new member modal */

    public function modal_form() {

        $projects = $this->Projects_model->get_projects_id_and_name()->getResult();
        $view_data['projects'] = $projects;


        return $this->template->view('dynamic_form/modal_form', $view_data);
    }

    public function get_tasks_by_project($project_id) {
        // print_r($project_id); exit;
        $tasks = $this->Tasks_model->get_tasks_by_project($project_id)->getResult();
        echo json_encode($tasks);
    }

    function save() {
        $this->validate_submitted_data(array(
            "project_id" => "numeric",
            "task_id" => "numeric",
            "title" => "required",
        ));
        
        $data = array(
            "title" =>$this->request->getPost('title'),
            "project_id" =>$this->request->getPost('project_id'),
            "task_id" =>$this->request->getPost('task_id'),
        );
        $id = $this->Dynamic_form_model->ci_save($data);
        // $id=1;
        if($id){
            foreach ($this->request->getPost('outer-group') as $question) {
                // Prepare data for the main question
                $questionData = [
                    'dynamic_form_id' => $id, // Link question to the dynamic form
                    'question_type' => $question['question_type'],
                    'question_title' => $question['question_title'],
                ];
                $questionId = $this->Questions_model->ci_save($questionData);
                
                if ($question['question_type'] !== 'text_input' && isset($question['inner-group'])) {
                    // print_r($question['inner-group']); exit;
                    foreach ($question['inner-group'] as $subQuestion) {
                        $subQuestionData = [
                            'question_id' => $questionId, // Link sub-question to the main question
                            'sub_question_title' => $subQuestion['sub_question_title'],
                        ];
                        // Insert the sub-question
                        $this->Questions_model->ci_save($subQuestionData);
                    }
                }
            }
            
            echo json_encode(array("success" => true, "data" => "", 'id' => $id, 'message' => app_lang('record_saved')));

        }
        // $this->Dynamic_form->save_dynamic_form($data);

        // Return success response
        echo json_encode(['status' => 'success', 'message' => 'Dynamic form and questions saved successfully!']);
    }
    
}