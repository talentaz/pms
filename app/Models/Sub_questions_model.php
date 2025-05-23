<?php

namespace App\Models;

class Sub_questions_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'sub_questions';
        parent::__construct($this->table);
    }

    function get_detail_by_id($id) {
        // $questions_table = $this->db->prefixTable('questions');
        // $sub_questions_table = $this->db->prefixTable('sub_questions');
        
        // $sql = "SELECT $dynamic_form_table.*
        //         FROM $dynamic_form_table
        //         WHERE $dynamic_form_table.id = $id";
        
        // return $this->db->query($sql);
    }
}