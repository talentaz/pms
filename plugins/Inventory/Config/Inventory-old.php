<?php

namespace Inventory\Config;

use CodeIgniter\Config\BaseConfig;
use Inventory\Models\Inventory_settings_model;

class Inventory extends BaseConfig {

    public $app_settings_array = array(
        "inventory_file_path" => PLUGIN_URL_PATH . "Inventory/files/inventory_files/"
    );

    public function __construct() {
        $inventory_settings_model = new Inventory_settings_model();

        $settings = $inventory_settings_model->get_all_settings()->getResult();
        foreach ($settings as $setting) {
            $this->app_settings_array[$setting->inv_id] = $setting->inv_date;
        }
    }

}
