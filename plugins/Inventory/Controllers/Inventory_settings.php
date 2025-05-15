<?php

namespace Inventory\Controllers;

use App\Controllers\Security_Controller;

class Inventory_settings extends Security_Controller {

    protected $Inventory_settings_model;

    function __construct() {
        parent::__construct();
        $this->access_only_admin_or_settings_admin();
        $this->Inventory_settings_model = new \Inventory\Models\Inventory_settings_model();
    }

    function index() {
        return $this->template->rander("Inventory\Views\settings\index");
    }

    function save() {
        $this->Inventory_settings_model->save_setting("setting_inventory", $this->request->getPost("setting_inventory"));

        //save file
        $files_data = move_files_from_temp_dir_to_permanent_dir(get_inventory_setting("inventory_file_path"), "inventory");
        $unserialize_files_data = unserialize($files_data);
        $inventory_file = get_array_value($unserialize_files_data, 0);
        if ($inventory_file) {
            if (get_inventory_setting("file_inventory")) {
                //delete old file if exists
                $this->delete_inventory_file(get_inventory_setting("file_inventory"));
            }

            $this->Inventory_settings_model->save_setting("file_inventory", serialize($inventory_file));
        }

        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    private function delete_inventory_file($inventory_file) {
        try {
            $inventory_file = unserialize($inventory_file);
        } catch (\Exception $ex) {
            echo json_encode(array("success" => false, 'message' => $ex->getMessage()));
            exit();
        }

        delete_app_files(get_inventory_setting("inventory_file_path"), array($inventory_file));
    }

    /* upload a file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file */

    function validate_inventory_file() {
        return validate_post_file($this->request->getPost("file_name"));
    }

}
