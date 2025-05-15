<?php

namespace Inventory\Models;

use App\Models\Crud_model;

class Inventory_settings_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'inventory_settings';
        parent::__construct($this->table);
    }

    function get_setting($inv_id) {
        $result = $this->db_builder->getWhere(array('inv_id' => $inv_id), 1);
        if (count($result->getResult()) == 1) {
            return $result->getRow()->inv_date;
        }
    }
    function get_data12($inv_id) {
        $result = $this->db_builder->getWhere(array('inv_id' => $inv_id), 1);
        if (count($result->getResult()) == 1) {
            return $result->getRow()->inv_date;
        }
    }

    function save_setting($inv_id, $inv_date, $type = "app") {
        $fields = array(
            'inv_id' => $inv_id,
            'inv_date' => $inv_date
        );

        $exists = $this->get_setting($inv_id);
        if ($exists === NULL) {
            $fields["type"] = $type; //type can't be updated

            return $this->db_builder->insert($fields);
        } else {
            $this->db_builder->where('inv_id', $inv_id);
            $this->db_builder->update($fields);
        }
    }

    //find all settings
    function get_all_settings() {
        $settings_table = $this->db->prefixTable('inventory_settings');
        $sql = "SELECT $settings_table.inv_id,  $settings_table.inv_date
        FROM $settings_table";
        return $this->db->query($sql);
    }


}
