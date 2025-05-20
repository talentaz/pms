<?php

namespace App\Models;

class Sub_questions_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'sub_questions';
        parent::__construct($this->table);
    }
}