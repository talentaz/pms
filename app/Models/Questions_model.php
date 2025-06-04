<?php

namespace App\Models;

class Questions_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'questions';
        parent::__construct($this->table);
    }

    function get_detail_by_id($id) {
        $questions_table = $this->db->prefixTable('questions');
        $sub_questions_table = $this->db->prefixTable('sub_questions');
       
        // Get the main question
        $questions = $this->db->table($questions_table)
                            ->where('dynamic_form_id', $id)
                            ->get()
                            ->getResult();
        
        if (!$questions) {
            return [];
        }
        
        $result = [];
        
        foreach ($questions as $question) {
            $sub_questions = $this->db->table($sub_questions_table)
                                     ->where('question_id', $question->id)
                                     ->get()
                                     ->getResult();
           
            $sub_questions_array = [];
            foreach ($sub_questions as $sub) {
                $sub_questions_array[] = [
                    'id' => $sub->id,
                    'question_id' => $sub->question_id,
                    'sub_question_title' => $sub->sub_question_title,
                ];
            }
    
            $result[] = [
                'id' => $question->id,
                'question_type' => $question->question_type,
                'question_title' => $question->question_title,
                'sub_questions' => $sub_questions_array,
            ];
        }
        return $result;
    }

    function get_form_data($form_id) {
        $questions_table = $this->db->prefixTable('questions');
        $sub_questions_table = $this->db->prefixTable('sub_questions');
        $form_answers_table = $this->db->prefixTable('form_answers');
        // Get all questions for the form
        $questions = $this->db->table($questions_table)
                            ->select('id, question_title, question_type')
                            ->where('dynamic_form_id', $form_id)
                            ->get()
                            ->getResult();
        
        $result = [];
        
        foreach ($questions as $question) {
            if ($question->question_type == 'single_choice' || $question->question_type == 'multi_choice') {
                // Get sub-questions (options) for multiple/single choice questions
                $options = $this->db->table($sub_questions_table)
                                  ->select('id, sub_question_title')
                                  ->where('question_id', $question->id)
                                  ->get()
                                  ->getResult();
               
                // Get answer counts for each option
                $answer_counts = [];
                foreach ($options as $option) {
                    $count = $this->db->table($form_answers_table)
                                     ->where('question_id', $question->id)
                                     ->where('answer_value', $option->id)
                                    //  ->like('answer_value', $option->id) // For multi-choice, answer_value might contain multiple IDs
                                     ->countAllResults();
                    
                    $answer_counts[] = [
                        'label' => $option->sub_question_title,
                        'count' => $count
                    ];
                }
                
                $result[] = [
                    'question_id' => $question->id,
                    'question_title' => $question->question_title,
                    'question_type' => $question->question_type,
                    'options' => $answer_counts
                ];
            } else {
                // For text input questions, we might want to show word cloud or just count responses
                $count = $this->db->table($form_answers_table)
                                 ->where('question_id', $question->id)
                                 ->countAllResults();              
                $result[] = [
                    'question_id' => $question->id,
                    'question_title' => $question->question_title,
                    'question_type' => $question->question_type,
                    'response_count' => $count
                ];
            }
        }
        
        return $result;
    }
}