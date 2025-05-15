<?php

namespace Inventory\Config;

use CodeIgniter\Events\Events;

Events::on('pre_system', function () {
    helper("inventory_general");
});