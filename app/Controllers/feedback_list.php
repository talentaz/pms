<?php

namespace App\Controllers;

class Feedback_list extends Security_Controller {
    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
    }
    public function index($slug = "") {
        $view_data["model_info"] = 1;
        return $this->template->rander("feedback_list/index", $view_data);
    }

    function list_data() {
        
        $list_data = $this->Dynamic_form_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data){
        // $form_url = anchor(get_uri("feedbacks/" . $data->id), $data->id);
        $row_data = array(
            anchor(get_uri("feedbacks/" . $data->id), $data->id, array("target" => "_blank")),
            $data->title,
            $data->project_title,
            $data->task_title,

       );
        $row_data[] = anchor(get_uri("feedbacks/" . $data->id), "<i data-feather='link' class='icon-16'></i>", array("target" => "_blank"))   
                     .modal_anchor(get_uri("feedback_list/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_feed_back'), "data-post-id" => $data->id));
        return $row_data;
     }

     /* open new member modal */

    function modal_form() {
        $dynamic_form_id = $this->request->getPost('id');
        $data['form_data'] = $this->Questions_model->get_form_data($dynamic_form_id);
        $dynamic_form_data = $this->Dynamic_form_model->get_detail_by_id($dynamic_form_id)->getRow();
        $data['form_title'] = $dynamic_form_data->title;
        $data['csv_file'] =$dynamic_form_data->csv_file;
        $data['dynamic_form_id'] = $dynamic_form_id;
        // print_r($data['form_title']); exit;
       
        return $this->template->view('feedback_list/modal_form', $data);
    }
}