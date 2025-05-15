<?php
inventory_load_css(array("assets/css/inventory_styles.css")); ?>
<div class="inventory-hello-world p15">
<?php
$settings = show_inventory_table(); // Fetch sorted data

// Get current ordering parameters
$order_by = request()->getGet('order_by') ?? 'inv_id';
$order_dir = request()->getGet('order_dir') ?? 'ASC';
?>
<style>

</style>
<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('inventory_name'); ?></h1>
            <div class="title-button-group">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default btn-sm active me-0"  title="<?php echo app_lang('list_view'); ?>"><i data-feather="menu" class="icon-16"></i></button>
                    <?php echo anchor(get_uri("inventory"), "<i data-feather='grid' class='icon-16'></i>", array("class" => "btn btn-default btn-sm")); ?>
                </div>
                <?php
                if ($login_user->is_admin || get_array_value($login_user->permissions, "can_add_or_invite_new_team_members")) {
                    echo modal_anchor(get_uri("inventory/model_form/".(int) session()->get('url_pro_id')), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_inventory_item'), array("class" => "btn btn-default", "title" => app_lang('add_inventory_item')));
                }

                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="team_member-table" class="inventory-table" cellspacing="0" width="100%">
              <tr><td>
              <form method="GET" class="show_table">
                  <input type="text" name="search" placeholder="Search..." value="<?= esc($_GET['search'] ?? '') ?>">
                  <button type="submit">Search</button>
              </form>
            </tr></td>
            <tr><td>
              <table class="display dataTable no-footer">
                  <tr>
                      <th >
                          <a href="<?= base_url('index.php/inventory?search=' . esc($_GET['search'] ?? '') . '&order_by=inv_date&order_dir=' . (($order_by == 'inv_date' && $order_dir == 'ASC') ? 'DESC' : 'ASC')) ?>">
                              Date <?= ($order_by == 'inv_date') ? ($order_dir == 'ASC' ? '↑' : '↓') : '' ?>
                          </a>
                      </th>
                      <th>
                          <a href="<?= base_url('index.php/inventory?search=' . esc($_GET['search'] ?? '') . '&order_by=inv_rec_no&order_dir=' . (($order_by == 'inv_rec_no' && $order_dir == 'ASC') ? 'DESC' : 'ASC')) ?>">
                              Receipt No. <?= ($order_by == 'inv_rec_no') ? ($order_dir == 'ASC' ? '↑' : '↓') : '' ?>
                          </a>
                      </th>
                      <th>
                          <a href="<?= base_url('index.php/inventory?search=' . esc($_GET['search'] ?? '') . '&order_by=inv_supplier&order_dir=' . (($order_by == 'inv_supplier' && $order_dir == 'ASC') ? 'DESC' : 'ASC')) ?>">
                              Supplier <?= ($order_by == 'inv_supplier') ? ($order_dir == 'ASC' ? '↑' : '↓') : '' ?>
                          </a>
                      </th>
                      <th>
                          <a href="<?= base_url('index.php/inventory?search=' . esc($_GET['search'] ?? '') . '&order_by=inv_name&order_dir=' . (($order_by == 'inv_name' && $order_dir == 'ASC') ? 'DESC' : 'ASC')) ?>">
                              Name <?= ($order_by == 'inv_name') ? ($order_dir == 'ASC' ? '↑' : '↓') : '' ?>
                          </a>
                      </th>
                      <th>
                          <a href="<?= base_url('index.php/inventory?search=' . esc($_GET['search'] ?? '') . '&order_by=inv_model_no&order_dir=' . (($order_by == 'inv_model_no' && $order_dir == 'ASC') ? 'DESC' : 'ASC')) ?>">
                              Model No<?= ($order_by == 'inv_model_no') ? ($order_dir == 'ASC' ? '↑' : '↓') : '' ?>
                          </a>
                      </th>
                      <th>
                          <a href="<?= base_url('index.php/inventory?search=' . esc($_GET['search'] ?? '') . '&order_by=inv_serial_no&order_dir=' . (($order_by == 'inv_serial_no' && $order_dir == 'ASC') ? 'DESC' : 'ASC')) ?>">
                              Serial No <?= ($order_by == 'inv_serial_no') ? ($order_dir == 'ASC' ? '↑' : '↓') : '' ?>
                          </a>
                      </th>
                      <th>

                      </th>

                  </tr>
                  <?php foreach ($settings as $setting) { ?>
                  <tr>
                      <td><?= esc($setting->inv_date); ?></td>
                      <td><?= esc($setting->inv_rec_no); ?></td>
                      <td><?= esc($setting->inv_supplier); ?></td>
                      <td><?= esc($setting->inv_name); ?></td>
                      <td><?= esc($setting->inv_model_no); ?></td>
                      <td><?= esc($setting->inv_serial_no); ?></td>


                      <td>
                          <!-- Add Delete Button -->
                         <a href="<?= base_url('index.php/inventory/delete/' . esc($setting->inv_id)); ?>"
                            onclick="return confirm('Are you sure you want to delete this setting?')"><i data-feather='delete' class='icon-18'></i></a>

                          <?php  echo modal_anchor(get_uri('inventory/model_form/'. esc($setting->inv_id)), "<i data-feather='edit' class='icon-16'></i> " , array("class" => "btn btn-default", "data-post-id" => esc($setting->inv_id), "title" => app_lang('edit_inventory'))); ?>

                  </td>
                  </tr>
                  <?php } ?>
                </td></tr>
              </table>
              </div>
            </table>
        </div>
    </div>
</div>

<!-- Inline JavaScript code -->
<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var openModalBtn = document.getElementById("openModalBtn");

// Get the <span> element that closes the modal
var closeBtn = document.getElementsByClassName("close-btn")[0];

// When the user clicks the button, open the modal
openModalBtn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
closeBtn.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
