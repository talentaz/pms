<div class="card">
    <ul id="project-timesheet-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
        <li class="nav-item title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang("timesheets"); ?></h4></li>

        <li class="nav-item"><a class="nav-link" id="timesheet-details-button" role="presentation" href="javascript:;" data-bs-target="#timesheet-details"><?php echo app_lang("details"); ?></a></li>
        <li class="nav-item"><a class="nav-link" role="presentation" href="<?php echo_uri("projects/timesheet_summary/" . $project_id); ?>" data-bs-target="#timesheet-summary"><?php echo app_lang('summary'); ?></a></li>
        <li class="nav-item"><a class="nav-link" role="presentation" href="<?php echo_uri("projects/timesheet_chart/" . $project_id); ?>" data-bs-target="#timesheet-chart"><?php echo app_lang('chart'); ?></a></li>

        <div class="tab-title clearfix no-border">
            <div class="title-button-group">
                <?php
                if ($can_add_log) {
                    echo modal_anchor(get_uri("projects/timelog_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('log_time'), array("class" => "btn btn-default", "title" => app_lang('log_time'), "data-post-project_id" => $project_id));
                }
                ?>
            </div>
        </div>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="timesheet-details">
            <div class="table-responsive">
                <table id="project-timesheet-table" class="display" width="100%">
                </table>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="timesheet-summary"></div>
        <div role="tabpanel" class="tab-pane fade grid-button" id="timesheet-chart"></div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        var optionVisibility = false;
<?php if ($login_user->user_type === "staff" && ($login_user->is_admin || get_array_value($login_user->permissions, "timesheet_manage_permission"))) { ?>
            optionVisibility = true;
<?php } ?>


        var endTimeVisibility = true;
<?php if (get_setting("users_can_input_only_total_hours_instead_of_period")) { ?>
            endTimeVisibility = false;
<?php } ?>

        var filterDropdown = [];

<?php if ($show_members_dropdown) { ?>
            filterDropdown.push({name: "user_id", class: "w200", options: <?php echo $project_members_dropdown; ?>});
<?php } ?>
        filterDropdown.push({name: "task_id", class: "w200", options: <?php echo $tasks_dropdown; ?>});
        filterDropdown.push(<?php echo $custom_field_filters; ?>);

        $("#project-timesheet-table").appTable({
            source: '<?php echo_uri("projects/timesheet_list_data") ?>',
            serverSide: true,
            filterParams: {project_id: "<?php echo $project_id; ?>"},
            order: [[3, "desc"]],
            filterDropdown: filterDropdown,
            rangeDatepicker: [{startDate: {name: "start_date", value: ""}, endDate: {name: "end_date", value: ""}, showClearButton: true, label: "<?php echo app_lang('date'); ?>", ranges: ['today', 'yesterday', 'last_7_days', 'last_30_days', 'this_month', 'last_month', 'this_year', 'last_year' ]}],
            columns: [
                {title: "<?php echo app_lang('member') ?>", "class": "all", order_by: "member_name"},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: "<?php echo app_lang('task') ?>", order_by: "task_title"},
                {visible: false, searchable: false, order_by: "start_time"},
                {title: "<?php echo get_setting("users_can_input_only_total_hours_instead_of_period") ? app_lang("date") : app_lang('start_time') ?>", "iDataSort": 4, order_by: "start_time"},
                {visible: false, searchable: false, order_by: "end_time"},
                {title: "<?php echo app_lang('end_time') ?>", "iDataSort": 6, visible: endTimeVisibility, order_by: "end_time"},
                {title: "<?php echo app_lang('total') ?>", "class": "text-right all"},
                {visible: false, title: "<?php echo app_lang('hours') ?>", "class": "text-right"},
                {visible: false, title: "<?php echo app_lang('hours') ?>", "class": "text-right"}, //follow the decimal seperator setting. Only for print.
                {title: '<?php echo app_lang('note'); ?>', "class": "w200"}
<?php echo $custom_field_headers; ?>,
                {visible: optionVisibility, title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 3, 5, 7, 8, 10, 11], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 3, 5, 7, 8, 9, 11], '<?php echo $custom_field_headers; ?>'),
            summation: [{column: 8, fieldName: "total_timesheet_value", dataType: 'time'}]
        });
    }
    );
</script>
