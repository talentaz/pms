<?php

namespace App\Models;

class Dynamic_form_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'dynamic_form';
        parent::__construct($this->table);
    }

    function get_details() {
        $dynamic_form_table = $this->db->prefixTable('dynamic_form');
        $project_table = $this->db->prefixTable('projects');
        $task_table = $this->db->prefixTable('tasks');
        
        $sql = "SELECT $dynamic_form_table.*, 
                $project_table.title as project_title,
                $task_table.title as task_title
                FROM $dynamic_form_table
                LEFT JOIN $project_table ON $project_table.id = $dynamic_form_table.project_id
                LEFT JOIN $task_table ON $task_table.id = $dynamic_form_table.task_id";
        // print_r($sql);
        return $this->db->query($sql);
    }

    function get_detail_by_id($id) {
        $dynamic_form_table = $this->db->prefixTable('dynamic_form');
        
        $sql = "SELECT $dynamic_form_table.*
                FROM $dynamic_form_table
                WHERE $dynamic_form_table.id = $id";
        
        return $this->db->query($sql);
    }

}
