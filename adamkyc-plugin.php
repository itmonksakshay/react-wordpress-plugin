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


define('WP_ADAMKYC_PATH', plugin_dir_path(__FILE__));
define('WP_ADAMKYC_URL', plugin_dir_url(__FILE__));
define('WP_ADAMKYC_VERSION', '2.1.2');
require(__DIR__ . '/src/AdamkycPlugin.php');


register_activation_hook(__FILE__, '');
function plugin_activation_hook()
{
    die('Hooks');

}

$plugin = new AdamkycPlugin();