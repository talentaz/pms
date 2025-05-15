<?php

namespace Inventory\Models;

use CodeIgniter\Model;

class Inventory_add_model extends Model {

    protected $table = 'inventory_settings'; // Table name
    protected $primaryKey = 'inv_id';
    protected $allowedFields = ['inv_id', 'inv_date', 'inv_rec_no']; // Allowed fields

    public function insert_data($data) {
        //return $this->insert($data); // CodeIgniter's built-in insert function
    }

    public function test() {
    return $this->db->query("SELECT 1")->getRow();
}
}
