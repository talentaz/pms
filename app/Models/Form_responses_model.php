<?php

namespace App\Models;

class Form_responses_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'form_responses';
        parent::__construct($this->table);
    }
 
    function get_details() {
        $dynamic_form_table = $this->db->prefixTable('dynamic_form');
        $project_table = $this->db->prefixTable('projects');
        $task_table = $this->db->prefixTable('tasks');
        $form_response_table = $this->db->prefixTable('form_responses');
        
        $sql = "SELECT $form_response_table.id, 
               $project_table.title as project_title,
               $task_table.title as task_title,
               $dynamic_form_table.title as quiz_title
            FROM $form_response_table
            LEFT JOIN $dynamic_form_table ON $form_response_table.dynamic_form_id = $dynamic_form_table.id
            LEFT JOIN $project_table ON $project_table.id = $dynamic_form_table.project_id
            LEFT JOIN $task_table ON $task_table.id = $dynamic_form_table.task_id";
        // print_r($sql); exit;
        return $this->db->query($sql);
    }
}
