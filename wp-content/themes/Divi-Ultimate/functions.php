<?php 

add_action( 'wp_enqueue_scripts', 'divi_ultimate_enqueue_assets' ); 
add_action( 'wp_enqueue_scripts', 'divi_ultimate_enqueue_child_theme_assests', 25 ); // delay child theme css

function divi_ultimate_enqueue_assets() { 
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' ); 
} 

function divi_ultimate_enqueue_child_theme_assests() { 
	wp_dequeue_style( 'divi-style' );
	wp_enqueue_style( 'child-theme', get_stylesheet_uri(), array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
} 

function custom_post_name () {
return array(
'feeds' => true,
'slug' => 'artists',
'with_front' => false,
);
}
add_filter( 'et_project_posttype_rewrite_args', 'custom_post_name' );
?>