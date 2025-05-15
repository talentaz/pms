<?php
$uri = service('uri');
$inv_id = $uri->getSegment(3);
// Check if editing
if (!empty($inv_id) && isset($inventory_item)) {
    $form_action = get_uri("inventory/update/" . $inv_id);
    $inv_id  = $inventory_item->inv_id ?? '';
    $inv_date = $inventory_item->inv_date ?? '';
    $inv_rec_no = $inventory_item->inv_rec_no ?? '';
    $inv_supplier = $inventory_item->inv_supplier ?? '';
    $inv_name = $inventory_item->inv_name ?? '';
    $inv_model_no = $inventory_item->inv_model_no ?? '';
    $inv_serial_no = $inventory_item->inv_serial_no ?? '';
    $inv_pro_id = $inventory_item->inv_pro_id ?? '';
    $inv_addedby = $inventory_item->inv_addedby ?? '';
} else {
    $form_action = get_uri("inventory/add");
    $inv_id = '';
    $inv_date = '';
    $inv_rec_no = '';
    $inv_supplier = '';
    $inv_name = '';
    $inv_model_no = '';
    $inv_serial_no = '';
    $inv_pro_id = session()->get('url_pro_id');
    $inv_addedby = '';
}

?>

<?= form_open($form_action, array("id" => "inventory-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix">
    <div class="container-fluid">
        <div class="form-group">
            <div class="row">
                <label for="inv_date" class="col-md-3">Date</label>
                <div class="col-md-9">
                    <?= form_input([
                        "id" => "inv_date",
                        "name" => "inv_date",
                        "type" => "date",
                        "value" => esc($inv_date),
                        "class" => "form-control",
                        "placeholder" => app_lang("")
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="inv_rec_no" class="col-md-3">Receipt No.</label>
                <div class="col-md-9">
                    <?= form_input([
                        "id" => "inv_rec_no",
                        "name" => "inv_rec_no",
                        "value" => esc($inv_rec_no),
                        "class" => "form-control",
                        "placeholder" => app_lang("")
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="inv_supplier" class="col-md-3">Supplier</label>
                <div class="col-md-9">
                    <?= form_input([
                        "id" => "inv_supplier",
                        "name" => "inv_supplier",
                        "value" => esc($inv_supplier),
                        "class" => "form-control",
                        "placeholder" => app_lang("")
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="inv_name" class="col-md-3">Name</label>
                <div class="col-md-9">
                    <?= form_input([
                        "id" => "inv_name",
                        "name" => "inv_name",
                        "value" => esc($inv_name),
                        "class" => "form-control",
                        "placeholder" => app_lang("")
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="inv_model_no" class="col-md-3">Model</label>
                <div class="col-md-9">
                    <?= form_input([
                        "id" => "inv_model_no",
                        "name" => "inv_model_no",
                        "value" => esc($inv_model_no),
                        "class" => "form-control",
                        "placeholder" => app_lang("")
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="inv_serial_no" class="col-md-3">Serial No</label>
                <div class="col-md-9">
                    <?= form_input([
                        "id" => "inv_serial_no",
                        "name" => "inv_serial_no",
                        "value" => esc($inv_serial_no),
                        "class" => "form-control",
                        "placeholder" => app_lang("")
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="form-group d-none">
            <div class="row">
                <label for="inv_pro_id" class="col-md-3">pro_id</label>
                <div class="col-md-9">
                    <?= form_input([
                        "id" => "inv_pro_id",
                        "name" => "inv_pro_id",
                        "value" => esc($inv_pro_id),
                        "class" => "form-control",
                        "type" => "hidden",
                        "placeholder" => app_lang("")
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="form-group d-none">
            <div class="row">
                <label for="inv_addedby" class="col-md-3">inv_addedby</label>
                <div class="col-md-9">
                    <?= form_input([
                        "id" => "inv_addedby",
                        "name" => "inv_addedby",
                        "value" => esc($login_user->id),
                        "class" => "form-control",
                        "type" => "hidden",
                        "placeholder" => app_lang("")
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="form-group d-none">
            <div class="row">
                <label for="inv_id" class="col-md-3">inv_id</label>
                <div class="col-md-9">
                    <?= form_input([
                        "id" => "inv_id",
                        "name" => "inv_id",
                        "value" => esc($inv_id),
                        "class" => "form-control",
                        "type" => "hidden",
                        "placeholder" => app_lang("")
                    ]) ?>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal">
        <span data-feather="x" class="icon-16"></span> <?= app_lang('close'); ?>
    </button>
    <button type="submit" class="btn btn-primary">
        <span data-feather="check-circle" class="icon-16"></span> <?= app_lang('save'); ?>
    </button>
</div>

<?= form_close(); ?>
