<?php

namespace App\Models;

class Questions_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'questions';
        parent::__construct($this->table);
    }
}