<?php

require(__DIR__ . '/DB.php');

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
        /**
         * Database
         */
        public $db = null;


        public function __construct()
        {

            $this->db = new DB();
            $this->plugin_url = plugins_url('/', __FILE__);
            $this->plugin_path = plugin_dir_path(__FILE__);
            //shows the menu item in sidebar
            add_action('admin_menu', array($this, 'load_menus'));
            // registers the api routes
            add_action('rest_api_init', [$this, 'load_api_routes']);
            add_action('rest_api_init', [$this, 'load_db_routes']);
            // function executes when shortcode is used
            add_shortcode('adamkyc_load_screen', [$this, 'loadScreen']);

            wp_enqueue_script('adamkyc-plugin-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', "", "", true);
            wp_enqueue_style('adamkyc-plugin-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');

        }
        public function screenStyle()
        {
            wp_enqueue_script('adamkyc-plugin-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', "", "", true);
            wp_enqueue_style('adamkyc-plugin-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
        }




        public function loadScreen()
        {
            $this->screenStyle();
            return "<div id=\"root\"></div>";
        }


        public function load_api_routes()
        {

            // register routes
            register_rest_route(
                $this->routeNamespace,
                '/me',
                array(

                    'methods' => 'GET',
                    'callback' => array($this, 'me'),
                )
            );



        }

        public function load_db_routes()
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

        public function me()
        {
            return $this->db->me();

        }
        /**
         * Start connection
         */
        public function connect(WP_REST_Request $request)
        {
            $this->saveOptions($request->get_params());
            return $this->db->start($request->get_params());
        }

        public function saveOptions($params = [])
        {
            //update username
            if (isset($params['username']) && $params['username'] !== '') {
                update_option('mongo_username', $params['username']);
            }
            //update password
            if (isset($params['password']) && $params['password'] !== '') {
                update_option('mongo_password', $params['password']);
            }

            //update host
            if (isset($params['host']) && $params['host'] !== '') {
                update_option('mongo_host', $params['host']);
            }
            //update database
            if (isset($params['database']) && $params['database'] !== '') {
                update_option('mongo_database', $params['database']);
            }

        }


        public function disconnect()
        {
            if ($this->db) {
                return $this->db->close();
            }
            return false;
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