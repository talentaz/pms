<?php

namespace App\Models;

class Form_answers_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'form_answers';
        parent::__construct($this->table);
    }

    
}
