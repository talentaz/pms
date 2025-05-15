<?php

/**
 * get the defined config value by a key
 * @param string $key
 * @return config value
 */
if (!function_exists('get_inventory_setting')) {

    function get_inventory_setting($key = "") {
        $config = new Inventory\Config\Inventory();

        $inv_date = get_array_value($config->app_settings_array, $key);
        if ($inv_date !== NULL) {
            return $inv_date;
        } else {
            return "";
        }
    }

}

if (!function_exists('get_inventory')) {

    function show_table() {
    $config = new Inventory\Config\Inventory();
    $data['settings'] = $config->settings;
    //$string = implode(', ', $data['settings']);
    return $data['settings'];
    }

    function show_inventory_table() {
    $db = \Config\Database::connect();
    $builder = $db->table('inventory_settings');

    // Apply WHERE condition for inv_pro_id = session value
    $builder->where('inv_pro_id', session()->get('url_pro_id'));

    // Get search input from GET request
    $search_query = request()->getGet('search');

    // If search query exists, apply multiple LIKE conditions
    if (!empty($search_query)) {
        $builder->groupStart()
            ->like('inv_id', $search_query)
            ->orLike('inv_date', $search_query)
            ->orLike('inv_rec_no', $search_query)
            ->orLike('inv_supplier', $search_query)
            ->orLike('inv_name', $search_query)
            ->orLike('inv_model_no', $search_query)
            ->orLike('inv_serial_no', $search_query)
            ->groupEnd();
    }

    // Apply ordering
    $order_by = request()->getGet('order_by') ?? 'inv_id';
    $order_dir = request()->getGet('order_dir') ?? 'ASC';
    $builder->orderBy($order_by, $order_dir);

    // Execute the query and get the result
    $query = $builder->get();
    return $query->getResult();
    }

}


/**
 * link the css files
 *
 * @param array $array
 * @return print css links
 */
if (!function_exists('inventory_load_css')) {

    function inventory_load_css(array $array) {
        $version = get_setting("app_version");

        foreach ($array as $uri) {
            echo "<link rel='stylesheet' type='text/css' href='" . base_url(PLUGIN_URL_PATH . "inventory/$uri") . "?v=$version' />";
        }
    }

}

if (!function_exists('inventory_load_view')) {

    function inventory_load_view() {
        $version = get_setting("app_version");
        //echo "$version";
        return $version;
    }

}

if (!function_exists('inventory_get_source_url')) {

    function inventory_get_source_url($inventory_file = "") {
        if (!$inventory_file) {
            return "";
        }

        try {
            $file = unserialize($inventory_file);
            if (is_array($file)) {
                return get_source_url_of_file($file, get_inventory_setting("inventory_file_path"), "thumbnail", false, false, true);
            }
        } catch (\Exception $ex) {

        }
    }

}
