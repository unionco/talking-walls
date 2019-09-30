<?php

/*
 * Plugin Name: Divi Ultimate Plugin
 * Plugin URI:  https://diviultimate.com
 * Description: Divi plugin that solves the styling for Divi header, footer, widget & blog post.
 * Author:      Divi Ultimate
 * Version:     5.0.1
 * Author URI:  https://diviultimate.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; 
define('DIVI_ULTIMATE_PLUGIN_VERSION', '5.0.1.1');

add_action('plugins_loaded', 'divi_ultimate_plugin_init');

function divi_ultimate_plugin_init() {
	$divi_ultimate_plugin_global_styling_settings = get_option( 'divi_ultimate_plugin_global_styling_settings', 'none' );

	add_action( 'wp_enqueue_scripts', 'divi_ultimate_plugin_main_css', 20 ); 
	add_action('get_header', 'divi_ultimate_plugin_ob_start', 1);
	add_action('wp_footer', 'divi_ultimate_plugin_custom_header_footer');
	add_filter('body_class', 'divi_ultimate_plugin_add_body_class');
	add_action('wp_head', 'divi_ultimate_plugin_css_edit');
	add_filter('manage_et_pb_layout_posts_columns', 'divi_ultimate_plugin_columns_head_shortcode', 10);
	add_action('manage_et_pb_layout_posts_custom_column', 'divi_ultimate_plugin_content_shortcode', 10, 2);
	add_shortcode('du_year', 'divi_ultimate_plugin_current_year');
	add_shortcode('du_shortcode', 'divi_ultimate_plugin_shortcode');
	add_action('add_meta_boxes', 'divi_ultimate_plugin_shortcode_meta_box');
	// add_action('add_meta_boxes', 'divi_ultimate_plugin_global_styling_meta_box');
	// add_action('add_meta_boxes', 'divi_ultimate_plugin_custom_header_navigation_meta_box');
	// add_action("save_post", "divi_ultimate_plugin_meta_box_save", 10, 3);
	add_action('customize_register', 'divi_ultimate_plugin_customizer_settings', 999);
	// add_filter('single_template', 'divi_ultimate_plugin_custom_blog');
	add_filter('single_template', 'divi_ultimate_plugin_blog_post');
	add_action( 'customize_controls_enqueue_scripts', 'divi_ultimate_plugin_customize_controls_js_css' );
	if ($divi_ultimate_plugin_global_styling_settings != 'none') {
		add_action('wp_enqueue_scripts', 'divi_ultimate_plugin_theme_css', 20);
	}
	if ($divi_ultimate_plugin_global_styling_settings == 'none') {
		add_action( 'admin_notices', 'divi_ultimate_plugin_admin_notice' );
	}
	add_filter( 'wp_nav_menu_items', 'divi_ultimate_plugin_menu_add_search_cart', 10, 2 );
	add_action( 'customize_preview_init', 'divi_ultimate_plugin_customize_preview_js' );
}

// -------------- Load main CSS and JS Start ----------------
function divi_ultimate_plugin_main_css() {
	$divi_ultimate_plugin_blog_post_enable = get_option( 'divi_ultimate_plugin_blog_post_enable' );
	
	wp_enqueue_style('divi-ultimate-plugin-main-css', plugin_dir_url( __FILE__ ) . 'css/main.css', array(), DIVI_ULTIMATE_PLUGIN_VERSION);
	wp_enqueue_style( 'divi-ultimate-plugin-restaurant-font', 'https://fonts.googleapis.com/css?family=Herr+Von+Muellerhoff', false );
	wp_enqueue_script( 'divi-ultimate-plugin-main-js', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery' ), DIVI_ULTIMATE_PLUGIN_VERSION, true );
	
	if ($divi_ultimate_plugin_blog_post_enable) {
		wp_enqueue_style('divi-ultimate-plugin-blog-css', plugin_dir_url( __FILE__ ) . 'blog/du-blog-1.css', array(), DIVI_ULTIMATE_PLUGIN_VERSION);
	}
} 
// -------------- Load main CSS and JS End ----------------

// -------------- Admin Notice Start ----------------
function divi_ultimate_plugin_admin_notice() {
	$customize_url = admin_url( 'customize.php?autofocus[panel]=divi_ultimate_plugin_customizer_options' );
	?>
	<div class="notice notice-info">
		<h2>Welcome to Divi Ultimate plugin</h2><p>All our settings can be found in the Theme Customizer. You can set your desired global styling, custom header, 
			footer, widget &amp; blog post design.<br>Once you set your desired theme in the global styling setting, this notice will be automatically dismissed.<br>
				<?php echo '<a href="' . $customize_url . '" class="button-primary" style="margin-top: 16px;margin-bottom: 12px;" target="_blank">Set Up Now</a>'; ?></p>
	</div>
	<?php
}
// -------------- Admin Notice End ----------------

// -------------- Current Year Shortcode Start ----------------
function divi_ultimate_plugin_current_year() {
	return date('Y');
}
// -------------- Current Year Shortcode Start ----------------

// -------------- Divi Library Shortcode Start ----------------
// Shortcode to show the module
function divi_ultimate_plugin_shortcode($moduleid) {
	extract(shortcode_atts(array('id' =>'*'),$moduleid)); 
	return do_shortcode('[et_pb_section global_module="'.$id.'"][/et_pb_section]');
}

// Two functions to add new shortcode columns to Divi Library page
function divi_ultimate_plugin_columns_head_shortcode($defaults) {
	$defaults['du_shortcode'] = 'Shortcode';
	return $defaults;
}
function divi_ultimate_plugin_content_shortcode($column_name, $post_ID) {
	if ($column_name == 'du_shortcode') {
		echo '[du_shortcode id="'. $post_ID .'"]';
	}
}

// Custom meta box in Divi Library item page to show the shortcode
function divi_ultimate_plugin_shortcode_meta_box_markup() {
	$id = get_the_ID();
	echo  '<p>Copy the below shortcode & paste it anywhere you want it to appear:</p><p>[du_shortcode id="'. $id .'"]</p>';
}
function divi_ultimate_plugin_shortcode_meta_box() {
	add_meta_box('du_shortcode', 'Shortcode', 'divi_ultimate_plugin_shortcode_meta_box_markup', 'et_pb_layout', 'side', 'low', null);
}
// -------------- Divi Library Shortcode End ----------------

// -------------- Meta Box Global Styling Start ----------------
function divi_ultimate_plugin_global_styling_meta_box_markup() {
	$overrides_global = get_post_meta(get_the_ID(), 'divi_ultimate_plugin_global_styling_overrides', true);
	if (empty($overrides_global)) {
		$overrides_global = '0';
	}
	?>
	<p><select style="max-width: 175px;" name="divi_ultimate_plugin_global_styling_overrides">
	<option value="0" <?php selected( $overrides_global, '0' ); ?>>Default</option>
	<option value="none" <?php selected( $overrides_global, 'none' ); ?>>None</option>
	<option value="free-agency" <?php selected( $overrides_global, 'free-agency' ); ?>>Agency</option>
	<option value="free-architect" <?php selected( $overrides_global, 'free-architect' ); ?>>Architect</option>
	<option value="free-bands" <?php selected( $overrides_global, 'free-bands' ); ?>>Bands</option>
	<option value="free-barber" <?php selected( $overrides_global, 'free-barber' ); ?>>Barber</option>
	<option value="free-business" <?php selected( $overrides_global, 'free-business' ); ?>>Business</option>
	<option value="free-construction" <?php selected( $overrides_global, 'free-construction' ); ?>>Construction</option>
	<option value="free-consulting" <?php selected( $overrides_global, 'free-consulting' ); ?>>Consulting</option>
	<option value="free-divi-ultimate" <?php selected( $overrides_global, 'free-divi-ultimate' ); ?>>Divi Ultimate</option>
	<option value="free-event" <?php selected( $overrides_global, 'free-event' ); ?>>Event</option>
	<option value="free-freelancer" <?php selected( $overrides_global, 'free-freelancer' ); ?>>Freelancer</option>
	<option value="free-gym" <?php selected( $overrides_global, 'free-gym' ); ?>>Gym & Fitness</option>
	<option value="free-hotel" <?php selected( $overrides_global, 'free-hotel' ); ?>>Hotel</option>
	<option value="free-interior" <?php selected( $overrides_global, 'free-interior' ); ?>>Interior Design</option>
	<option value="free-lawyer" <?php selected( $overrides_global, 'free-lawyer' ); ?>>Lawyer</option>
	<option value="free-magazine" <?php selected( $overrides_global, 'free-magazine' ); ?>>Magazine</option>
	<option value="free-medical" <?php selected( $overrides_global, 'free-medical' ); ?>>Medical</option>
	<option value="free-mobile-app" <?php selected( $overrides_global, 'free-mobile-app' ); ?>>Mobile App</option>
	<option value="free-newspaper" <?php selected( $overrides_global, 'free-newspaper' ); ?>>Newspaper</option>
	<option value="free-photography" <?php selected( $overrides_global, 'free-photography' ); ?>>Photography</option>
	<option value="free-restaurant" <?php selected( $overrides_global, 'free-restaurant' ); ?>>Restaurant</option>
	<option value="free-shop" <?php selected( $overrides_global, 'free-shop' ); ?>>Shop</option>
	<option value="free-wedding" <?php selected( $overrides_global, 'free-wedding' ); ?>>Wedding</option>
	</select></p>
	<?php
}
function divi_ultimate_plugin_global_styling_meta_box() {
	add_meta_box('du_global_styling', 'Global Styling Theme Overrides', 'divi_ultimate_plugin_global_styling_meta_box_markup', array( 'post', 'page' ), 'side', 'low', null);
}
// -------------- Meta Box Global Styling End ----------------

// -------------- Meta Box Custom Header & Navigation Start ----------------
function divi_ultimate_plugin_custom_header_navigation_meta_box_markup() {
	$hide_custom_header_navigation = get_post_meta(get_the_ID(), 'divi_ultimate_plugin_hide_custom_header_navigation', true);
	$show_default_divi_header = get_post_meta(get_the_ID(), 'divi_ultimate_plugin_show_default_divi_header', true);
	if (empty($hide_custom_header_navigation)) {
		$hide_custom_header_navigation = '0';
	}
	if (empty($show_default_divi_header)) {
		$show_default_divi_header = '0';
	}
	?>
	<p>
		<input type="checkbox" name="divi_ultimate_plugin_hide_custom_header_navigation" id="divi_ultimate_plugin_hide_custom_header_navigation" <?php if ( $hide_custom_header_navigation ) echo 'checked="checked"'; ?> />
		<label for="divi_ultimate_plugin_hide_custom_header_navigation">Hide custom header & navigation</label>
	</p>
	<p>
		<input type="checkbox" name="divi_ultimate_plugin_show_default_divi_header" id="divi_ultimate_plugin_show_default_divi_header" <?php if ( $show_default_divi_header ) echo 'checked="checked"'; ?> />
		<label for="divi_ultimate_plugin_show_default_divi_header">Show default Divi header & navigation</label>
	</p>
	<?php
}
function divi_ultimate_plugin_custom_header_navigation_meta_box() {
	add_meta_box('du_custom_header_navigation', 'Custom Header & Navigation Settings', 'divi_ultimate_plugin_custom_header_navigation_meta_box_markup', array( 'post', 'page' ), 'side', 'low', null);
}
// -------------- Meta Box Custom Header & Navigation End ----------------

// -------------- Meta Box Save Start ----------------
function divi_ultimate_plugin_meta_box_save($post_id, $post, $update) {
	if (!current_user_can("edit_post", $post_id)) return;
	if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) return;
	$post_type = get_post_type($post_id);
	if ( !in_array($post_type, array('post', 'page')) ) return;
	
	if (isset($_POST['divi_ultimate_plugin_global_styling_overrides'])) {
		update_post_meta($post_id, 'divi_ultimate_plugin_global_styling_overrides', $_POST['divi_ultimate_plugin_global_styling_overrides']);
	}   
	if (isset($_POST['divi_ultimate_plugin_hide_custom_header_navigation'])) {
		update_post_meta($post_id, 'divi_ultimate_plugin_hide_custom_header_navigation', '1');
	} else {
		update_post_meta($post_id, 'divi_ultimate_plugin_hide_custom_header_navigation', '0');		
	}
	if (isset($_POST['divi_ultimate_plugin_show_default_divi_header'])) {
		update_post_meta($post_id, 'divi_ultimate_plugin_show_default_divi_header', '1');
	} else {
		update_post_meta($post_id, 'divi_ultimate_plugin_show_default_divi_header', '0');
	}
}
// -------------- Meta Box Save End ----------------

// -------------- Add Custom Header & Footer Start ----------------
function divi_ultimate_plugin_ob_start() {
	ob_start();
}

function divi_ultimate_plugin_custom_header_footer () {
	$du_body_content = ob_get_clean();
	$divi_ultimate_plugin_header_styling_settings = get_option( 'divi_ultimate_plugin_header_styling_settings', 'none' );
	$divi_ultimate_plugin_footer_styling_settings = get_option( 'divi_ultimate_plugin_footer_styling_settings', 'none' );
	$divi_ultimate_plugin_footer_reveal = get_option( 'divi_ultimate_plugin_footer_reveal');
	$du_vb_1 = strpos($du_body_content, ' et-fb ');
	$du_vb_2 = strpos($du_body_content, ' et-fb"');
	if ($du_vb_1) {
		$du_vb_1 = TRUE;
	} else {
		$du_vb_1 = FALSE;
	}
	if ($du_vb_2) {
		$du_vb_2 = TRUE;
	} else {
		$du_vb_2 = FALSE;
	}
	
	// Add custom header
	if ($divi_ultimate_plugin_header_styling_settings != 'none' && !($du_vb_1 || $du_vb_2) && $du_header_position = strpos($du_body_content, '</header>') ) {
		$du_custom_header = do_shortcode('[et_pb_section global_module="' . $divi_ultimate_plugin_header_styling_settings . '"][/et_pb_section]');
		$du_body_content = substr_replace($du_body_content, '<div class="free-du-plugin-header">' . $du_custom_header . '</div>', ($du_header_position + 9), 0);
	}
	// Add custom footer
	if ($divi_ultimate_plugin_footer_styling_settings != 'none' && !($du_vb_1 || $du_vb_2) && $du_footer_position = strpos($du_body_content, '<footer') ) {
		$du_custom_footer = do_shortcode('[et_pb_section global_module="' . $divi_ultimate_plugin_footer_styling_settings . '"][/et_pb_section]');
		$du_body_content = substr_replace($du_body_content, '<div class="free-du-plugin-footer">' . $du_custom_footer . '</div>', ($du_footer_position), 0);
	}
	echo $du_body_content;
	
	if ($divi_ultimate_plugin_header_styling_settings != 'none' && !($du_vb_1 || $du_vb_2) && $du_header_position = strpos($du_body_content, '</header>') ) {
		// Add sticky menu
		echo '<script src="' . plugin_dir_url( __FILE__ ) . 'js/jquery.sticky.min.js?ver=' . DIVI_ULTIMATE_PLUGIN_VERSION . '"></script>';
		if ( is_admin_bar_showing() ) {
			$du_sticky_offset = '32';
		} else {
			$du_sticky_offset = '0';
		}
		?>
		<script>
		  jQuery(function($) {
			$(".free-sticky").sticky({topSpacing:<?php echo $du_sticky_offset; ?>});
		  });
		</script>
	<?php
	}
	
	if ($divi_ultimate_plugin_footer_styling_settings != 'none' && $divi_ultimate_plugin_footer_reveal && !($du_vb_1 || $du_vb_2) && $du_footer_position = strpos($du_body_content, '<footer') ) {
		// Add footer reveal
		echo '<script src="' . plugin_dir_url( __FILE__ ) . 'js/jquery.footer-reveal.min.js?ver=' . DIVI_ULTIMATE_PLUGIN_VERSION . '"></script>';
		?>
		<script>
		  jQuery(function($) {
			$('.free-du-plugin-footer').footerReveal();
		  });
		</script>
	<?php
	}
	
}
// -------------- Add Custom Header & Footer End ----------------

// -------------- Customizer Settings Start ----------------
function divi_ultimate_plugin_customizer_settings($wp_customize) {
	
	// Add new panel
	$wp_customize->add_panel( 'divi_ultimate_plugin_customizer_options', array(
		'priority'       => 0,
		'capability'     => 'edit_theme_options',
		'title'          => 'Divi Ultimate',
		'description'    => 'Custom styling for your Divi header, footer & widget.',
	));
	
	$wp_customize->add_panel( 'divi_ultimate_plugin_blog_post_customizer_options', array(
		'priority'       => 0,
		'capability'     => 'edit_theme_options',
		'title'          => 'Divi Ultimate Blog Post',
		'description'    => 'Custom styling for your blog post.',
	));

	// Add new sections
	$wp_customize->add_section('divi_ultimate_plugin_global_styling', array(
		'priority' => 5,
		'title' => 'Global Styling',
		'panel'  => 'divi_ultimate_plugin_customizer_options',
	)); 
	
	$wp_customize->add_section('divi_ultimate_plugin_header_styling', array(
		'priority' => 10,
		'title' => 'Header & Navigation Styling',
		'panel'  => 'divi_ultimate_plugin_customizer_options',
	)); 

	$wp_customize->add_section('divi_ultimate_plugin_footer_styling', array(
		'priority' => 20,
		'title' => 'Footer Styling',
		'panel'  => 'divi_ultimate_plugin_customizer_options',
	));

	$wp_customize->add_section('divi_ultimate_plugin_widget_styling', array(
		'priority' => 30,
		'title' => 'Widget Styling',
		'panel'  => 'divi_ultimate_plugin_customizer_options',
	));
	
	// $wp_customize->add_section('divi_ultimate_plugin_blog_styling', array(
	//     'priority' => 40,
	//     'title' => 'Blog Post Styling',
	//     'panel'  => 'divi_ultimate_plugin_customizer_options',
	// )); 
	
	$wp_customize->add_section('divi_ultimate_plugin_blog_post_main_settings', array(
		'priority' => 5,
		'title' => 'Blog Main Settings',
		'panel'  => 'divi_ultimate_plugin_blog_post_customizer_options',
	)); 
	
	$wp_customize->add_section('divi_ultimate_plugin_blog_post_header', array(
		'priority' => 10,
		'title' => 'Customize Blog Header',
		'panel'  => 'divi_ultimate_plugin_blog_post_customizer_options',
	));
	
	$wp_customize->add_section('divi_ultimate_plugin_blog_post_featured_image', array(
		'priority' => 20,
		'title' => 'Customize Blog Featured Image',
		'panel'  => 'divi_ultimate_plugin_blog_post_customizer_options',
	));
	
	$wp_customize->add_section('divi_ultimate_plugin_blog_post_sidebar', array(
		'priority' => 30,
		'title' => 'Customize Blog Sidebar',
		'panel'  => 'divi_ultimate_plugin_blog_post_customizer_options',
	));
	
	$wp_customize->add_section('divi_ultimate_plugin_blog_post_navigation', array(
		'priority' => 40,
		'title' => 'Customize Blog Post Navigation',
		'panel'  => 'divi_ultimate_plugin_blog_post_customizer_options',
	));
	
	$wp_customize->add_section('divi_ultimate_plugin_blog_post_related_posts', array(
		'priority' => 50,
		'title' => 'Customize Blog Related Posts',
		'panel'  => 'divi_ultimate_plugin_blog_post_customizer_options',
	));

	$wp_customize->add_section('divi_ultimate_plugin_blog_post_comments', array(
		'priority' => 60,
		'title' => 'Customize Blog Comments',
		'panel'  => 'divi_ultimate_plugin_blog_post_customizer_options',
	));


	// Add new settings
	$wp_customize->add_setting( 'divi_ultimate_plugin_global_styling_settings', array(
		'default'        => 'none',
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_global_styling_settings', array(
		'settings' => 'divi_ultimate_plugin_global_styling_settings',
		'label'    => 'Choose your desired theme',
		'section'  => 'divi_ultimate_plugin_global_styling',
		'type'     => 'select',
		'choices'  => array(
			'none'					=> 'None',
			'free-all'				=> 'Load all themes styling (Great for demo purpose)',
			'free-agency'			=> 'Agency',
			'free-architect'		=> 'Architect',
			'free-bands'			=> 'Bands',
			'free-barber'			=> 'Barber',
			'free-business'			=> 'Business',
			'free-construction'		=> 'Construction',
			'free-consulting'		=> 'Consulting',
			'free-divi-ultimate'	=> 'Divi Ultimate',
			'free-event'			=> 'Event',
			'free-freelancer' 		=> 'Freelancer',
			'free-gym'				=> 'Gym & Fitness',
			'free-hotel'			=> 'Hotel',
			'free-interior'			=> 'Interior Design',
			'free-lawyer'			=> 'Lawyer',
			'free-magazine'			=> 'Magazine',
			'free-medical'			=> 'Medical',
			'free-mobile-app'		=> 'Mobile App',
			'free-newspaper'		=> 'Newspaper',
			'free-photography'		=> 'Photography',
			'free-restaurant'		=> 'Restaurant',
			'free-shop'				=> 'Shop',
			'free-wedding'			=> 'Wedding',
		),
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_global_heading_border_color_settings', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_global_heading_border_color_settings', array(
		'label'		=> 'Heading Border Color',
		'section'	=> 'divi_ultimate_plugin_global_styling',
		'settings'	=> 'divi_ultimate_plugin_global_heading_border_color_settings',
	) ) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_global_heading_small_color_settings', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_global_heading_small_color_settings', array(
		'label'		=> 'Heading Small Color',
		'section'	=> 'divi_ultimate_plugin_global_styling',
		'settings'	=> 'divi_ultimate_plugin_global_heading_small_color_settings',
	) ) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_global_heading_top_text_color_settings', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_global_heading_top_text_color_settings', array(
		'label'		=> 'Heading Top Text Color',
		'section'	=> 'divi_ultimate_plugin_global_styling',
		'settings'	=> 'divi_ultimate_plugin_global_heading_top_text_color_settings',
	) ) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_global_testimonial_slider_button_color_settings', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_global_testimonial_slider_button_color_settings', array(
		'label'		=> 'Testimonial Modern Slider Hover Color',
		'section'	=> 'divi_ultimate_plugin_global_styling',
		'settings'	=> 'divi_ultimate_plugin_global_testimonial_slider_button_color_settings',
	) ) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_header_styling_settings', array(
		'default'        => 'none',
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_header_styling_settings', array(
		'settings' => 'divi_ultimate_plugin_header_styling_settings',
		'label'    => 'Choose your custom navigation style',
		'section'  => 'divi_ultimate_plugin_header_styling',
		'type'     => 'select',
		'choices'  => divi_ultimate_plugin_header_styling_choices(),
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_header_menu_hover_style', array(
		'default'        => 'none',
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_header_menu_hover_style', array(
		'settings' => 'divi_ultimate_plugin_header_menu_hover_style',
		'label'    => 'Choose your custom menu hover style',
		'section'  => 'divi_ultimate_plugin_header_styling',
		'type'     => 'select',
		'choices'  => array(
			'none' => 'None',
			'free-menu-hover-1' => 'Style 1',
			'free-menu-hover-2' => 'Style 2',
			'free-menu-hover-3' => 'Style 3',
		),
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_header_menu_hover_main_color', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_header_menu_hover_main_color', array(
		'label'		=> 'Menu Hover Main Color',
		'section'	=> 'divi_ultimate_plugin_header_styling',
		'settings'	=> 'divi_ultimate_plugin_header_menu_hover_main_color',
	) ) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_header_menu_hover_text_color', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_header_menu_hover_text_color', array(
		'label'		=> 'Menu Hover Text Color',
		'section'	=> 'divi_ultimate_plugin_header_styling',
		'settings'	=> 'divi_ultimate_plugin_header_menu_hover_text_color',
	) ) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_header_mega_menu_fix', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_header_mega_menu_fix', array(
		'settings' => 'divi_ultimate_plugin_header_mega_menu_fix',
		'label'    => 'Enable Fix For Mega Menu',
		'section'  => 'divi_ultimate_plugin_header_styling',
		'type'     => 'checkbox',
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_header_show_search', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_header_show_search', array(
		'settings' => 'divi_ultimate_plugin_header_show_search',
		'label'    => 'Show Search in Custom Menu',
		'section'  => 'divi_ultimate_plugin_header_styling',
		'type'     => 'checkbox',
	));
	
	if ( class_exists( 'woocommerce' ) ) {
		$wp_customize->add_setting( 'divi_ultimate_plugin_header_show_cart', array(
			'default'        => false,
			'type'           => 'option',
			'capability'     => 'edit_theme_options',
		));

		$wp_customize->add_control( 'divi_ultimate_plugin_header_show_cart', array(
			'settings' => 'divi_ultimate_plugin_header_show_cart',
			'label'    => 'Show Cart in Custom Menu',
			'section'  => 'divi_ultimate_plugin_header_styling',
			'type'     => 'checkbox',
		));
	}
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_header_search_text_color', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_header_search_text_color', array(
		'label'		=> 'Search Text Color',
		'section'	=> 'divi_ultimate_plugin_header_styling',
		'settings'	=> 'divi_ultimate_plugin_header_search_text_color',
	) ) );
	
	if ( class_exists( 'woocommerce' ) ) {
		$wp_customize->add_setting( 'divi_ultimate_plugin_header_cart_background_color', array(
			'default'		=> null,
			'type'			=> 'option',
			'capability'	=> 'edit_theme_options',
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_header_cart_background_color', array(
			'label'		=> 'Cart Background Color',
			'section'	=> 'divi_ultimate_plugin_header_styling',
			'settings'	=> 'divi_ultimate_plugin_header_cart_background_color',
		) ) );
		
		$wp_customize->add_setting( 'divi_ultimate_plugin_header_cart_total_color', array(
			'default'		=> null,
			'type'			=> 'option',
			'capability'	=> 'edit_theme_options',
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_header_cart_total_color', array(
			'label'		=> 'Cart Total Color',
			'section'	=> 'divi_ultimate_plugin_header_styling',
			'settings'	=> 'divi_ultimate_plugin_header_cart_total_color',
		) ) );
		
	}

	$wp_customize->add_setting( 'divi_ultimate_plugin_footer_styling_settings', array(
		'default'        => 'none',
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_footer_styling_settings', array(
		'settings' => 'divi_ultimate_plugin_footer_styling_settings',
		'label'    => 'Choose your custom footer style',
		'section'  => 'divi_ultimate_plugin_footer_styling',
		'type'     => 'select',
		'choices'  => divi_ultimate_plugin_footer_styling_choices(),
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_footer_reveal', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_footer_reveal', array(
		'settings' => 'divi_ultimate_plugin_footer_reveal',
		'label'    => 'Enable Footer Reveal',
		'section'  => 'divi_ultimate_plugin_footer_styling',
		'type'     => 'checkbox',
	));
	
	if (class_exists('ET_Divi_Range_Option')) {
		$wp_customize->add_setting( 'divi_ultimate_plugin_footer_reveal_shadow_opacity', array(
			'default'       => '35',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_footer_reveal_shadow_opacity', array(
			'label'	      => 'Box Shadow Opacity',
			'section'     => 'divi_ultimate_plugin_footer_styling',
			'settings'	  => 'divi_ultimate_plugin_footer_reveal_shadow_opacity',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1
			),
		) ) );
		
		$wp_customize->add_setting( 'divi_ultimate_plugin_footer_reveal_shadow_verical', array(
			'default'       => '40',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_footer_reveal_shadow_verical', array(
			'label'	      => 'Box Shadow Vertical Position',
			'section'     => 'divi_ultimate_plugin_footer_styling',
			'settings'	  => 'divi_ultimate_plugin_footer_reveal_shadow_verical',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1
			),
		) ) );
		
		$wp_customize->add_setting( 'divi_ultimate_plugin_footer_reveal_shadow_blur', array(
			'default'       => '40',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_footer_reveal_shadow_blur', array(
			'label'	      => 'Box Shadow Blur Strength',
			'section'     => 'divi_ultimate_plugin_footer_styling',
			'settings'	  => 'divi_ultimate_plugin_footer_reveal_shadow_blur',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1
			),
		) ) );
		
		$wp_customize->add_setting( 'divi_ultimate_plugin_footer_reveal_shadow_spread', array(
			'default'       => '30',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_footer_reveal_shadow_spread', array(
			'label'	      => 'Box Shadow Spread Strength',
			'section'     => 'divi_ultimate_plugin_footer_styling',
			'settings'	  => 'divi_ultimate_plugin_footer_reveal_shadow_spread',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1
			),
		) ) );
	}

	$wp_customize->add_setting( 'divi_ultimate_plugin_widget_styling_settings', array(
		'default'        => 'none',
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_widget_styling_settings', array(
		'settings' => 'divi_ultimate_plugin_widget_styling_settings',
		'label'    => 'Choose your custom widget style',
		'section'  => 'divi_ultimate_plugin_widget_styling',
		'type'     => 'select',
		'choices'  => array(
			'none' => 'Default',
			'free-sidebar-style-1' => 'Style 1',
			'free-sidebar-style-2' => 'Style 2',
		),
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_widget_main_color_settings', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_widget_main_color_settings', array(
		'label'		=> 'Widget Main Color',
		'section'	=> 'divi_ultimate_plugin_widget_styling',
		'settings'	=> 'divi_ultimate_plugin_widget_main_color_settings',
	) ) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_widget_header_color_settings', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_widget_header_color_settings', array(
		'label'		=> 'Widget Header Color',
		'section'	=> 'divi_ultimate_plugin_widget_styling',
		'settings'	=> 'divi_ultimate_plugin_widget_header_color_settings',
	) ) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_widget_text_color_settings', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_widget_text_color_settings', array(
		'label'		=> 'Widget Text Color',
		'section'	=> 'divi_ultimate_plugin_widget_styling',
		'settings'	=> 'divi_ultimate_plugin_widget_text_color_settings',
	) ) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_widget_custom_header_settings', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_widget_custom_header_settings', array(
		'settings' => 'divi_ultimate_plugin_widget_custom_header_settings',
		'label'    => 'Use custom widget header style',
		'section'  => 'divi_ultimate_plugin_widget_styling',
		'type'     => 'checkbox',
	));
	
	if (class_exists('ET_Divi_Range_Option')) {
		$wp_customize->add_setting( 'divi_ultimate_plugin_widget_header_size_settings', array(
			'default'       => '14',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_widget_header_size_settings', array(
			'label'	      => 'Widget Header Text Size',
			'section'     => 'divi_ultimate_plugin_widget_styling',
			'settings'	  => 'divi_ultimate_plugin_widget_header_size_settings',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 10,
				'max'  => 32,
				'step' => 1
			),
		) ) );
	}
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_widget_header_bold_settings', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_widget_header_bold_settings', array(
		'settings' => 'divi_ultimate_plugin_widget_header_bold_settings',
		'label'    => 'Bold Widget Header',
		'section'  => 'divi_ultimate_plugin_widget_styling',
		'type'     => 'checkbox',
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_widget_header_uppercase_settings', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_widget_header_uppercase_settings', array(
		'settings' => 'divi_ultimate_plugin_widget_header_uppercase_settings',
		'label'    => 'Uppercase Widget Header',
		'section'  => 'divi_ultimate_plugin_widget_styling',
		'type'     => 'checkbox',
	));
	
	// $wp_customize->add_setting( 'divi_ultimate_plugin_blog_styling_settings', array(
	//     'default'        => 'none',
	//     'type'           => 'option',
	//     'capability'     => 'edit_theme_options',
	// ));
	

	// $wp_customize->add_control( 'divi_ultimate_plugin_blog_styling_settings', array(
	//     'settings' => 'divi_ultimate_plugin_blog_styling_settings',
	//     'label'    => 'Choose your custom blog style',
	//     'section'  => 'divi_ultimate_plugin_blog_styling',
	// 	'type'     => 'select',
	// 	'choices'  => divi_ultimate_plugin_blog_styling_choices(),
	// ));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_enable', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_enable', array(
		'settings' => 'divi_ultimate_plugin_blog_post_enable',
		'label'    => 'Enable Divi Ultimate Blog Design',
		'section'  => 'divi_ultimate_plugin_blog_post_main_settings',
		'type'     => 'checkbox',
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_style', array(
		'default'        => 'none',
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_header_style', array(
		'settings' => 'divi_ultimate_plugin_blog_post_header_style',
		'label'    => 'Choose your blog header style',
		'section'  => 'divi_ultimate_plugin_blog_post_main_settings',
		'type'     => 'select',
		'choices'  => array(
			'none' => 'Style 1',
			'free-blog-post-style-2' => 'Style 2',
			'free-blog-post-style-3' => 'Style 3',
			'free-blog-post-style-4' => 'Style 4',
			'free-blog-post-style-5' => 'Style 5',
		),
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_parallax', array(
		'default'        => 'none',
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_header_parallax', array(
		'settings' => 'divi_ultimate_plugin_blog_post_header_parallax',
		'label'    => 'Parallax for Blog Header Featured',
		'section'  => 'divi_ultimate_plugin_blog_post_main_settings',
		'type'     => 'select',
		'choices'  => array(
			'none' => 'None',
			'parallax' => 'True Parallax',
			'css' => 'CSS Fixed',
		),
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_navigation_style', array(
		'default'        => 'free-blog-post-navigation-hide',
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_navigation_style', array(
		'settings' => 'divi_ultimate_plugin_blog_post_navigation_style',
		'label'    => 'Choose your post navigation style',
		'section'  => 'divi_ultimate_plugin_blog_post_main_settings',
		'type'     => 'select',
		'choices'  => array(
			'free-blog-post-navigation-hide' => 'None',
			'free-blog-post-navigation-style-1' => 'Style 1',
		),
	));	

	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_related_posts_style', array(
		'default'        => 'free-blog-post-related-posts-hide',
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_related_posts_style', array(
		'settings' => 'divi_ultimate_plugin_blog_post_related_posts_style',
		'label'    => 'Choose your related posts style',
		'section'  => 'divi_ultimate_plugin_blog_post_main_settings',
		'type'     => 'select',
		'choices'  => array(
			'free-blog-post-related-posts-hide' => 'None',
			' ' => 'Style 1',
			'free-blog-list-2' => 'Style 2',
			'free-blog-list-2 free-blog-background-solid free-blog-all-center' => 'Style 3',
		),
	));	
	
	if (class_exists('ET_Divi_Range_Option')) {
		$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_related_posts_border_radius', array(
			'default'       => '15',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'transport'     => 'postMessage',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_blog_post_related_posts_border_radius', array(
			'label'	      => 'Related Posts Border Radius',
			'section'     => 'divi_ultimate_plugin_blog_post_main_settings',
			'settings'	  => 'divi_ultimate_plugin_blog_post_related_posts_border_radius',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1
			),
		) ) );
	}
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_header_custom', array(
		'settings' => 'divi_ultimate_plugin_blog_post_header_custom',
		'label'    => 'Custom Blog Header Settings',
		'section'  => 'divi_ultimate_plugin_blog_post_header',
		'type'     => 'checkbox',
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_hide', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_header_custom_hide', array(
		'settings' => 'divi_ultimate_plugin_blog_post_header_custom_hide',
		'label'    => 'Hide featured header image',
		'section'  => 'divi_ultimate_plugin_blog_post_header',
		'type'     => 'checkbox',
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_overlay', array(
		'default'        => 'free-blog-post-header-featured-overlay-none',
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_header_custom_overlay', array(
		'settings' => 'divi_ultimate_plugin_blog_post_header_custom_overlay',
		'label'    => 'Choose your featured header overlay',
		'section'  => 'divi_ultimate_plugin_blog_post_header',
		'type'     => 'select',
		'choices'  => array(
			'free-blog-post-header-featured-overlay-none' => 'None',
			'free-blog-post-header-featured-overlay-solid' => 'Solid',
			'free-blog-post-header-featured-overlay-gradient' => 'Gradient',
		),
	));	
	
	if (class_exists('ET_Divi_Range_Option')) {
		$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_blur', array(
			'default'       => '0',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'transport'     => 'postMessage',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_blog_post_header_custom_blur', array(
			'label'	      => 'Featured Header Blur',
			'section'     => 'divi_ultimate_plugin_blog_post_header',
			'settings'	  => 'divi_ultimate_plugin_blog_post_header_custom_blur',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1
			),
		) ) );
	}
	
	if (class_exists('ET_Divi_Range_Option')) {
		$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_scale', array(
			'default'       => '0',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'transport'     => 'postMessage',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_blog_post_header_custom_scale', array(
			'label'	      => 'Featured Header Scale',
			'section'     => 'divi_ultimate_plugin_blog_post_header',
			'settings'	  => 'divi_ultimate_plugin_blog_post_header_custom_scale',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 9,
				'step' => 1
			),
		) ) );
	}

	if (class_exists('ET_Divi_Range_Option')) {
		$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_padding_top', array(
			'default'       => '42',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'transport'     => 'postMessage',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_blog_post_header_custom_padding_top', array(
			'label'	      => 'Header Content Padding Top',
			'section'     => 'divi_ultimate_plugin_blog_post_header',
			'settings'	  => 'divi_ultimate_plugin_blog_post_header_custom_padding_top',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 500,
				'step' => 1
			),
		) ) );
	}
	
	if (class_exists('ET_Divi_Range_Option')) {
		$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_padding_bottom', array(
			'default'       => '100',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'transport'     => 'postMessage',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_blog_post_header_custom_padding_bottom', array(
			'label'	      => 'Header Content Padding Bottom',
			'section'     => 'divi_ultimate_plugin_blog_post_header',
			'settings'	  => 'divi_ultimate_plugin_blog_post_header_custom_padding_bottom',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 500,
				'step' => 1
			),
		) ) );
	}
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_alignment', array(
		'default'        => 'free-blog-post-header-content-center',
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_header_custom_alignment', array(
		'settings' => 'divi_ultimate_plugin_blog_post_header_custom_alignment',
		'label'    => 'Choose your header content alignment',
		'section'  => 'divi_ultimate_plugin_blog_post_header',
		'type'     => 'select',
		'choices'  => array(
			'free-blog-post-header-content-center' => 'Center',
			'free-blog-post-header-content-left' => 'Left',
			'free-blog-post-header-content-right' => 'Right',
		),
	));	
	
	if (class_exists('ET_Divi_Range_Option')) {
		$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_width', array(
			'default'       => '700',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'transport'     => 'postMessage',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_blog_post_header_custom_width', array(
			'label'	      => 'Header Content Max Width',
			'section'     => 'divi_ultimate_plugin_blog_post_header',
			'settings'	  => 'divi_ultimate_plugin_blog_post_header_custom_width',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 300,
				'max'  => 1140,
				'step' => 1
			),
		) ) );
	}
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_title_uppercase', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_header_custom_title_uppercase', array(
		'settings' => 'divi_ultimate_plugin_blog_post_header_custom_title_uppercase',
		'label'    => 'Header Title Uppercase',
		'section'  => 'divi_ultimate_plugin_blog_post_header',
		'type'     => 'checkbox',
	));	
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_meta_uppercase', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_header_custom_meta_uppercase', array(
		'settings' => 'divi_ultimate_plugin_blog_post_header_custom_meta_uppercase',
		'label'    => 'Header Meta Uppercase',
		'section'  => 'divi_ultimate_plugin_blog_post_header',
		'type'     => 'checkbox',
	));
	
	if (class_exists('ET_Divi_Range_Option')) {
		$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_title_size', array(
			'default'       => '38',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'transport'     => 'postMessage',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_blog_post_header_custom_title_size', array(
			'label'	      => 'Header Title Font Size',
			'section'     => 'divi_ultimate_plugin_blog_post_header',
			'settings'	  => 'divi_ultimate_plugin_blog_post_header_custom_title_size',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 14,
				'max'  => 100,
				'step' => 1
			),
		) ) );
	}
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_title_weight', array(
		'default'        => '700',
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_header_custom_title_weight', array(
		'settings' => 'divi_ultimate_plugin_blog_post_header_custom_title_weight',
		'label'    => 'Header Title Font Weight',
		'section'  => 'divi_ultimate_plugin_blog_post_header',
		'type'     => 'select',
		'choices'  => array(
			'100' => 'Thin',
			'200' => 'Extra Light',
			'300' => 'Light',
			'400' => 'Regular',
			'600' => 'Semi Bold',
			'700' => 'Bold',
			'800' => 'Ultra Bold',
		),
	));	
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_background_color', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'     => 'postMessage',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_blog_post_header_custom_background_color', array(
		'label'		=> 'Blog Header Background Color',
		'section'	=> 'divi_ultimate_plugin_blog_post_header',
		'settings'	=> 'divi_ultimate_plugin_blog_post_header_custom_background_color',
	) ) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_title_color', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'     => 'postMessage',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_blog_post_header_custom_title_color', array(
		'label'		=> 'Blog Header Title Color',
		'section'	=> 'divi_ultimate_plugin_blog_post_header',
		'settings'	=> 'divi_ultimate_plugin_blog_post_header_custom_title_color',
	) ) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_header_custom_meta_color', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'     => 'postMessage',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_blog_post_header_custom_meta_color', array(
		'label'		=> 'Blog Header Meta Color',
		'section'	=> 'divi_ultimate_plugin_blog_post_header',
		'settings'	=> 'divi_ultimate_plugin_blog_post_header_custom_meta_color',
	) ) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_featured_image_custom', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_featured_image_custom', array(
		'settings' => 'divi_ultimate_plugin_blog_post_featured_image_custom',
		'label'    => 'Custom Featured Image Settings',
		'section'  => 'divi_ultimate_plugin_blog_post_featured_image',
		'type'     => 'checkbox',
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_featured_image_custom_hide', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_featured_image_custom_hide', array(
		'settings' => 'divi_ultimate_plugin_blog_post_featured_image_custom_hide',
		'label'    => 'Hide Featured Image',
		'section'  => 'divi_ultimate_plugin_blog_post_featured_image',
		'type'     => 'checkbox',
	));	
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_featured_image_custom_box_shadow', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_featured_image_custom_box_shadow', array(
		'settings' => 'divi_ultimate_plugin_blog_post_featured_image_custom_box_shadow',
		'label'    => 'Box Shadow for Featured Image',
		'section'  => 'divi_ultimate_plugin_blog_post_featured_image',
		'type'     => 'checkbox',
	));	
	
	if (class_exists('ET_Divi_Range_Option')) {
		$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_featured_image_custom_offset', array(
			'default'       => '0',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'transport'     => 'postMessage',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_blog_post_featured_image_custom_offset', array(
			'label'	      => 'Featured Image Top Offset',
			'section'     => 'divi_ultimate_plugin_blog_post_featured_image',
			'settings'	  => 'divi_ultimate_plugin_blog_post_featured_image_custom_offset',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 300,
				'step' => 1
			),
		) ) );	
	}
	
	if (class_exists('ET_Divi_Range_Option')) {
		$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_featured_image_custom_border_width', array(
			'default'       => '0',
			'type'          => 'option',
			'capability'    => 'edit_theme_options',
			'transport'     => 'postMessage',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'divi_ultimate_plugin_blog_post_featured_image_custom_border_width', array(
			'label'	      => 'Featured Image Border Width',
			'section'     => 'divi_ultimate_plugin_blog_post_featured_image',
			'settings'	  => 'divi_ultimate_plugin_blog_post_featured_image_custom_border_width',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 30,
				'step' => 1
			),
		) ) );	
	}
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_sidebar_hide', array(
		'default'        => false,
		'type'           => 'option',
		'capability'     => 'edit_theme_options',
		'transport'     => 'postMessage',
	));

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_sidebar_hide', array(
		'settings' => 'divi_ultimate_plugin_blog_post_sidebar_hide',
		'label'    => 'Hide Blog Sidebar',
		'section'  => 'divi_ultimate_plugin_blog_post_sidebar',
		'type'     => 'checkbox',
	));
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_navigation_previous_text', array(
		'default'		=> 'Previous',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	) );

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_navigation_previous_text', array(
		'settings' => 'divi_ultimate_plugin_blog_post_navigation_previous_text',
		'label'		=> '"Previous" Text',
		'section'	=> 'divi_ultimate_plugin_blog_post_navigation',
		'type'      => 'text',
	) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_navigation_next_text', array(
		'default'		=> 'Next',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	) );

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_navigation_next_text', array(
		'settings' => 'divi_ultimate_plugin_blog_post_navigation_next_text',
		'label'		=> '"Next" Text',
		'section'	=> 'divi_ultimate_plugin_blog_post_navigation',
		'type'      => 'text',
	) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_related_posts_title', array(
		'default'		=> 'Related Posts',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	) );

	$wp_customize->add_control( 'divi_ultimate_plugin_blog_post_related_posts_title', array(
		'settings' => 'divi_ultimate_plugin_blog_post_related_posts_title',
		'label'		=> 'Related Posts Title',
		'section'	=> 'divi_ultimate_plugin_blog_post_related_posts',
		'type'      => 'text',
	) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_related_posts_background_color', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'     => 'postMessage',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_blog_post_related_posts_background_color', array(
		'label'		=> 'Related Posts Background Color',
		'section'	=> 'divi_ultimate_plugin_blog_post_related_posts',
		'settings'	=> 'divi_ultimate_plugin_blog_post_related_posts_background_color',
	) ) );
	
	$wp_customize->add_setting( 'divi_ultimate_plugin_blog_post_related_posts_title_color', array(
		'default'		=> null,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'     => 'postMessage',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'divi_ultimate_plugin_blog_post_related_posts_title_color', array(
		'label'		=> 'Related Posts Title Color',
		'section'	=> 'divi_ultimate_plugin_blog_post_related_posts',
		'settings'	=> 'divi_ultimate_plugin_blog_post_related_posts_title_color',
	) ) );
	
}

// Header styling choices
function divi_ultimate_plugin_header_styling_choices() {
	$divi_ultimate_plugin_header_styling_choices_array = array();
	$divi_ultimate_plugin_header_styling_choices_array['none'] = 'None';
	$args = array( 'post_type' => 'et_pb_layout', 'numberposts' => -1,
				'tax_query' => array(
					array(
						'taxonomy' => 'layout_category',
						'field' => 'slug',
						'terms' => 'header-navigation-divi-ultimate-child-theme'
					)
				)
			 );
	$divi_ultimate_plugin_header_styles = get_posts( $args );
	if ($divi_ultimate_plugin_header_styles) {
		foreach( $divi_ultimate_plugin_header_styles as $divi_ultimate_plugin_header_style ) :
			$divi_ultimate_plugin_header_styling_choices_array[$divi_ultimate_plugin_header_style -> ID] = $divi_ultimate_plugin_header_style -> post_title;
		endforeach;
	};
	return $divi_ultimate_plugin_header_styling_choices_array;
}

// Footer styling choices
function divi_ultimate_plugin_footer_styling_choices() {
	$divi_ultimate_plugin_footer_styling_choices_array = array();
	$divi_ultimate_plugin_footer_styling_choices_array['none'] = 'None';
	$args = array( 'post_type' => 'et_pb_layout', 'numberposts' => -1,
				'tax_query' => array(
					array(
						'taxonomy' => 'layout_category',
						'field' => 'slug',
						'terms' => 'footer-divi-ultimate-child-theme'
					)
				)
			 );
	$divi_ultimate_plugin_footer_styles = get_posts( $args );
	if ($divi_ultimate_plugin_footer_styles) {
		foreach( $divi_ultimate_plugin_footer_styles as $divi_ultimate_plugin_footer_style ) :
			$divi_ultimate_plugin_footer_styling_choices_array[$divi_ultimate_plugin_footer_style -> ID] = $divi_ultimate_plugin_footer_style -> post_title;
		endforeach;
	};
	return $divi_ultimate_plugin_footer_styling_choices_array;
}

// Blog styling choices
function divi_ultimate_plugin_blog_styling_choices() {
	$divi_ultimate_plugin_blog_styling_choices_array = array();
	$divi_ultimate_plugin_blog_styling_choices_array['none'] = 'None';
	$divi_ultimate_plugin_blog_styles = get_page_templates(null, 'post');
	if ($divi_ultimate_plugin_blog_styles) {
		foreach( $divi_ultimate_plugin_blog_styles as $divi_ultimate_plugin_blog_style => $divi_ultimate_plugin_blog_style_filename ) :
		$divi_ultimate_plugin_blog_styling_choices_array[$divi_ultimate_plugin_blog_style_filename] = $divi_ultimate_plugin_blog_style;
		endforeach;
	};

	return $divi_ultimate_plugin_blog_styling_choices_array;
}
// -------------- Customizer Settings End ----------------

// ----------------- Customizer JS Start -------------------
function divi_ultimate_plugin_customize_controls_js_css() {
	wp_enqueue_script( 'divi-ultimate-customizer-controls-js', plugin_dir_url( __FILE__ ) . 'customizer/divi-ultimate-customizer-controls.js', array( 'jquery' ), DIVI_ULTIMATE_PLUGIN_VERSION, true );
	wp_enqueue_style( 'divi-ultimate-customizer-controls-css', plugin_dir_url( __FILE__ ) . 'customizer/divi-ultimate-customizer-controls.css', array(), DIVI_ULTIMATE_PLUGIN_VERSION );
}
// ----------------- Customizer JS End -------------------

// ----------------- Customizer Preview JS Start -------------------
function divi_ultimate_plugin_customize_preview_js() {
	wp_enqueue_script( 'divi-ultimate-customizer-js', plugin_dir_url( __FILE__ ) . 'customizer/divi-ultimate-customizer.js', array( 'customize-preview' ), DIVI_ULTIMATE_PLUGIN_VERSION, true );
}
// ----------------- Customizer Preivew JS End -------------------
	
// ------------- Custom Blog Function Start -----------------
function divi_ultimate_plugin_custom_blog($blog_template) {
	global $wp_query, $post;
	$divi_ultimate_plugin_custom_blog_file_name = get_option( 'divi_ultimate_plugin_blog_styling_settings', 'none' );
	if ( $divi_ultimate_plugin_custom_blog_file_name != 'none' && ( empty($post->_wp_page_template) || $post->_wp_page_template == 'default' ) ) {
		if ( file_exists( get_stylesheet_directory() . '/' . $divi_ultimate_plugin_custom_blog_file_name ) ) {
			return get_stylesheet_directory() . '/' . $divi_ultimate_plugin_custom_blog_file_name;
		}
	}
	return $blog_template;
}
// ------------- Custom Blog Function End -----------------

// ------------- DU Blog Post Function Start -----------------
function divi_ultimate_plugin_blog_post($blog_template) {
	global $wp_query, $post;
	$divi_ultimate_plugin_blog_post_enable = get_option( 'divi_ultimate_plugin_blog_post_enable' );
	if ( $divi_ultimate_plugin_blog_post_enable && $post->post_type == 'post' ) {
		if ( file_exists( plugin_dir_path( __FILE__ ) . 'blog/du-blog-1.php' ) ) {
			return plugin_dir_path( __FILE__ ) . 'blog/du-blog-1.php';
		}
	}
	return $blog_template;
}
// ------------- DU Blog Post Function End -----------------

// ------------- Add CSS Class to Body Tag Start -----------------
function divi_ultimate_plugin_add_body_class( $classes = '' ) {
	$divi_ultimate_plugin_global_styling_settings = get_option( 'divi_ultimate_plugin_global_styling_settings', 'none' );
	$divi_ultimate_plugin_widget_styling_settings = get_option( 'divi_ultimate_plugin_widget_styling_settings', 'none' );
	$divi_ultimate_plugin_header_styling_settings = get_option( 'divi_ultimate_plugin_header_styling_settings', 'none' );
	$divi_ultimate_plugin_header_menu_hover_style = get_option( 'divi_ultimate_plugin_header_menu_hover_style', 'none' );
	$divi_ultimate_plugin_blog_post_header_custom = get_option( 'divi_ultimate_plugin_blog_post_header_custom');
	$divi_ultimate_plugin_blog_post_header_style = get_option( 'divi_ultimate_plugin_blog_post_header_style', 'none' );
	$divi_ultimate_plugin_blog_post_navigation_style = get_option( 'divi_ultimate_plugin_blog_post_navigation_style', 'free-blog-post-navigation-hide' );
	$divi_ultimate_plugin_blog_post_related_posts_style = get_option( 'divi_ultimate_plugin_blog_post_related_posts_style', 'free-blog-post-related-posts-hide' );
	$divi_ultimate_plugin_blog_post_header_custom_alignment = get_option( 'divi_ultimate_plugin_blog_post_header_custom_alignment', 'free-blog-post-header-content-center' );
	$divi_ultimate_plugin_blog_post_header_custom_hide = get_option( 'divi_ultimate_plugin_blog_post_header_custom_hide' );
	$divi_ultimate_plugin_blog_post_featured_image_custom = get_option( 'divi_ultimate_plugin_blog_post_featured_image_custom' );
	$divi_ultimate_plugin_blog_post_featured_image_custom_hide = get_option( 'divi_ultimate_plugin_blog_post_featured_image_custom_hide' );
	$divi_ultimate_plugin_blog_post_featured_image_custom_box_shadow = get_option( 'divi_ultimate_plugin_blog_post_featured_image_custom_box_shadow' );
	$divi_ultimate_plugin_blog_post_sidebar_hide = get_option( 'divi_ultimate_plugin_blog_post_sidebar_hide' );
	$divi_ultimate_plugin_header_mega_menu_fix = get_option( 'divi_ultimate_plugin_header_mega_menu_fix' );
	$divi_ultimate_plugin_footer_styling_settings = get_option( 'divi_ultimate_plugin_footer_styling_settings', 'none' );
	$divi_ultimate_plugin_footer_reveal = get_option( 'divi_ultimate_plugin_footer_reveal');
	
	$classes[] = 'free-du-global-styling';
	
	$excludes = array('none', 'free-all', 'free-divi-ultimate', 'free-photography');
	if (is_singular()) {
		$overrides_global = get_post_meta(get_the_ID(), 'divi_ultimate_plugin_global_styling_overrides', true);
		// Check for global styling overrides
		if (empty($overrides_global)) {
			if ( !in_array($divi_ultimate_plugin_global_styling_settings, $excludes) ) {
				$classes[] = $divi_ultimate_plugin_global_styling_settings;
			}
		} elseif ($overrides_global != 'none') {
			if ( !in_array($overrides_global, $excludes) ) {
				$classes[] = $overrides_global;
			}
		}
	}
	
	if ($divi_ultimate_plugin_header_styling_settings != 'none') {
		$classes[] = 'free-custom-header';
	}
	if ($divi_ultimate_plugin_footer_styling_settings != 'none') {
		$classes[] = 'free-custom-footer';
	}
	
	if ($divi_ultimate_plugin_widget_styling_settings != 'none') {
		$classes[] = $divi_ultimate_plugin_widget_styling_settings;
	}
	
	if ($divi_ultimate_plugin_header_menu_hover_style != 'none') {
		$classes[] = $divi_ultimate_plugin_header_menu_hover_style;
	}
	if ($divi_ultimate_plugin_header_mega_menu_fix) {
		$classes[] = 'free-mega-menu';
	}
	
	if ($divi_ultimate_plugin_blog_post_header_style != 'none') {
		$classes[] = $divi_ultimate_plugin_blog_post_header_style;
	}
	if ($divi_ultimate_plugin_blog_post_header_custom_hide) {
		$classes[] = 'free-blog-post-header-featured-hide';
	}
	if ($divi_ultimate_plugin_blog_post_featured_image_custom_hide) {
		$classes[] = 'free-blog-post-featured-image-hide';
	} else {
		$classes[] = 'free-blog-post-featured-image-show';
	}
	if ($divi_ultimate_plugin_blog_post_featured_image_custom_box_shadow) {
		$classes[] = 'free-blog-post-featured-image-box-shadow';
	}
	if ($divi_ultimate_plugin_blog_post_featured_image_custom) {
		$classes[] = 'free-blog-post-featured-image-custom';
	}
	if ($divi_ultimate_plugin_blog_post_related_posts_style == 'free-blog-post-related-posts-hide') {
		$classes[] = 'free-blog-post-related-posts-hide';
	}
	if ($divi_ultimate_plugin_blog_post_header_custom) {
		$classes[] = 'free-blog-post-header-custom';
	}
	if ($divi_ultimate_plugin_blog_post_sidebar_hide) {
		$classes[] = 'free-blog-post-sidebar-hide';
	}
	$classes[] = $divi_ultimate_plugin_blog_post_navigation_style;
	$classes[] = $divi_ultimate_plugin_blog_post_header_custom_alignment;
	
	if ($divi_ultimate_plugin_footer_styling_settings != 'none' && $divi_ultimate_plugin_footer_reveal) {
		$classes[] = 'free-footer-reveal';
	}
	
	return $classes;
}
// ------------- Add CSS Class to Body Tag End -----------------

// ------------- Add Selected Theme CSS Start -----------------
function divi_ultimate_plugin_theme_css() {
	$divi_ultimate_plugin_global_styling_settings = get_option( 'divi_ultimate_plugin_global_styling_settings', 'none' );
	$excludes = array('none', 'free-divi-ultimate', 'free-photography');
	if (is_singular()) {
		$overrides_global = get_post_meta(get_the_ID(), 'divi_ultimate_plugin_global_styling_overrides', true);
		// Check for global styling overrides
		if (empty($overrides_global)) {
			if ( !in_array($divi_ultimate_plugin_global_styling_settings, $excludes) ) {
				wp_enqueue_style('divi-ultimate-plugin-theme-css', plugin_dir_url( __FILE__ ) . 'css/' . $divi_ultimate_plugin_global_styling_settings . '.css', array(), DIVI_ULTIMATE_PLUGIN_VERSION);
			}
		} elseif ($overrides_global != 'none') {
			if ( !in_array($overrides_global, $excludes) ) {
				wp_enqueue_style('divi-ultimate-plugin-theme-css', plugin_dir_url( __FILE__ ) . 'css/' . $overrides_global . '.css', array(), DIVI_ULTIMATE_PLUGIN_VERSION);
			}
		}
	}
}
// ------------- Add Selected Theme CSS End -----------------

// ------------------ CSS Edit Start ----------------------
function divi_ultimate_plugin_css_edit() {
	$divi_ultimate_plugin_global_heading_border_color_settings = get_option( 'divi_ultimate_plugin_global_heading_border_color_settings', null );
	$divi_ultimate_plugin_global_heading_small_color_settings = get_option( 'divi_ultimate_plugin_global_heading_small_color_settings', null );
	$divi_ultimate_plugin_global_heading_top_text_color_settings = get_option( 'divi_ultimate_plugin_global_heading_top_text_color_settings', null );
	$divi_ultimate_plugin_global_testimonial_slider_button_color_settings = get_option( 'divi_ultimate_plugin_global_testimonial_slider_button_color_settings', null );
	$divi_ultimate_plugin_header_styling_settings = get_option( 'divi_ultimate_plugin_header_styling_settings', 'none' );
	$divi_ultimate_plugin_footer_styling_settings = get_option( 'divi_ultimate_plugin_footer_styling_settings', 'none' );
	$divi_ultimate_plugin_widget_styling_settings = get_option( 'divi_ultimate_plugin_widget_styling_settings', 'none' );
	$divi_ultimate_plugin_header_menu_hover_style = get_option( 'divi_ultimate_plugin_header_menu_hover_style', 'none' );
	$divi_ultimate_plugin_widget_main_color_settings = get_option( 'divi_ultimate_plugin_widget_main_color_settings', null );
	$divi_ultimate_plugin_widget_header_color_settings = get_option( 'divi_ultimate_plugin_widget_header_color_settings', null );
	$divi_ultimate_plugin_widget_text_color_settings = get_option( 'divi_ultimate_plugin_widget_text_color_settings', null );
	$divi_ultimate_plugin_widget_custom_header_settings = get_option( 'divi_ultimate_plugin_widget_custom_header_settings' );
	$divi_ultimate_plugin_widget_header_size_settings = get_option( 'divi_ultimate_plugin_widget_header_size_settings', 14 );
	$divi_ultimate_plugin_widget_header_bold_settings = get_option( 'divi_ultimate_plugin_widget_header_bold_settings' );
	$divi_ultimate_plugin_widget_header_uppercase_settings = get_option( 'divi_ultimate_plugin_widget_header_uppercase_settings' );
	$divi_ultimate_plugin_header_cart_background_color = get_option( 'divi_ultimate_plugin_header_cart_background_color', null );
	$divi_ultimate_plugin_header_cart_total_color = get_option( 'divi_ultimate_plugin_header_cart_total_color', null );
	$divi_ultimate_plugin_header_search_text_color = get_option( 'divi_ultimate_plugin_header_search_text_color', null );
	$divi_ultimate_plugin_header_menu_hover_main_color = get_option( 'divi_ultimate_plugin_header_menu_hover_main_color', null );
	$divi_ultimate_plugin_header_menu_hover_text_color = get_option( 'divi_ultimate_plugin_header_menu_hover_text_color', null );
	$divi_ultimate_plugin_blog_post_related_posts_border_radius = get_option( 'divi_ultimate_plugin_blog_post_related_posts_border_radius', 15 );
	$divi_ultimate_plugin_blog_post_header_custom = get_option( 'divi_ultimate_plugin_blog_post_header_custom');
	$divi_ultimate_plugin_blog_post_header_custom_background_color = get_option( 'divi_ultimate_plugin_blog_post_header_custom_background_color', null );
	$divi_ultimate_plugin_blog_post_header_custom_title_color = get_option( 'divi_ultimate_plugin_blog_post_header_custom_title_color', null );
	$divi_ultimate_plugin_blog_post_header_custom_meta_color = get_option( 'divi_ultimate_plugin_blog_post_header_custom_meta_color', null );
	$divi_ultimate_plugin_blog_post_header_custom_padding_top = get_option( 'divi_ultimate_plugin_blog_post_header_custom_padding_top', 42 );
	$divi_ultimate_plugin_blog_post_header_custom_padding_bottom = get_option( 'divi_ultimate_plugin_blog_post_header_custom_padding_bottom', 100 );
	$divi_ultimate_plugin_blog_post_header_custom_width = get_option( 'divi_ultimate_plugin_blog_post_header_custom_width', 700 );
	$divi_ultimate_plugin_blog_post_header_custom_title_uppercase = get_option( 'divi_ultimate_plugin_blog_post_header_custom_title_uppercase' );
	$divi_ultimate_plugin_blog_post_header_custom_meta_uppercase = get_option( 'divi_ultimate_plugin_blog_post_header_custom_meta_uppercase' );
	$divi_ultimate_plugin_blog_post_header_custom_blur = get_option( 'divi_ultimate_plugin_blog_post_header_custom_blur', 0 );
	$divi_ultimate_plugin_blog_post_header_custom_scale = get_option( 'divi_ultimate_plugin_blog_post_header_custom_scale', 1 );
	$divi_ultimate_plugin_blog_post_header_custom_title_size = get_option( 'divi_ultimate_plugin_blog_post_header_custom_title_size', 38 );
	$divi_ultimate_plugin_blog_post_header_custom_title_weight = get_option( 'divi_ultimate_plugin_blog_post_header_custom_title_weight', 700 );
	$divi_ultimate_plugin_blog_post_featured_image_custom = get_option( 'divi_ultimate_plugin_blog_post_featured_image_custom' );
	$divi_ultimate_plugin_blog_post_featured_image_custom_offset = get_option( 'divi_ultimate_plugin_blog_post_featured_image_custom_offset', 0 );
	$divi_ultimate_plugin_blog_post_featured_image_custom_border_width = get_option( 'divi_ultimate_plugin_blog_post_featured_image_custom_border_width', 0 );
	$divi_ultimate_plugin_blog_post_related_posts_title_color = get_option( 'divi_ultimate_plugin_blog_post_related_posts_title_color', null );
	$divi_ultimate_plugin_blog_post_related_posts_background_color = get_option( 'divi_ultimate_plugin_blog_post_related_posts_background_color', null );
	$divi_ultimate_plugin_footer_reveal = get_option( 'divi_ultimate_plugin_footer_reveal' );
	$divi_ultimate_plugin_footer_reveal_shadow_opacity = get_option( 'divi_ultimate_plugin_footer_reveal_shadow_opacity', 35 );
	$divi_ultimate_plugin_footer_reveal_shadow_verical = get_option( 'divi_ultimate_plugin_footer_reveal_shadow_verical', 40 );
	$divi_ultimate_plugin_footer_reveal_shadow_blur = get_option( 'divi_ultimate_plugin_footer_reveal_shadow_blur', 40 );
	$divi_ultimate_plugin_footer_reveal_shadow_spread = get_option( 'divi_ultimate_plugin_footer_reveal_shadow_spread', 30 );
	$divi_ultimate_plugin_footer_bug_main_background_color = get_option( 'theme_mods_Divi' );
	$hide_custom_header_navigation = '0';
	$show_default_divi_header = '0';
	if (is_singular()) {
		$hide_custom_header_navigation = get_post_meta(get_the_ID(), 'divi_ultimate_plugin_hide_custom_header_navigation', true);
		$show_default_divi_header = get_post_meta(get_the_ID(), 'divi_ultimate_plugin_show_default_divi_header', true);
	}
	
	switch ($divi_ultimate_plugin_widget_styling_settings) {
		case "none":
			?>
			<style type="text/css">
				#main-content .widgettitle {
					font-size: <?php if($divi_ultimate_plugin_widget_custom_header_settings) {
									echo $divi_ultimate_plugin_widget_header_size_settings;
								} else {
									echo '14';
								} ?>px!important;
					font-weight: <?php if($divi_ultimate_plugin_widget_custom_header_settings && !$divi_ultimate_plugin_widget_header_bold_settings) {
									echo 'normal';
								} else {
									echo '700';
								} ?>!important;
					color: <?php if(empty($divi_ultimate_plugin_widget_header_color_settings)) {
									echo '#ffd200';
								} else {
									echo $divi_ultimate_plugin_widget_header_color_settings;
								} ?>!important;
					background: <?php if(empty($divi_ultimate_plugin_widget_main_color_settings)) {
									echo '#151515';
								} else {
									echo $divi_ultimate_plugin_widget_main_color_settings;
								} ?>!important;
					text-transform: <?php if($divi_ultimate_plugin_widget_custom_header_settings && !$divi_ultimate_plugin_widget_header_uppercase_settings) {
									echo 'none';
								} else {
									echo 'uppercase';
								} ?>!important;
				}
				#main-content .et_pb_widget li.cat-item a:before {
					border-color: <?php if(empty($divi_ultimate_plugin_widget_main_color_settings)) {
									echo '#ffd200';
								} else {
									echo $divi_ultimate_plugin_widget_main_color_settings;
								} ?>!important;
				}		
				<?php if(!empty($divi_ultimate_plugin_widget_text_color_settings)) {
					echo '#main-content .et_pb_widget, #main-content .et_pb_widget a { color:' . $divi_ultimate_plugin_widget_text_color_settings . '!important; }';
					echo '#main-content .et_pb_widget a:hover { opacity: 0.65!important; }';
				} ?>			
			</style>
			<?php
			break;
		case "free-sidebar-style-1":
			?>
			<style type="text/css">
				#main-content .widgettitle, #main-content .free-sidebar .free-header h2 {
					font-size: <?php if($divi_ultimate_plugin_widget_custom_header_settings) {
									echo $divi_ultimate_plugin_widget_header_size_settings;
								} else {
									echo '18';
								} ?>px!important;
					color: <?php if(empty($divi_ultimate_plugin_widget_header_color_settings)) {
									echo '#333';
								} else {
									echo $divi_ultimate_plugin_widget_header_color_settings;
								} ?>!important;
					font-weight: <?php if($divi_ultimate_plugin_widget_custom_header_settings && !$divi_ultimate_plugin_widget_header_bold_settings) {
									echo 'normal';
								} else {
									echo '700';
								} ?>!important;
					text-transform: <?php if($divi_ultimate_plugin_widget_custom_header_settings && !$divi_ultimate_plugin_widget_header_uppercase_settings) {
									echo 'none';
								} else {
									echo 'uppercase';
								} ?>!important;
				}
				#main-content .widgettitle:after, #main-content .free-sidebar .free-header:after {			
					background: <?php if(empty($divi_ultimate_plugin_widget_main_color_settings)) {
									echo '#ec0e0e';
								} else {
									echo $divi_ultimate_plugin_widget_main_color_settings;
								} ?>!important;
				}
				#main-content .et_pb_widget li.cat-item a:before {
					border-color: <?php if(empty($divi_ultimate_plugin_widget_main_color_settings)) {
									echo '#ec0e0e';
								} else {
									echo $divi_ultimate_plugin_widget_main_color_settings;
								} ?>!important;
				}	
				<?php if(!empty($divi_ultimate_plugin_widget_text_color_settings)) {
					echo '#main-content .et_pb_widget, #main-content .et_pb_widget a { color:' . $divi_ultimate_plugin_widget_text_color_settings . '!important; }';
					echo '#main-content .et_pb_widget a:hover { opacity: 0.65!important; }';
				} ?>					
			</style>
			<?php
			break;
		case "free-sidebar-style-2":
			?>
			<style type="text/css">
				#main-content .widgettitle, #main-content .free-sidebar .free-header h2 {
					font-size: <?php if($divi_ultimate_plugin_widget_custom_header_settings) {
									echo $divi_ultimate_plugin_widget_header_size_settings;
								} else {
									echo '16';
								} ?>px!important;
					color: <?php if(empty($divi_ultimate_plugin_widget_header_color_settings)) {
									echo '#151515';
								} else {
									echo $divi_ultimate_plugin_widget_header_color_settings;
								} ?>!important;
					background: <?php if(empty($divi_ultimate_plugin_widget_main_color_settings)) {
									echo '#ffd200';
								} else {
									echo $divi_ultimate_plugin_widget_main_color_settings;
								} ?>!important;
					font-weight: <?php if($divi_ultimate_plugin_widget_custom_header_settings && !$divi_ultimate_plugin_widget_header_bold_settings) {
									echo 'normal';
								} else {
									echo '700';
								} ?>!important;
					text-transform: <?php if($divi_ultimate_plugin_widget_custom_header_settings && !$divi_ultimate_plugin_widget_header_uppercase_settings) {
									echo 'none';
								} else {
									echo 'uppercase';
								} ?>!important;
				}
				#main-content .et_pb_widget li.cat-item a:before {
					border-color: <?php if(empty($divi_ultimate_plugin_widget_main_color_settings)) {
									echo '#ffd200';
								} else {
									echo $divi_ultimate_plugin_widget_main_color_settings;
								} ?>!important;
				}
				<?php if(!empty($divi_ultimate_plugin_widget_text_color_settings)) {
					echo '#main-content .et_pb_widget, #main-content .et_pb_widget a { color:' . $divi_ultimate_plugin_widget_text_color_settings . '!important; }';
					echo '#main-content .et_pb_widget a:hover { opacity: 0.65!important; }';
				} ?>	
			</style>
			<?php
			break;
	} ?>
	
	<style type="text/css"> <?php
	
		// Header border color
		if(!empty($divi_ultimate_plugin_global_heading_border_color_settings)) { ?>
				#main-content .free-header-border-bottom { background-color: <?php echo $divi_ultimate_plugin_global_heading_border_color_settings; ?>!important; }
			<?php
		}
		
		// Header small color
		if(!empty($divi_ultimate_plugin_global_heading_small_color_settings)) { ?>
				.free-header-small h2 { color: <?php echo $divi_ultimate_plugin_global_heading_small_color_settings; ?>!important; }
			<?php
		}
		
		// Header top text color
		if(!empty($divi_ultimate_plugin_global_heading_top_text_color_settings)) { ?>
				.free-header-top-text { color: <?php echo $divi_ultimate_plugin_global_heading_top_text_color_settings; ?>!important; }
			<?php
		}
		
		// Testimonial slider modern hover button background color
		if(!empty($divi_ultimate_plugin_global_testimonial_slider_button_color_settings)) { ?>
				#main-content .free-testimonial-slider-modern .et-pb-slider-arrows a:hover { background-color: <?php echo $divi_ultimate_plugin_global_testimonial_slider_button_color_settings; ?>!important; }
			<?php
		}
		
		// Custom header & navigation css
		if ($divi_ultimate_plugin_header_styling_settings != 'none' && empty($show_default_divi_header)) { ?>
				@media screen and (min-width: 981px) {
					#main-header, #top-header { display: none!important; }
					#page-container { padding-top: 0!important; margin-top: 0!important; }
				}
			<?php
		}
		
		// Hide custom header & navigation css (page / post setting)
		if (!empty($hide_custom_header_navigation)) { ?>
				.free-du-plugin-header { display: none!important; }
			<?php
		}
		
		// Custom footer css
		if ($divi_ultimate_plugin_footer_styling_settings != 'none') { ?>
				#main-footer { display: none!important; }
			<?php
		}
		
		// Cart background color
		if(!empty($divi_ultimate_plugin_header_cart_background_color)) { ?>
				.free-cart-total { background: <?php echo $divi_ultimate_plugin_header_cart_background_color; ?>!important; }
			<?php
		}
		
		// Cart total color
		if(!empty($divi_ultimate_plugin_header_cart_total_color)) { ?>
				.free-cart-total { color: <?php echo $divi_ultimate_plugin_header_cart_total_color; ?>!important; }
			<?php
		}	
		
		// Menu hover main color & text color
		switch ($divi_ultimate_plugin_header_menu_hover_style) {
			case "none":
				break;
			case "free-menu-hover-1":
				// Menu hover main color
				if(!empty($divi_ultimate_plugin_header_menu_hover_main_color)) { ?>
						.free-menu-hover-1 .free-header-menu nav>ul>li:after { background-color: <?php echo $divi_ultimate_plugin_header_menu_hover_main_color; ?>!important; }
					<?php
				}	
				
				// Menu hover text color
				if(!empty($divi_ultimate_plugin_header_menu_hover_text_color)) { ?>
						.free-menu-hover-1 .free-header-menu nav>ul>li>a:hover { color: <?php echo $divi_ultimate_plugin_header_menu_hover_text_color; ?>!important; opacity: 1!important; }
					<?php
				}	
				break;
			case "free-menu-hover-2":
				// Menu hover main color
				if(!empty($divi_ultimate_plugin_header_menu_hover_main_color)) { ?>
						.free-menu-hover-2 .free-header-menu nav>ul>li:before { background-color: <?php echo $divi_ultimate_plugin_header_menu_hover_main_color; ?>!important; }
					<?php
				}	
				
				// Menu hover text color
				if(!empty($divi_ultimate_plugin_header_menu_hover_text_color)) { ?>
						.free-menu-hover-2 .free-header-menu nav>ul>li:hover>a { color: <?php echo $divi_ultimate_plugin_header_menu_hover_text_color; ?>!important; opacity: 1!important; }
					<?php
				}	
				break;
			case "free-menu-hover-3":
				// Menu hover main color
				if(!empty($divi_ultimate_plugin_header_menu_hover_main_color)) { ?>
						.free-menu-hover-3 .free-header-menu nav>ul>li:hover { background-color: <?php echo $divi_ultimate_plugin_header_menu_hover_main_color; ?>; }
					<?php
				}	
				
				// Menu hover text color
				if(!empty($divi_ultimate_plugin_header_menu_hover_text_color)) { ?>
						.free-menu-hover-3 .free-header-menu nav>ul>li:hover>a { color: <?php echo $divi_ultimate_plugin_header_menu_hover_text_color; ?>!important; opacity: 1!important; }
					<?php
				}	
				break;
		}
			
		// Search text color
		if(!empty($divi_ultimate_plugin_header_search_text_color)) { ?>
			.et_pb_section .free-search-outer .et-search-form input {
				color: <?php echo $divi_ultimate_plugin_header_search_text_color; ?>!important;
			}
			.et_pb_section .free-search-outer .et-search-form input::-webkit-input-placeholder {
				color: <?php echo $divi_ultimate_plugin_header_search_text_color; ?>!important;
			}
			.et_pb_section .free-search-outer .et-search-form input:-moz-placeholder { /* Firefox 18- */
				color: <?php echo $divi_ultimate_plugin_header_search_text_color; ?>!important;
			}
			.et_pb_section .free-search-outer .et-search-form input::-moz-placeholder {  /* Firefox 19+ */
				color: <?php echo $divi_ultimate_plugin_header_search_text_color; ?>!important;
			}
			.et_pb_section .free-search-outer .et-search-form input:-ms-input-placeholder {  
				color: <?php echo $divi_ultimate_plugin_header_search_text_color; ?>!important;
			}
			.et_pb_section span.free-search-close:after {
				color: <?php echo $divi_ultimate_plugin_header_search_text_color; ?>!important;
			}
			<?php
		} 
		
		// Related posts border radius
		if(!empty($divi_ultimate_plugin_blog_post_related_posts_border_radius)) { ?>
			.free-du-blog-1 .free-blog-related-posts .et_pb_post {
				border-radius: <?php echo $divi_ultimate_plugin_blog_post_related_posts_border_radius; ?>px!important;
			}
			<?php
		}
		
		// Blog header custom
		if(!empty($divi_ultimate_plugin_blog_post_header_custom_background_color)) { ?>
			.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header {
				background: <?php echo $divi_ultimate_plugin_blog_post_header_custom_background_color; ?>!important;
			}
			<?php
		}
		
		if(!empty($divi_ultimate_plugin_blog_post_header_custom_title_color)) { ?>
			.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content .entry-title {
				color: <?php echo $divi_ultimate_plugin_blog_post_header_custom_title_color; ?>!important;
			}
			<?php
		}
		
		if(!empty($divi_ultimate_plugin_blog_post_header_custom_meta_color)) { ?>
			.free-blog-post-header-custom #main-content.free-du-blog-1 .free-blog-post-header-content .post-meta, .free-blog-post-header-custom #main-content.free-du-blog-1 .free-blog-post-header-content .post-meta a {
				color: <?php echo $divi_ultimate_plugin_blog_post_header_custom_meta_color; ?>!important;
			}
			<?php
		}
		
		?>
		
		.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content {
			padding-top: <?php echo $divi_ultimate_plugin_blog_post_header_custom_padding_top; ?>px!important;
			padding-bottom: <?php echo $divi_ultimate_plugin_blog_post_header_custom_padding_bottom; ?>px!important;
			max-width: <?php echo $divi_ultimate_plugin_blog_post_header_custom_width; ?>px!important;
		}
		.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content .entry-title {
			text-transform: <?php if($divi_ultimate_plugin_blog_post_header_custom_title_uppercase) { ?>uppercase<?php } else { ?>none<?php } ?>!important;
			font-weight: <?php echo $divi_ultimate_plugin_blog_post_header_custom_title_weight; ?>!important;
		}
		.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content .post-meta {
			text-transform: <?php if($divi_ultimate_plugin_blog_post_header_custom_meta_uppercase) { ?>uppercase<?php } else { ?>none<?php } ?>!important;
		}
		.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-featured {
			filter: blur(<?php echo $divi_ultimate_plugin_blog_post_header_custom_blur; ?>px)!important;
			-webkit-filter: blur(<?php echo $divi_ultimate_plugin_blog_post_header_custom_blur; ?>px)!important;
		}
		.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-featured-scale {
			transform: scale(1.<?php echo $divi_ultimate_plugin_blog_post_header_custom_scale; ?>)!important;
		}
		
		@media screen and (min-width: 622px) {
			.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content .entry-title {
				font-size: <?php echo $divi_ultimate_plugin_blog_post_header_custom_title_size; ?>px!important;
			}
		}
		
		
		<?php				
		// Blog featured image  ?>
		.free-blog-post-featured-image-custom .free-du-blog-1 .free-blog-post-featured>* {
			margin-top: -<?php echo $divi_ultimate_plugin_blog_post_featured_image_custom_offset; ?>px!important;
			border: <?php echo $divi_ultimate_plugin_blog_post_featured_image_custom_border_width; ?>px solid #FFF!important;
		}
		
		<?php
		// Blog related posts background color
		if(!empty($divi_ultimate_plugin_blog_post_related_posts_background_color)) { ?>
			.free-du-blog-1 .free-blog-related-posts-background-color {
				background: <?php echo $divi_ultimate_plugin_blog_post_related_posts_background_color; ?>!important;
			}
			<?php
		}
						
		// Blog related posts title color
		if(!empty($divi_ultimate_plugin_blog_post_related_posts_title_color)) { ?>
			.free-du-blog-1 .free-blog-related-posts .free-blog-related-posts-title h2 {
				color: <?php echo $divi_ultimate_plugin_blog_post_related_posts_title_color; ?>!important;
			}
			<?php
		} 
		
		// Footer reveal box shadow
		if($divi_ultimate_plugin_footer_reveal && $divi_ultimate_plugin_footer_styling_settings != 'none') { ?>
			#main-content {
				box-shadow: rgba(0, 0, 0, <?php echo $divi_ultimate_plugin_footer_reveal_shadow_opacity / 100; ?>) 0px <?php echo $divi_ultimate_plugin_footer_reveal_shadow_verical - 20; ?>px <?php echo $divi_ultimate_plugin_footer_reveal_shadow_blur; ?>px <?php echo $divi_ultimate_plugin_footer_reveal_shadow_spread - 50; ?>px!important;
			}
			<?php
		} 
	
		// Footer reveal main background color bug
		if($divi_ultimate_plugin_footer_reveal && $divi_ultimate_plugin_footer_styling_settings != 'none') { ?>
			#page-container #main-content {
				background-color: #<?php 
				
				if ( isset( $divi_ultimate_plugin_footer_bug_main_background_color['background_color'] ) ) {
					if ( !empty( $divi_ultimate_plugin_footer_bug_main_background_color['background_color'] ) ) {
						echo $divi_ultimate_plugin_footer_bug_main_background_color['background_color'];
					} else {
						echo 'ffffff';
					}
				} else {
					echo 'ffffff';
				}
				?>;
			}
			<?php
		} ?>
		
	</style> <?php
}
// ------------------ CSS Edit End ----------------------

// ------------------ Add Search & Cart Start ----------------------
function divi_ultimate_plugin_menu_add_search_cart( $items, $args ) {
	$divi_ultimate_plugin_header_show_cart = get_option( 'divi_ultimate_plugin_header_show_cart' );
	$divi_ultimate_plugin_header_show_search = get_option( 'divi_ultimate_plugin_header_show_search' );
	if (strpos($args->menu_class, 'fullwidth-menu') !== false) {
		if ($divi_ultimate_plugin_header_show_cart) {
			if ( class_exists( 'woocommerce' ) ) {
				$items .= '<li class="free-cart-menu">' . du_show_cart_total() . '</li>';
			}
		}
		if ($divi_ultimate_plugin_header_show_search) {
			$du_search_outer = 	sprintf( '<input type="search" class="et-search-field" placeholder="%1$s" value="%2$s" name="s" title="%3$s" />',
									esc_attr__( 'Search &hellip;', 'Divi' ),
									get_search_query(),
									esc_attr__( 'Search for:', 'Divi' )
								);
			$du_search_action = esc_url( home_url( '/' ) );
			$items .= 	'<li class="free-search-menu">
							<a class="free-search-icon-link" href="#">
								<span class="free-search-icon"></span>
							</a>
						</li>
						<li class="free-search-container">
							<div class="free-search-outer">
								<form role="search" method="get" class="et-search-form" action="' . $du_search_action . '">' .	$du_search_outer . '
								</form>
								<span class="free-search-close"></span>
							</div>
						</li>';
		}

	}
	return $items;
}
// ------------------ Add Search & Cart End ----------------------

// ------------------ Show Cart Function Start ---------------------
if ( ! function_exists( 'du_show_cart_total' ) ) {
	function du_show_cart_total( $args = array() ) {
		if ( ! class_exists( 'woocommerce' ) || ! WC()->cart ) {
			return;
		}

		$defaults = array(
			'no_text' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		$items_number = WC()->cart->get_cart_contents_count();

		$du_cart = sprintf (
			'<a href="%1$s" class="et-cart-info">
				<span><sup class="free-cart-total">%2$s</sup></span>
			</a>',
			esc_url( WC()->cart->get_cart_url() ),
			( ! $args['no_text']
				? esc_html( sprintf(
					_nx( '%1$s', '%1$s', $items_number, 'WooCommerce items number', 'Divi' ),
					number_format_i18n( $items_number )
				) )
				: ''
			)
		);
		return $du_cart;
	}
}
// ------------------ Show Cart Function End ---------------------
?>