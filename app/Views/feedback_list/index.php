<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
        <h1>Feedback List</h1>
        
        <div class="table-responsive">
            <table id="feedback-form-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var visibleContact = false;
        var visibleDelete = true;
        
        $("#feedback-form-table").appTable({
            source: '<?php echo_uri("feedback_list/list_data/") ?>',
            order: [[0, "asc"]],
            columns: [
                {title: 'ID', "class": "w50 text-center all"},
                {title: "QUIZ TITLE", "class": "w200 all"},
                {title: "PROJECT TITLE", "class": "w15p"},
                {title: "TASK TITLE", "class": "w15p"},
                {visible: visibleDelete, title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],

        });
       
    });
</script>   
