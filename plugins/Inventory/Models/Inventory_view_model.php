<?php

namespace Inventory\Models;

use App\Models\Crud_model;

class Inventory_view_model extends Crud_model {

    protected $table = null;

    function __construct() {
        //$this->table = 'inventory_settings';
      //  parent::__construct($this->table);
        $this->table = 'inventory_settings';
        parent::__construct($this->table);

    }

    //find all settings
    function get_all_inventory() {
      $settings_table = $this->db->prefixTable('inventory_settings');
      $sql = "SELECT *
      FROM $settings_table";
      return $this->db->query($sql);
    }

    public function saveinv($fields) {
        //print_r($fields); // Debugging

        // Connect to the database
        $db = \Config\Database::connect();

        // Insert data using Query Builder
        $query = $db->table('inventory_settings')->insert($fields);

        if ($query) {
            return "Data inserted successfully!";
        } else {
            return "Failed to insert data!";
        }
    }
    public function updateinv($id, $fields) {
        // Debugging
        // print_r($fields);

        // Connect to the database
        $db = \Config\Database::connect();

        // Update data using Query Builder
        $query = $db->table('inventory_settings')->where('inv_id', $id)->update($fields);
        if ($query) {
            return "Data updated successfully!";
        } else {
            return "Failed to update data!";
        }
    }
    public function deleteinv($id) {
        // Connect to the database
        $db = \Config\Database::connect();


        // Delete data using Query Builder
        $query = $db->table('inventory_settings')->delete(['inv_id' => $id]);

        // Check if the deletion was successful
        if ($query) {
            return "Data deleted successfully!";
        } else {
            return "Failed to delete data!";
        }
    }


}
