<?php
if (!function_exists("make_project_tabs_data")) {

    function make_project_tabs_data($default_project_tabs = array(), $is_client = false)
    {
        $project_tab_order = get_setting("project_tab_order");
        $project_tab_order_of_clients = get_setting("project_tab_order_of_clients");
        $custom_project_tabs = array();

        if ($is_client && $project_tab_order_of_clients) {
            //user is client
            $custom_project_tabs = explode(',', $project_tab_order_of_clients);
        } else if (!$is_client && $project_tab_order) {
            //user is team member
            $custom_project_tabs = explode(',', $project_tab_order);
        }

        $final_projects_tabs = array();
        if ($custom_project_tabs) {
            foreach ($custom_project_tabs as $custom_project_tab) {
                if (array_key_exists($custom_project_tab, $default_project_tabs)) {
                    $final_projects_tabs[$custom_project_tab] = get_array_value($default_project_tabs, $custom_project_tab);
                }
            }
        }

        $final_projects_tabs = $final_projects_tabs ? $final_projects_tabs : $default_project_tabs;

        foreach ($final_projects_tabs as $key => $value) {
            echo "<li class='nav-item' role='presentation'><a class='nav-link' data-bs-toggle='tab' href='" . get_uri($value) . "' data-bs-target='#project-$key-section'>" . app_lang($key) . "</a></li>";
        }
    }
}
?>

<div class="page-content project-details-view clearfix">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="project-title-section">
                    <div class="page-title no-bg clearfix mb5 no-border">
                        <div>
                            <h1 class="pl0">
                                <span title="<?php echo $project_info->title_language_key ? app_lang($project_info->title_language_key) : $project_info->status_title; ?>"><i data-feather="<?php echo $project_info->status_icon; ?>" class='icon'></i></span>

                                <?php echo $project_info->title; ?>

                                <?php if (!(get_setting("disable_access_favorite_project_option_for_clients") && $login_user->user_type == "client")) { ?>
                                    <span id="star-mark">
                                        <?php
                                        if ($is_starred) {
                                            echo view('projects/star/starred', array("project_id" => $project_info->id));
                                        } else {
                                            echo view('projects/star/not_starred', array("project_id" => $project_info->id));
                                        }
                                        ?>
                                    </span>
                                <?php } ?>
                            </h1>
                        </div>

                        <div class="project-title-button-group-section">
                            <div class="title-button-group mr0" id="project-timer-box">
                                <?php echo view("projects/project_title_buttons"); ?>
                                |
                                <a href="<?php echo base_url('index.php/inventory/index/' . $project_info->id); ?>" class="btn btn-primary">
                                    Inventory
                                </a>
                                <?php
                                  $session = session();
                                  $session->set('url_pro_id', $project_info->id);
                                 ?>
                            </div>
                        </div>
                    </div>

                    <ul id="project-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs rounded classic mb20 scrollable-tabs" role="tablist">
                        <?php
                        if ($login_user->user_type === "staff") {
                            //default tab order
                            $project_tabs = array(
                                "overview" => "projects/overview/" . $project_info->id,
                                "tasks_list" => "tasks/project_tasks/" . $project_info->id,
                                "tasks_kanban" => "tasks/project_tasks_kanban/" . $project_info->id,
                            );
                            if ($show_milestone_info) {
                                $project_tabs["milestones"] = "projects/milestones/" . $project_info->id;
                            }

                            if ($show_gantt_info) {
                                $project_tabs["gantt"] = "tasks/gantt/" . $project_info->id;
                            }

                            if ($show_note_info) {
                                $project_tabs["notes"] = "projects/notes/" . $project_info->id;
                            }

                            if ($show_files) {
                                $project_tabs["files"] = "projects/files/" . $project_info->id . "/" . $files_tab . "/" . $folder_id;
                            }

                            if ($can_comment_on_projects) {
                                $project_tabs["comments"] = "projects/comments/" . $project_info->id;
                            }

                            if ($project_info->project_type === "client_project" && $show_customer_feedback) {
                                $project_tabs["customer_feedback"] = "projects/customer_feedback/" . $project_info->id;
                            }

                            if ($show_timesheet_info) {
                                $project_tabs["timesheets"] = "projects/timesheets/" . $project_info->id;
                            }

                            if ($show_invoice_info && $project_info->project_type === "client_project") {
                                $project_tabs["invoices"] = "projects/invoices/" . $project_info->id;
                                $project_tabs["payments"] = "projects/payments/" . $project_info->id;
                            }

                            if ($show_expense_info) {
                                $project_tabs["expenses"] = "projects/expenses/" . $project_info->id;
                            }

                            if ($show_contract_info && $project_info->project_type === "client_project") {
                                $project_tabs["contracts"] = "projects/contracts/" . $project_info->id;
                            }

                            if ($show_ticket_info && $project_info->project_type === "client_project") {
                                $project_tabs["tickets"] = "projects/tickets/" . $project_info->id;
                            }

                            $project_tabs_of_hook_of_staff = array();
                            $project_tabs_of_hook_of_staff = app_hooks()->apply_filters('app_filter_team_members_project_details_tab', $project_tabs_of_hook_of_staff, $project_info->id);
                            $project_tabs_of_hook_of_staff = is_array($project_tabs_of_hook_of_staff) ? $project_tabs_of_hook_of_staff : array();
                            $project_tabs = array_merge($project_tabs, $project_tabs_of_hook_of_staff);

                            make_project_tabs_data($project_tabs);
                        } else {
                            //default tab order
                            $project_tabs = array(
                                "overview" => "projects/overview_for_client/" . $project_info->id
                            );

                            if ($show_tasks) {
                                $project_tabs["tasks_list"] = "tasks/project_tasks/" . $project_info->id;
                                $project_tabs["tasks_kanban"] = "tasks/project_tasks_kanban/" . $project_info->id;
                            }

                            if ($show_files) {
                                $project_tabs["files"] = "projects/files/" . $project_info->id;
                            }

                            $project_tabs["comments"] = "projects/customer_feedback/" . $project_info->id;

                            if ($show_milestone_info) {
                                $project_tabs["milestones"] = "projects/milestones/" . $project_info->id;
                            }

                            if ($show_gantt_info) {
                                $project_tabs["gantt"] = "tasks/gantt/" . $project_info->id;
                            }

                            if ($show_timesheet_info) {
                                $project_tabs["timesheets"] = "projects/timesheets/" . $project_info->id;
                            }

                            if (get_setting("module_invoice") && $show_invoice_info) {
                                //check left menu settings
                                $left_menu = get_setting("user_" . $login_user->id . "_left_menu") ? get_setting("user_" . $login_user->id . "_left_menu") : get_setting("default_client_left_menu");
                                $left_menu = $left_menu ? json_decode(json_encode(@unserialize($left_menu)), true) : false;
                                if (!$left_menu || in_array("invoices", array_column($left_menu, "name"))) {
                                    $project_tabs["invoices"] = "projects/invoices/" . $project_info->id . "/" . $login_user->client_id;
                                }
                            }

                            if (get_setting("project_reference_in_tickets") && $project_info->project_type === "client_project" && can_client_access($login_user->client_permissions, "ticket")) {
                                $project_tabs["tickets"] = "projects/tickets/" . $project_info->id . "/" . $login_user->client_id;
                            }

                            $project_tabs_of_hook_of_client = array();
                            $project_tabs_of_hook_of_client = app_hooks()->apply_filters('app_filter_clients_project_details_tab', $project_tabs_of_hook_of_client, $project_info->id);
                            $project_tabs_of_hook_of_client = is_array($project_tabs_of_hook_of_client) ? $project_tabs_of_hook_of_client : array();
                            $project_tabs = array_merge($project_tabs, $project_tabs_of_hook_of_client);

                            make_project_tabs_data($project_tabs, true);
                        }
                        ?>

                    </ul>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active" id="project-overview-section"></div>
                    <div role="tabpanel" class="tab-pane fade grid-button" id="project-tasks_list-section"></div>
                    <div role="tabpanel" class="tab-pane fade grid-button" id="project-tasks_kanban-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-milestones-section"></div>
                    <div role="tabpanel" class="tab-pane fade grid-button" id="project-gantt-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-files-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-comments-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-customer_feedback-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-notes-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-timesheets-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-invoices-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-payments-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-expenses-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-contracts-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-tickets-section"></div>

                    <?php
                    if ($login_user->user_type === "staff") {
                        $project_tabs_of_hook_targets = $project_tabs_of_hook_of_staff;
                    } else {
                        $project_tabs_of_hook_targets = $project_tabs_of_hook_of_client;
                    }

                    foreach ($project_tabs_of_hook_targets as $key => $value) {
                    ?>
                        <div role="tabpanel" class="tab-pane fade" id="project-<?php echo $key; ?>-section"></div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
//if we get any task parameter, we'll show the task details modal automatically
$preview_task_id = get_array_value($_GET, 'task');
if ($preview_task_id) {
    echo modal_anchor(get_uri("tasks/view"), "", array("id" => "preview_task_link", "title" => app_lang('task_info') . " #$preview_task_id", "data-post-id" => $preview_task_id, "data-modal-lg" => "1"));
}
?>

<?php
load_css(array(
    "assets/js/gantt-chart/frappe-gantt.css",
));
load_js(array(
    "assets/js/gantt-chart/frappe-gantt.js",
));
?>

<script type="text/javascript">
    RELOAD_PROJECT_VIEW_AFTER_UPDATE = true;

    $(document).ready(function() {
        setTimeout(function() {
            var tab = "<?php echo $tab; ?>";
            if (tab === "comment") {
                $("[data-bs-target='#project-comments-section']").trigger("click");
            } else if (tab === "customer_feedback") {
                $("[data-bs-target='#project-customer_feedback-section']").trigger("click");
            } else if (tab === "files" || tab === "file_manager") {
                $("[data-bs-target='#project-files-section']").trigger("click");
            } else if (tab === "gantt") {
                $("[data-bs-target='#project-gantt-section']").trigger("click");
            } else if (tab === "tasks") {
                $("[data-bs-target='#project-tasks_list-section']").trigger("click");
            } else if (tab === "tasks_kanban") {
                $("[data-bs-target='#project-tasks_kanban-section']").trigger("click");
            } else if (tab === "milestones") {
                $("[data-bs-target='#project-milestones-section']").trigger("click");
            }
        }, 210);


        //open task details modal automatically

        if ($("#preview_task_link").length) {
            $("#preview_task_link").trigger("click");
        }

    });
</script>

<?php echo view("tasks/sub_tasks_helper_js"); ?>
