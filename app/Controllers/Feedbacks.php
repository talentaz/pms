<?php

namespace App\Controllers;

class Feedbacks extends App_Controller {

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
        
        // Save form_responses data
        $response_id = $this->Form_responses_model->ci_save($response_data);
        
        if (!$response_id) {
            echo json_encode(array("success" => false, "message" => "Could not save response"));
            return;
        }
        
        // Prepare data for CSV
        $csv_data = [];
        $headers = ['Timestamp'];
        $row_data = [
            date('Y-m-d H:i:s')
        ];
        
        $dynamic_data = $this->Dynamic_form_model->get_detail_by_id($post_data['dynamic_form_id'])->getRow();

        // Process each question
        foreach ($post_data as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $question_id = str_replace('question_', '', $key);
                $question = $this->Questions_model->get_one($question_id);
                
                // Add question title to headers if not already there
                if (!in_array($question->question_title, $headers)) {
                    $headers[] = $question->question_title;
                }
                
                if (is_array($value)) {
                    // Multi-choice answers
                    $answer_value = implode(',', $value);
                    $answer_labels = [];
                    
                    foreach ($value as $sub_question_id) {
                        $sub_question = $this->Sub_questions_model->get_one($sub_question_id);
                        $answer_labels[] = $sub_question->sub_question_title;
                        
                        // Save each answer to database
                        $multi_choice_data = array(
                            'response_id' => $response_id,
                            'question_id' => $question_id,
                            'answer_value' => $sub_question_id,
                        );
                        $this->Form_answers_model->ci_save($multi_choice_data);
                    }
                    
                    $row_data[] = implode(', ', $answer_labels);
                } else {
                    // Single answer (text or single choice)
                    if ($question->question_type == 'single_choice') {
                        $sub_question = $this->Sub_questions_model->get_one($value);
                        $answer_label = $sub_question->sub_question_title;
                        $row_data[] = $answer_label;
                    } else {
                        $answer_label = $value;
                        $row_data[] = $value;
                    }
                    
                    // Save to database
                    $single_choice_data = array(
                        'response_id' => $response_id,
                        'question_id' => $question_id,
                        'answer_value' => $value,
                    );
                    $this->Form_answers_model->ci_save($single_choice_data);
                }
            }
        }
        
        // Generate CSV file
        $this->generate_csv($post_data['dynamic_form_id'], $headers, [$row_data]);
        // update csv file to dynamic form
        if(!$dynamic_data->csv_file){
            $dynamic_data->csv_file = 'feedback_'.$post_data['dynamic_form_id'].'.csv';
            $this->Dynamic_form_model->ci_save($dynamic_data, $post_data['dynamic_form_id']);   
        }
        
        echo json_encode(array(
            "success" => true, 
            "data" => "", 
            'id' => $response_id, 
            'message' => app_lang('record_saved'),
            "redirect_to" => get_uri("feedbacks/thank_you")
        ));
    }

    function generate_csv($form_id, $headers, $data) {
        $filename = 'feedback_'.$form_id.'.csv';
        $filepath = FCPATH.'uploads/feedback_csv/'.$filename;
        
        // Create directory if it doesn't exist
        if (!is_dir(FCPATH.'uploads/feedback_csv')) {
            mkdir(FCPATH.'uploads/feedback_csv', 0777, true);
        }
        
        // Check if file exists
        $file_exists = file_exists($filepath);
        
        // Open file for writing (append if exists, create if not)
        $file = fopen($filepath, $file_exists ? 'a' : 'w');
        
        // If new file, write UTF-8 BOM for Excel compatibility and headers
        if (!$file_exists) {
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, $headers);
        }
        
        // Write data rows
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        
        fclose($file);
    }
}