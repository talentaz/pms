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
}