<?php
/*
Template Name: Wallmap Plugin
*/

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

$projectJson = [];
$projects = [];

$args = [
    'post_type'      => 'project',
    'orderby'        => 'title',
    'order'          => 'ASC',
    'posts_per_page' => -1,
];
$query = new WP_Query($args);

if ($query && count($query->posts) > 0) {
    foreach ($query->posts as $project) {
        array_push($projects, $project);
        array_push($projectJson, [
            "id" => $project->ID,
            "title" => $project->post_title,
            "location" => get_field('wall_map', $project->ID),
            "image" => get_the_post_thumbnail_url($project->ID)
        ]);
    }
}

?>

<div id="main-content">
	<div class="murals" data-murals='<?php echo json_encode($projectJson); ?>'>
		<div class="murals-list" data-murals-list>
			<?php
				foreach ($projects as $project) {
					$title = $project->post_title;
					$location = get_field('wall_location', $project->ID);
					$featuredImage = get_the_post_thumbnail_url($project->ID);
					echo '<div class="murals-list-item">
						<div class="murals-list-item-image">
							<img src="' . $featuredImage . '" />
							<div class="murals-list-item-tag"><span></span> 2019</div>
						</div>
						<div class="murals-list-item-info">
							<h3>' . $title . '</h3>
							<p>' . ($location ? $location['address'] : 'No Location Set') . '</p>
							<p><a href="/">Artist Info</a> <a href="/">Get Directions</a></p>
						</div>
					</div>';
				}
			?>
		</div>
		<div class="murals-map"  data-murals-map></div>
	</div>
		
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area"></div> <!-- #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->

	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-content">
			<?php
				the_content();

				if ( ! $is_page_builder_used )
					wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
			?>
			</div> <!-- .entry-content -->

		<?php
			if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
		?>

		</article> <!-- .et_pb_post -->

	<?php endwhile; ?>

</div> <!-- #main-content -->

<?php


get_footer();
