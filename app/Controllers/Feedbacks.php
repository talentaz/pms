<?php

namespace App\Controllers;

class Feedbacks extends Security_Controller {

    function __construct() {
        parent::__construct();
    }

    function index($id) {
        
        $view_data['topbar'] = "includes/public/topbar";
        $view_data['left_menu'] = false;
        $view_data['form'] = $this->Dynamic_form_model->get_detail_by_id($id)->getRow();
        // print_r($view_data['form']); exit;
        $view_data['questions'] = $this->Questions_model->get_detail_by_id($id);
        return $this->template->rander("feedback/index", $view_data);
    }

    function save() {
        $this->validate_submitted_data(array(
            "dynamic_form_id" => "required|numeric"
        ));
        
        $post_data = $this->request->getPost();
        $response_data = array(
            'dynamic_form_id' => $post_data['dynamic_form_id'],
        );
        
        // save form_reponses data
        $response_id = $this->Form_responses_model->ci_save($response_data);
        foreach ($post_data as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $question_id = str_replace('question_', '', $key);
                
                if (is_array($value)) {
                    // Multi-choice answers
                    foreach ($value as $sub_question_id) {
                        $multi_choice_data = array(
                            'response_id' => $response_id,
                            'question_id' => $question_id,
                            'answer_value' => $sub_question_id,
                        );
                        $this->Form_answers_model->ci_save($multi_choice_data);
                    }
                } else {
                    // Single answer (text or single choice)
                    $single_choice_data = array(
                        'response_id' => $response_id,
                        'question_id' => $question_id,
                        'answer_value' => $value,
                    );
                    $this->Form_answers_model->ci_save($single_choice_data);
                }
            }
        }
        
        echo json_encode(array("success" => true, "data" => "", 'id' => "", 'message' => app_lang('record_saved')));
        
    }
}