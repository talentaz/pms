<?php

namespace Inventory\Controllers;

use App\Controllers\Security_Controller;

class Inventory extends Security_Controller {

  protected $Inventory_view_model;

  function __construct() {
      parent::__construct();
      $this->access_only_admin_or_settings_admin();
      $this->Inventory_view_model = new \Inventory\Models\Inventory_view_model();
  }

      function index() {
          return $this->template->rander('Inventory\Views\inventory\index');
      }
      function add() {
        $request = service('request');

        $fields = array(
          //'inv_id'  => $request->getPost('inv_id'),
          'inv_date' => $request->getPost('inv_date'),
          'inv_rec_no'          => $request->getPost('inv_rec_no'),
          'inv_supplier'          => $request->getPost('inv_supplier'),
          'inv_name'          => $request->getPost('inv_name'),
          'inv_model_no'          => $request->getPost('inv_model_no'),
          'inv_serial_no'          => $request->getPost('inv_serial_no'),
          'inv_pro_id'          => $request->getPost('inv_pro_id'),
          'inv_addedby'          => $request->getPost('inv_addedby')
        );

          $this->Inventory_view_model->saveinv($fields);
          //echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
          return $this->template->rander("Inventory\Views\inventory\index");
      }
      function update($id) {
          $request = service('request');
          $fields = [
              'inv_id'  => $request->getPost('inv_id'),
              'inv_date' => $request->getPost('inv_date'),
              'inv_rec_no' => $request->getPost('inv_rec_no'),
              'inv_supplier'          => $request->getPost('inv_supplier'),
              'inv_name'          => $request->getPost('inv_name'),
              'inv_model_no'          => $request->getPost('inv_model_no'),
              'inv_serial_no'          => $request->getPost('inv_serial_no'),
              'inv_pro_id'          => $request->getPost('inv_pro_id'),
              'inv_addedby'          => $request->getPost('inv_addedby')
          ];

          //$this->Inventory_view_model->saveinv($fields);
          //$model = new Inventory_view_model();
            $this->Inventory_view_model->updateinv($id,$fields);

            return $this->template->rander("Inventory\Views\inventory\index");
        //  echo json_encode(["success" => true, 'message' => app_lang('settings_updated']);

      }
      function delete($id) {
        $request = service('request');
        $this->Inventory_view_model->deleteinv($id); // Load the model
        return $this->template->rander("Inventory\Views\inventory\index");
      }

      function model_form($id = null) {
          $view_data = [];

          if ($id !== null) {
             // Get the inventory item from the model
             $inventory_item = $this->get_inventory_item($id);
             $view_data['inventory_item'] = $inventory_item;
             //echo ($this->get_inventory_item($id));
           }


          return $this->template->view('Inventory\Views\inventory\model_form', $view_data);
      }

        function get_inventory_item($id) {
          $db = \Config\Database::connect();
          // Query the database to get the item based on ID
          return $db->table('inventory_settings')  // Adjust your table name here
                      ->where('inv_id', $id)
                      ->get()
                      ->getRow();  // Use getRow() to return a single result
        }


}
