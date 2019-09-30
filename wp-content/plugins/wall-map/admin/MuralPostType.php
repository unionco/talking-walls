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

class MuralPostType
{
    /**
     * @var key string
     */
    const SINGULAR = 'mural';

    /**
     * @var key string
     */
    const PLURAL = 'murals';

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
    private $categories = [
        'Year',
        'Type'
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
     *
     */
    public function register()
    {
        $args = [
            'labels' => [
                'name'                  =>  __(ucfirst(static::SINGULAR)),
                'singular_name'         =>  __(ucfirst(static::SINGULAR)),
                'add_new'               =>  __("Add New"),
                'add_new_item'          =>  __("Add New " . ucfirst(static::SINGULAR)),
                'edit_item'             =>  __("Edit " . ucfirst(static::SINGULAR)),
                'new_item'              =>  __("New " . ucfirst(static::SINGULAR)),
                'all_items'             =>  __("All " . ucfirst(static::PLURAL)),
                'view_item'             =>  __("View " . ucfirst(static::PLURAL)),
                'search_items'          =>  __("Search " . ucfirst(static::PLURAL)),
                'not_found'             =>  __("No " . ucfirst(static::PLURAL . " found")),
                'not_found_in_trash'    =>  __("No " . ucfirst(static::PLURAL . " found in Trash")),
                'parent_item_colon'     =>  __("Parent"),
                'menu_name'             =>  __(ucfirst(static::PLURAL)),
            ],
            'public'                =>  true,
            'publicly_queryable'    =>  true,
            'show_ui'               =>  true,
            'show_in_menu'          =>  true,
            'query_var'             =>  true,
            'exclude_from_search'   =>  true,
            'rewrite'               =>  ['slug' => esc_attr(static::PLURAL)],
            'capability_type'       =>  'post',
            'has_archive'           =>  true,
            'hierarchical'          =>  true,
            'menu_position'         =>  25,
            'supports'              =>  ['title', 'custom-fields', 'thumbnail']
        ];

        register_post_type(static::PLURAL, $args);

        return true;
    }

    /**
     *
     */
    public function taxonomies()
    {
        foreach ($this->categories as $tax) {
            $taxName = static::PLURAL . "-" . sanitize_title($tax);

            register_taxonomy(
                $taxName,
                static::PLURAL,
                [
                    'hierarchical'  => true,
                    'labels'        => [
                        'name'              => __($tax),
                        'singular_name'     => __($tax),
                        'search_items'      => __('Search'),
                        'all_items'         => __('All'),
                        'parent_item'       => __('Parent'),
                        'parent_item_colon' => __('Parent:'),
                        'edit_item'         => __('Edit'),
                        'update_item'       => __('Update'),
                        'add_new_item'      => __('Add New'),
                        'new_item_name'     => __('New'),
                        'menu_name'         => __($tax)
                    ],
                    'show_ui'           => true,
                    'query_var'         => true,
                    'rewrite'           => false
                ]
            );

            if (function_exists('add_term_ordering_support')) {
                add_term_ordering_support($taxName);
            }
        }
    }
}
