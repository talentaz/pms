<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
        <h1>Dynamic Form</h1>
        <div class="title-button-group">
            <?php
                    if ($login_user->is_admin) {
                        echo modal_anchor(get_uri("dynamic_form/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_team_member'), array("class" => "btn btn-default", "title" => app_lang('add_team_member')));
                    }
                    ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="dynamic-form-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>
<?php echo_uri("dynamic_form/list_data/");  echo modal_anchor(get_uri("dynamic_form/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_team_member'), array("class" => "btn btn-default", "title" => app_lang('add_team_member'))); ?>
<script type="text/javascript">
    $(document).ready(function () {
        var visibleContact = false;
        var visibleDelete = true;
        $("#dynamic-form-table").appTable({
            source: '<?php echo_uri("dynamic_form/list_data/") ?>',
            order: [[1, "asc"]],
            columns: [
                {title: 'ID', "class": "w50 text-center all"},
                {title: "TITLE", "class": "w200 all"},
                {title: "PROJECT TITLE", "class": "w15p"},
                {title: "TASK TITLE", "class": "w15p"},
                {visible: visibleDelete, title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],

        });
       
    });
</script>   
