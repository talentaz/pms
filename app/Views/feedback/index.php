<?php
$view_class = "view-container";
$page_wrapper_class = "page-wrapper";
$page_id = "page-content";
include("style.php");

?>
<div id="<?php echo $page_id; ?>" class="<?php echo $page_wrapper_class; ?> clearfix">
    <div class="<?php echo $view_class; ?>">
       <!-- form -->
       <?php echo form_open(get_uri("feedbacks/save"), array("id" => "feed-back-form", "class" => "general-form", "role" => "form")); ?>
       
            <h3 class="quiz-title"><?php echo htmlspecialchars($form->title); ?></h3>
            <input type="hidden" name="dynamic_form_id" value="<?php echo $form->id; ?>">

            <?php foreach ($questions as $question): ?>
                <div class="question ml-sm-5 pl-sm-5 pt-2" data-question-type="<?php echo $question['question_type']; ?>">
                    <div class="py-2 h5"><b><?php echo htmlspecialchars($question['question_title']); ?></b></div>
                    <div class="ml-md-3 ml-sm-3 pl-md-5 pt-sm-0 pt-3" id="options">
                        <?php if ($question['question_type'] === 'text_input'): ?>
                            <input type="text" class="form-control" 
                                name="question_<?php echo $question['id']; ?>" 
                                required
                                placeholder="<?php echo app_lang('type_your_answer'); ?>"/>
                        
                        <?php elseif ($question['question_type'] === 'single_choice'): ?>
                            <?php foreach ($question['sub_questions'] as $sub_question): ?>
                                <label class="options">
                                    <?php echo htmlspecialchars($sub_question['sub_question_title']); ?>
                                    <input type="radio" 
                                        name="question_<?php echo $question['id']; ?>" 
                                        value="<?php echo $sub_question['id']; ?>" 
                                        required>
                                    <span class="checkmark"></span>
                                </label>
                            <?php endforeach; ?>
                        
                        <?php elseif ($question['question_type'] === 'multi_choice'): ?>
                            <?php foreach ($question['sub_questions'] as $sub_question): ?>
                                <label class="options">
                                    <?php echo htmlspecialchars($sub_question['sub_question_title']); ?>
                                    <input type="checkbox" 
                                        name="question_<?php echo $question['id']; ?>[]" 
                                        value="<?php echo $sub_question['id']; ?>">
                                    <span class="checkmark"></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <button type="submit" class="btn btn-primary">
                <span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?>
            </button>
        </form>

       <!-- end form -->
    </div>
</div>
<script>
     $(document).ready(function() {
        $("#feed-back-form").appForm({
            isModal: false,
            onSuccess: function(result) {
                appAlert.success(result.message, {duration: 10000});
            },
            onAjaxSuccess: function(result) {
                
            }
        });
    });
</script>