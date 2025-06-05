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
       if ($data->csv_file) {
            $row_data[] = 
              anchor(get_uri("feedbacks/" . $data->id), "<i data-feather='link' class='icon-16'></i>", array("target" => "_blank"))   
            . modal_anchor(get_uri("dynamic_form/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_feed_back'), "data-post-id" => $data->id));
            // . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_expense'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("dynamic_form/delete"), "data-action" => "delete-confirmation")); 
       } else {
            $row_data[] = 
              anchor(get_uri("feedbacks/" . $data->id), "<i data-feather='link' class='icon-16'></i>", array("target" => "_blank"))   
            . modal_anchor(get_uri("dynamic_form/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_feed_back'), "data-post-id" => $data->id))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_expense'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("dynamic_form/delete"), "data-action" => "delete-confirmation"));   
       }
       
       return $row_data;
    }
    
    /* open new member modal */

    function modal_form() {

        $projects = $this->Projects_model->get_projects_id_and_name()->getResult();
        $view_data['projects'] = $projects;

        $dynamic_form_id = $this->request->getPost('id');
        if($dynamic_form_id){
            $model_info['dynamic_form'] = $this->Dynamic_form_model->get_detail_by_id($dynamic_form_id)->getRow();
            if($model_info['dynamic_form']->id){
                $model_info['questions'] = $this->Questions_model->get_detail_by_id($dynamic_form_id);
            }
            $view_data['model_info'] = $model_info;
            $tasks = $this->Tasks_model->get_tasks_by_project($model_info['dynamic_form']->project_id)->getResult();
            $view_data['tasks'] = $tasks;
        }
        // print_r($model_info['dynamic_form']->id); exit;
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
 
        $dynamic_form_id = $this->request->getPost('id');
        if($dynamic_form_id){
            // update logic
            $this->Dynamic_form_model->ci_save($data, $dynamic_form_id);
            
            // delete existing questions and sub question
            $questions = $this->Questions_model->get_detail_by_id($dynamic_form_id);
            foreach ($questions as $question) {
                // Delete sub-questions
                if (!empty($question['sub_questions'])) {
                    foreach ($question['sub_questions'] as $sub_question) {
                        $this->Sub_questions_model->delete_permanently($sub_question['id']);
                    }
                }
                // Delete the main question
                $this->Questions_model->delete_permanently($question['id']);
                
            }
            // insert data 
            foreach ($this->request->getPost('outer-group') as $question) {
                // Prepare data for the main question
                $questionData = [
                    'dynamic_form_id' => $dynamic_form_id, 
                    'question_type' => $question['question_type'],
                    'question_title' => $question['question_title'],
                ];
                
                $questionId = $this->Questions_model->ci_save($questionData);
                
                if ($question['question_type'] !== 'text_input') {
                    foreach ($question['inner-group'] as $subQuestion) {
                        if (isset($subQuestion['sub_question_title']) && !empty($subQuestion['sub_question_title'])){
                            $subQuestionData = [
                                'question_id' => $questionId, 
                                'sub_question_title' => $subQuestion['sub_question_title'],
                            ];
                            // Insert the sub-question
                            $this->Sub_questions_model->ci_save($subQuestionData);
                        }
                    }
                }
            }
            // Return success response
            echo json_encode(array("success" => true, "data" => "", 'id' => "", 'message' => app_lang('record_saved')));
        } else {
            // insert logic
            
            $id = $this->Dynamic_form_model->ci_save($data);
    
            if($id){ 
                foreach ($this->request->getPost('outer-group') as $question) {
                    // Prepare data for the main question
                    $questionData = [
                        'dynamic_form_id' => $id, // Link question to the dynamic form
                        'question_type' => $question['question_type'],
                        'question_title' => $question['question_title'],
                    ];
                    
                    $questionId = $this->Questions_model->ci_save($questionData);
                    if ($question['question_type'] !== 'text_input') {
                        foreach ($question['inner-group'] as $subQuestion) {
                            if (isset($subQuestion['sub_question_title']) && !empty($subQuestion['sub_question_title'])){
                                $subQuestionData = [
                                    'question_id' => $questionId, // Link sub-question to the main question
                                    'sub_question_title' => $subQuestion['sub_question_title'],
                                ];
                                // Insert the sub-question
                                $this->Sub_questions_model->ci_save($subQuestionData);
                                
                            }
                        }
                    }
                }
    
            }
            // Return success response
            echo json_encode(array("success" => true, "data" => "", 'id' => $id, 'message' => app_lang('record_saved')));
        }
    }
    
    function delete() {
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');
        $questions = $this->Questions_model->get_detail_by_id($id);

        if(count($questions) > 0) {
            foreach ($questions as $question) {
                // Delete sub-questions
                if (!empty($question['sub_questions'])) {
                    foreach ($question['sub_questions'] as $sub_question) {
                        $this->Sub_questions_model->delete_permanently($sub_question['id']);
                    }
                }
                // Delete the main question
                $this->Questions_model->delete_permanently($question['id']);
            }
        }

        if($this->Dynamic_form_model->delete_permanently($id)){
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }
}