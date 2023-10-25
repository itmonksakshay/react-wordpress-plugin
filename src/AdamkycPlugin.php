<?php

require(__DIR__ . '/Mongo.php');

if (!class_exists('AdamkycPlugin')) {
    class AdamkycPlugin
    {

        /**
         * Plugin instance.
         *
         * @see get_instance()
         * @type object
         */
        protected static $instance = NULL;

        /**
         * URL to this plugin's directory.
         *
         * @type string
         */
        public $plugin_url = '';

        /**
         * Path to this plugin's directory.
         *
         * @type string
         */
        public $plugin_path = '';

        /**
         * Namespace for api
         */
        public $routeNamespace = 'api-adamkyc/v1';
        public $connection = null;


        public function __construct()
        {

            $this->plugin_url = plugins_url('/', __FILE__);
            $this->plugin_path = plugin_dir_path(__FILE__);
            //shows the menu item in sidebar
            add_action('admin_menu', array($this, 'load_menus'));
            // registers the api routes
            add_action('rest_api_init', [$this, 'load_api_routes']);
            add_action('rest_api_init', [$this, 'db_routes']);
            // function executes when shortcode is used
            add_shortcode('adamkyc_load_screen', [$this, 'loadScreen']);

        }


        public function loadScreen()
        {
            wp_enqueue_script('adamkyc-plugin', plugin_dir_url(__FILE__) . 'assets/js/script.js', "", "", true);
            wp_enqueue_style('adamkyc-plugin', plugin_dir_url(__FILE__) . 'assets/css/style.css');

            return "<div id=\"root\"></div>";
        }


        public function load_api_routes()
        {

            // register routes
            register_rest_route(
                $this->routeNamespace,
                '/testing',
                array(

                    'methods' => 'GET',
                    'callback' => array($this, 'callbackPosts'),
                )
            );

        }

        public function db_routes()
        {
            register_rest_route(
                $this->routeNamespace,
                '/connect',
                array(

                    'methods' => 'GET',
                    'callback' => array($this, 'connect'),
                )
            );
            register_rest_route(
                $this->routeNamespace,
                '/disconnect',
                array(

                    'methods' => 'GET',
                    'callback' => array($this, 'disconnect'),
                )
            );
        }
        public function connect()
        {
            $connection = new Mongo();
            return $connection->start();
        }


        public function disconnect()
        {
            $connection = new Mongo();
            if ($connection) {

                return $connection->close();
            }

            return false;
        }

        /**
         * 
         */
        public function callbackPosts()
        {
            global $current_user, $wp_posts;
            wp_get_current_user();
            $return = array(
                'user' => $current_user,
                'ID' => 1
            );

            return wp_send_json($return);
        }




        /**
         * load languages
         */

        public function load_languages()
        {
        }




        /**
         * Load javascript files 
         */
        public function load_scripts()
        {
            $screen = get_current_screen();


            wp_enqueue_script('adamkyc-plugin', plugin_dir_url(__FILE__) . 'assets/js/adminScript.js', "", "", true);


        }
        /**
         * Load css styles
         */
        public function load_styles()
        {
            wp_enqueue_style('adamkyc-plugin', plugin_dir_url(__FILE__) . 'assets/css/adminStyle.css');


        }

        /**
         * Load admin menu
         */

        public function load_menus()
        {
            $hook = add_menu_page(
                'Adamkyc Plugin',
                'Adamkyc Plugin',
                'edit_posts',
                'adamkyc-plugin',
                array($this, 'page'),
                //function
                plugin_dir_url(__FILE__) . 'assets/images/plugin-icon.png',
            );

            add_action("admin_print_styles-$hook", array($this, 'load_styles'));
            add_action("admin_print_scripts-$hook", array($this, 'load_scripts'));



        }

        /**
         * Html for page
         */
        public function page()
        {
            echo "<div id=\"root\"></div>";
        }
    }
}