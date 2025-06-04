
<?php echo form_open(get_uri("dynamic_form/save"), array("id" => "dynamic-form", "class" => "general-form outer-repeater", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php 
            echo htmlspecialchars(
                (isset($model_info['dynamic_form']) && is_object($model_info['dynamic_form'])) 
                ? $model_info['dynamic_form']->id 
                : ''
            ); 
        ?>" />
        <div class="form-group">
            <div class="row mb-3">
                <label for="recurring" class=" col-md-3">Title</label>
                <div class="col-md-9">
                    <input type="text"  id="title" name="title" value="<?= htmlspecialchars($model_info['dynamic_form']->title ?? '') ?>" class="form-control" required/>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="recurring" class="col-md-3">Assign Task</label>
                <div class="col-md-9">
                    <select id="project" name="project_id" class="form-control select2" required>
                        <option value="">-- Select Project --</option>
                        <?php foreach ($projects as $project): ?>
                            <option 
                                value="<?= $project->id ?>" 
                                <?= (isset($model_info['dynamic_form']->project_id) && $model_info['dynamic_form']->project_id == $project->id) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($project->title) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select id="task" name="task_id" class="form-control select2 mt-3" <?= !isset($model_info['dynamic_form']->project_id) ? 'disabled' : '' ?> required>
                        <option value="">-- Select Task --</option>
                        <?php if (isset($tasks)): ?>
                            <?php foreach ($tasks as $task): ?>
                                <option 
                                    value="<?= $task->id ?>" 
                                    <?= (isset($model_info['dynamic_form']->task_id) && $model_info['dynamic_form']->task_id == $task->id) ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($task->title) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>
        <!-- add repead when create -->
        <div class="form-group">
            <div data-repeater-list="outer-group" class="outer">
                <?php if (!empty($model_info['questions'])): ?>
                    <?php foreach ($model_info['questions'] as $qIndex => $question): ?>
                        <div data-repeater-item class="row outer">
                            <div class="col-md-3">
                                <label for="problem">Question</label>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <select name="question_type" class="form-control question-type select2" required>
                                            <option value="text_input" <?= ($question['question_type'] ?? '') === 'text_input' ? 'selected' : '' ?>>Text Input</option>
                                            <option value="single_choice" <?= ($question['question_type'] ?? '') === 'single_choice' ? 'selected' : '' ?>>Single checkbox</option>
                                            <option value="multi_choice" <?= ($question['question_type'] ?? '') === 'multi_choice' ? 'selected' : '' ?>>Multiple Choice</option>
                                        </select>
                                    </div>
                                    <div class="col-md-10 col-8">
                                        <input type="text" name="question_title" value="<?= htmlspecialchars($question['question_title'] ?? '') ?>" class="form-control" required/>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <button type="button" data-repeater-delete class="btn btn-danger">
                                            <span data-feather="x" class="icon-16"></span> 
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Inner repeater for sub-questions -->
                                <div class="inner-repeater sub-question">
                                    <div data-repeater-list="inner-group" class="inner">
                                        <?php if (!empty($question['sub_questions'])): ?>
                                            <?php foreach ($question['sub_questions'] as $sub_question): ?>
                                                <div data-repeater-item class="inner row mt-2">
                                                    <div class="col-md-10 col-8">
                                                        <input type="text" name="sub_question_title" value="<?= htmlspecialchars($sub_question['sub_question_title'] ?? '') ?>" class="form-control" required/>
                                                    </div>
                                                    <div class="col-md-2 col-4">
                                                        <button type="button" data-repeater-delete class="btn btn-warning">
                                                            <span data-feather="x" class="icon-16"></span> 
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div data-repeater-item class="inner row mt-2">
                                                <div class="col-md-10 col-8">
                                                    <input type="text" name="sub_question_title" value="" class="form-control" required/>
                                                </div>
                                                <div class="col-md-2 col-4">
                                                    <button type="button" data-repeater-delete class="btn btn-warning">
                                                        <span data-feather="x" class="icon-16"></span> 
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Always show the "Add Option" button -->
                                    <button type="button" data-repeater-create class="btn btn-success inner mt-2">
                                        Add Option
                                    </button>
                                </div>
                            </div>
                            <hr class="mt-2"/>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Default empty question if no questions exist -->
                    <div data-repeater-item class="row outer">
                        <div class="col-md-3">
                            <label for="problem">Question</label>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <select name="question_type" class="form-control question-type select2" required>
                                        <option value="text_input">Text Input</option>
                                        <option value="single_choice">Single checkbox</option>
                                        <option value="multi_choice">Multiple Choice</option>
                                    </select>
                                </div>
                                <div class="col-md-10 col-8">
                                    <input type="text" name="question_title" value="" class="form-control" required/>
                                </div>
                                <div class="col-md-2 col-4">
                                    <button type="button" data-repeater-delete class="btn btn-danger">
                                        <span data-feather="x" class="icon-16"></span> 
                                    </button>
                                </div>
                            </div>
                            <div class="inner-repeater sub-question">
                                <div data-repeater-list="inner-group" class="inner">
                                    <div data-repeater-item class="inner row mt-2">
                                        <div class="col-md-10 col-8">
                                            <input type="text" name="sub_question_title" value="" class="form-control" required/>
                                        </div>
                                        <div class="col-md-2 col-4">
                                            <button type="button" data-repeater-delete class="btn btn-warning">
                                                <span data-feather="x" class="icon-16"></span> 
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" data-repeater-create class="btn btn-success inner mt-2">
                                    Add Option
                                </button>
                            </div>
                        </div>
                        <hr class="mt-2"/>
                    </div>
                <?php endif; ?>
            </div>
            <input data-repeater-create type="button" value="Add Question" class="btn btn-success"/>
        </div>
        <!-- // end repead when create -->
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <?php if(!$model_info['dynamic_form']->id): ?>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
    <?php endif;?>
</div>
<?php echo form_close(); ?>
<style>
    .sub-question {
        width: 90%;
        float: right;
        margin-right: 9px;
    }
</style>
<script>
     $(document).ready(function() {
        $("#dynamic-form").appForm({
            onSuccess: function(result) {
                
            },
            onAjaxSuccess: function(result) {
                
            }
        });
        
        function initializeDropdowns(selectElement) {
            selectElement.select2();
        }

        // repeater js for task
        $('.outer-repeater').repeater({
            repeaters: [{
                selector: '.inner-repeater',
                defaultValues: {
                    'sub_question_title': ''
                }
            }],
            show: function () {
                $(this).slideDown();
                initializeDropdowns($(this).find(".select2"));
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        function toggleInnerRepeater(selectElement) {
            var selectedValue = selectElement.val();
            var $innerRepeater = selectElement.closest('.outer').find('.inner-repeater');

            if (selectedValue === 'text_input') {
                $innerRepeater.hide(); 
                $innerRepeater.find('input').val(''); 
            } else {
                $innerRepeater.show(); 
            }
        }
        $('.modal').on('shown.bs.modal', function() {
            initializeDropdowns($(this).find(".select2"));
            $('.question-type').each(function() {
                toggleInnerRepeater($(this)); 
            });
        });

        $(document).on('change', '.question-type', function() {
            toggleInnerRepeater($(this));
        });

        // add project and task
        $('#project').change(function() {
            const projectId = $(this).val();
            const $taskDropdown = $('#task');
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
        
     })
</script>
 