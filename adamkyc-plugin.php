<?php
/**
 * Plugin Name: Adamkyc API Plugin
 * Description: Wordpress API Plugin for fetting wordpress information use REST API.
 * Version: 1.0
 * Author: Akshay Bhatt
 * License: GPLv2 or later
 * Text Domain www.adamkycplugin.com
 */


// Exit if accessed directly.
if (!defined('ABSPATH')) {

    exit;

}
// require 'vendor/autoload.php';
// require(__DIR__ . '/vendor/autoload.php');

// $client = new MongoDB\Client("mongodb://localhost:27017");


/**
 * Global function for testing purposes
 */
function dd($array = null)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

define('WP_ADAMKYC_PATH', plugin_dir_path(__FILE__));
define('WP_ADAMKYC_URL', plugin_dir_url(__FILE__));
define('WP_ADAMKYC_VERSION', '2.1.2');

require(__DIR__ . '/src/AdamkycPlugin.php');


$plugin = new AdamkycPlugin();