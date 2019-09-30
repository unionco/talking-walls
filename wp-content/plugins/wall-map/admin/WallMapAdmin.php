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

class WallMapAdmin
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
     * Undocumented variable
     *
     * @var array
     */
    private $templates = [
        'template-map.php' => 'Wall Map'
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    private $postTypes = [
        'MuralPostType.php' => MuralPostType::class
    ];

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Dynamically register post types
     */
    public function registerPostTypes()
    {
        foreach ($this->postTypes as $fileName => $postType) {
            require_once plugin_dir_path(dirname(__FILE__)) . "admin/{$fileName}";

            $class = new $postType($this->plugin_name, $this->version);
            $class->register();
            $class->taxonomies();
        }
    }

    /**
     *
     */
    public function registerPageTemplate($posts_templates)
    {
        return array_merge($posts_templates, $this->templates);
    }

    /**
     *
     */
    public function registerProjectTemplates($atts)
    {
        $cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());
        // Retrieve the cache list.
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if (empty($templates)) {
            $templates = array();
        }
        // New cache, therefore remove the old one
        wp_cache_delete($cache_key, 'themes');
        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge($templates, $this->templates);
        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add($cache_key, $templates, 'themes', 1800);
        return $atts;
    }

    /**
     * Checks if the template is assigned to the page
     */
    public function viewProjectTemplate($template)
    {
        // Return the search template if we're searching (instead of the template for the first result)
        if (is_search()) {
            return $template;
        }

        // Get global post
        global $post;
        // Return template if post is empty
        if (!$post) {
            return $template;
        }

        // Return default template if we don't have a custom one defined
        if (!isset($this->templates[get_post_meta(
            $post->ID,
            '_wp_page_template',
            true
        )])) {
            return $template;
        }

        // Allows filtering of file path
        $filepath = apply_filters('page_templater_plugin_dir_path', plugin_dir_path(__FILE__) . '../templates/');

        $file =  $filepath . get_post_meta(
            $post->ID,
            '_wp_page_template',
            true
        );

        // Just to be safe, we check if the file exist first
        if (file_exists($file)) {
            return $file;
        } else {
            echo $file;
        }

        // Return template
        return $template;
    }

    public function settings_menu()
    {
        // add_submenu_page(
        //     'edit.php?post_type=trip-it',
        //     __('Settings', 'trips'),
        //     __('Settings', 'trips'),
        //     'manage_options',
        //     'tripit-settings',
        //     [$this, 'settings_page']
        // );
    }

    public function register_settings()
    {
        // register_setting('tripit-settings-group', 'avail_override');
        // register_setting('tripit-settings-group', 'avail_url');
        // register_setting('tripit-settings-group', 'avail_text');
    }

    public function settings_page()
    {
        echo "Wallmap settings";
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        // wp_enqueue_style(
        //     $this->plugin_name,
        //     plugin_dir_url(__FILE__) . 'css/inventory-manager-admin.css',
        //     array(),
        //     $this->version,
        //     'all'
        // );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        // wp_enqueue_script(
        //     $this->plugin_name . ' _admin',
        //     plugin_dir_url(__FILE__) . 'js/trip-it.js',
        //     array('jquery'),
        //     $this->version,
        //     false
        // );
    }
}
