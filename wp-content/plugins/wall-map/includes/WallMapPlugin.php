<?php

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Wall Maps
 * @author     Union <dev@union.co>
 */

class WallMapPlugin
{

    protected $loader;
    protected $plugin_name;
    protected $version;

    function __construct()
    {
        $this->plugin_name = 'wall_maps';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/Loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/WallMapAdmin.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/WallMapPublic.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/WallMapAjax.php';

        $this->loader = new Loader();
    }

    private function define_admin_hooks()
    {
        $admin = new WallMapAdmin($this->get_plugin_name(), $this->get_version());

        // Actions
        $this->loader->add_action('init', $admin, 'registerPostTypes');

        // Filters
        $this->loader->add_filter('theme_page_templates', $admin, 'registerPageTemplate');
        $this->loader->add_filter('wp_insert_post_data', $admin, 'registerProjectTemplates');
        $this->loader->add_filter('template_include', $admin, 'viewProjectTemplate');

        // $this->loader->add_action('init', $plugin_admin, 'register_post_type');
        // $this->loader->add_action('init', $plugin_admin, 'register_taxonomies');

        // $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        // $this->loader->add_filter('manage_trip-it_posts_columns', $plugin_admin, 'manage_post_columns', 2);
        // $this->loader->add_action('manage_trip-it_posts_custom_column', $plugin_admin, 'manage_post_column', 5, 2);
        // $this->loader->add_filter('manage_edit-trip-it_sortable_columns', $plugin_admin, 'my_sortable_trips_column');

        // $this->loader->add_action('pre_get_posts', $plugin_admin, 'orderby');
        // $this->loader->add_action('restrict_manage_posts', $plugin_admin, 'add_taxonomy_filters');
        // // $this->loader->add_action('save_post_trip-it', $plugin_admin, 'add_filtered_meta', 99 );
        // $this->loader->add_action('save_post', $plugin_admin, 'add_filtered_meta');

        // $this->loader->add_action('admin_menu', $plugin_admin, 'settings_menu');
        // $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
        // $this->loader->add_action('admin_bar_menu', $plugin_admin, 'admin_bar_menu', 100);
    }

    private function define_public_hooks()
    {
        $plugin_public = new WallMapPublic($this->get_plugin_name(), $this->get_version());
        $ajax_handler = new WallMapAjax($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        // $this->loader->add_action('wp_ajax_load-trips-ajax', $ajax_handler, 'load_trips');
        // $this->loader->add_action('wp_ajax_nopriv_load-trips-ajax', $ajax_handler, 'load_trips');

        // $this->loader->add_action('init', $plugin_public, 'register_shortcode');
    }

    public function run()
    {
        $this->loader->run();
    }

    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    public function get_loader()
    {
        return $this->loader;
    }

    public function get_version()
    {
        return $this->version;
    }
}
