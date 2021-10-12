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

class WallMapPublic
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register Shortcode
     *
     * @since    1.0.0
     **/
    public function register_shortcode()
    {
        // add_shortcode('trip-it', array($this, 'trip_it_shortcode'));
        // add_shortcode('trip-it-start', array($this, 'trip_it_shortcode_start'));
        // add_shortcode('trip-it-group-dates', array($this, 'trip_it_shortcode_group_dates'));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /* Extra Stylesheet if needed */
        wp_enqueue_style(
            $this->plugin_name . "_custom",
            plugin_dir_url(__FILE__) . 'css/murals.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            $this->plugin_name . "_vendor",
            'https://maps.googleapis.com/maps/api/js?key=AIzaSyDBBRlHShwf-OD5MEBfy7RmgaCfdBUpCug',
            $this->version,
            true
        );

        wp_enqueue_script(
            $this->plugin_name . "_cluster",
            'https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js',
            [],
            false
        );

        wp_enqueue_script(
            $this->plugin_name . "_app",
            plugin_dir_url(__FILE__) . 'js/murals.js',
            [$this->plugin_name . "_vendor"],
            $this->version,
            true
        );
    }

    public function jsOptions()
    {
        // return [
        //     'ajaxUrl' => admin_url('admin-ajax.php'),
        //     'nounce' => wp_create_nonce('tripit_n'),
        //     'shortcode' => $this->short_code_type,
        //     'query' => $this->short_code_query,
        //     'filter' => isset($_GET['filter']) ? $_GET['filter'] : $this->short_code_filter,
        //     'uniq' => $this->short_code_id,
        //     'preventScroll' => $this->prevent_scroll,
        //     'limitRows' => $this->limit_rows,
        //     'override' => [
        //         'enabled' => get_option('avail_override'),
        //         'text' => get_option('avail_text'),
        //         'url' => get_option('avail_url')
        //     ]
        // ];
    }

    public function js($options = [])
    {
        // wp_localize_script(
        //     $this->plugin_name . "_app",
        //     'tripitParams',
        //     $options
        // );
    }
}
