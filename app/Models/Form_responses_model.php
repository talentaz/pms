<?php

namespace App\Models;

class Form_responses_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'form_responses';
        parent::__construct($this->table);
    }
  
}
