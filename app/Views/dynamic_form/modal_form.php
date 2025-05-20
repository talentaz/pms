<?php echo form_open(get_uri("dynamic_form/save"), array("id" => "dynamic-form", "class" => "general-form outer-repeater", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <div class="form-group">
            <div class="row">
                <label for="recurring" class=" col-md-3">Title</label>
                <div class=" col-md-9">
                <?php
                    echo form_input(array(
                        "id" => "title",
                        "name" => "title",
                        "class" => "form-control",
                        "placeholder" => app_lang('title'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="recurring" class="col-md-3">Assign Task</label>
                <div class="col-md-9">
                    <select id="project" name="project_id" class="form-control select2" required>
                        <option value="">-- Select Project --</option>
                        <?php foreach ($projects as $project): ?>
                            <option value="<?= $project->id ?>"><?= htmlspecialchars($project->title) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="task" name="task_id" class="form-control select2 mt-3" disabled required>
                        <option value="">-- Select Project First --</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div data-repeater-list="outer-group" class="outer">
                <div data-repeater-item class="row outer">
                    <div class="col-md-3">
                        <label for="porblem">Question 1</label>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <select name="question_type" class="inner form-control question-type select2" required>
                                    <option value="text_input">Text Input</option>
                                    <option value="single_choice">Single checkbox</option>
                                    <option value="multi_choice">Multiple Choice</option>
                                </select>
                            </div>
                            <div class="col-md-10 col-8">
                                <input type="text" name="question_title" value="A" class="outer form-control" required/>
                            </div>
                            <div class="col-md-2 col-4">
                                <button data-repeater-delete class="btn btn-danger outer">
                                    <span data-feather="x" class="icon-16"></span> 
                                </button>
                            </div>
                        </div>
                        <div class="inner-repeater">
                            <div data-repeater-list="inner-group" class="inner">
                                <div data-repeater-item class="inner row">
                                    <div class="col-md-10 col-8 mt-2">
                                        <input type="text" name="sub_question_title" value="B" class="inner form-control" required/>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <button data-repeater-delete class="btn btn-warning inner">
                                            <span data-feather="x" class="icon-16"></span> 
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button data-repeater-create class="btn btn-success inner">
                                Add Option
                            </button>
                        </div>
                    </div>
                    <hr class="mt-2"/>
                </div>
            </div>
            <input data-repeater-create type="button" value="Add Question" class="outer btn btn-success"/>
        </div> 
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script>
     $(document).ready(function() {
       
        initializeDropdowns($("#dynamic-form .select2"));
        $("#dynamic-form").appForm({
            onSuccess: function(result) {
                
            },
            onAjaxSuccess: function(result) {
                
            }
        });

        function initializeDropdowns(selectElement) {
            selectElement.select2();
        }
        // function initializeDropdowns() {
        //     $("#dynamic-form .select2").select2();
        // }

        // repeater js for task
        $('.outer-repeater').repeater({
            repeaters: [{
                selector: '.inner-repeater',
            }],
            show: function () {
                $(this).slideDown();
                 initializeDropdowns($(this).find(".select2"));
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        $(document).on('change', '.question-type', function() {
            var selectedValue = $(this).val();
            var $innerRepeater = $(this).closest('.outer').find('.inner-repeater');

            if (selectedValue === 'text_input') {
                $innerRepeater.hide(); // Hide the inner repeater
                $innerRepeater.find('input').val(''); // Clear inner inputs if needed
            } else {
                $innerRepeater.show(); // Show the inner repeater for other options
            }
        });
        // add project and task
        $('#project').change(function() {
            const projectId = $(this).val();
            const $taskDropdown = $('#task');
            
            // Reset and disable task dropdown
            $taskDropdown.empty().append('<option value="">-- Loading Tasks --</option>').prop('disabled', true);
            
            if (projectId) {
                $.ajax({
                    url: '<?= site_url('dynamic_form/get_tasks_by_project') ?>/' + projectId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(tasks) {
                        $taskDropdown.empty();
                        
                        if (tasks && tasks.length > 0) {
                            $taskDropdown.append('<option value="">-- Select Task --</option>');
                            $.each(tasks, function(index, task) {
                                $taskDropdown.append($('<option>', {
                                    value: task.id,
                                    text: task.title
                                }));
                            });
                            $taskDropdown.prop('disabled', false);
                        } else {
                            $taskDropdown.append('<option value="">No tasks available</option>');
                        }
                    },
                    error: function() {
                        $taskDropdown.empty().append('<option value="">Error loading tasks</option>');
                    }
                });
            } else {
                $taskDropdown.empty().append('<option value="">-- Select Project First --</option>');
            }
        });
        
        // $('[data-repeater-item]').each(function() {
        //     initializeDropdowns($(this));
        // });
     })
</script>
 