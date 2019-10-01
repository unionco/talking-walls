<?php
/*
Template Name: Wallmap Plugin
*/

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used(get_the_ID());

$entry = get_post();
$currentYear = get_field('current_year', $entry->ID);
$color = get_field('color', $entry->ID);

$muralJson = [];
$murals = [];

$args = [
	'post_type'      => 'murals',
	'orderby'        => 'title',
	'order'          => 'ASC',
	'posts_per_page' => -1,
];
$query = new WP_Query($args);

if ($query && count($query->posts) > 0) {
	foreach ($query->posts as $mural) {
		// json version for js
		$year = get_the_terms($mural->ID, 'murals-year')[0];

		array_push($muralJson, [
			"ID" => $mural->ID,
			"title" => $mural->post_title,
			"business" => get_field('business_name', $mural->ID),
			"address" => get_field('display_address', $mural->ID),
			"location" => get_field('location', $mural->ID),
			"image" => get_the_post_thumbnail_url($mural->ID, 'medium_large'),
			"year" => $year,
			"isCurrent" => $currentYear === $year->name,
			"currentColor" => $color
		]);
	}
}

?>

<div id="main-content">

	<?php if (!$is_page_builder_used) : ?>

		<div class="container">
			<div id="content-area" class="clearfix">
				<div id="left-area">

				<?php endif; ?>

				<?php while (have_posts()) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php if (!$is_page_builder_used) : ?>

							<h1 class="entry-title main_title"><?php the_title(); ?></h1>
							<?php
									$thumb = '';

									$width = (int) apply_filters('et_pb_index_blog_image_width', 1080);

									$height = (int) apply_filters('et_pb_index_blog_image_height', 675);
									$classtext = 'et_featured_image';
									$titletext = get_the_title();
									$thumbnail = get_thumbnail($width, $height, $classtext, $titletext, $titletext, false, 'Blogimage');
									$thumb = $thumbnail["thumb"];

									if ('on' === et_get_option('divi_page_thumbnails', 'false') && '' !== $thumb)
										print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height);
									?>
						<?php endif; ?>

						<div class="murals" data-murals='<?php echo json_encode($muralJson); ?>'>
							<div class="murals-list" data-murals-list>
								<div class="murals-legend">
									<div class="murals-legend-item is-current"><span></span> 2019 Murals</div>
									<div class="murals-legend-item"><span></span> Past Murals</div>
								</div>
								<?php
									foreach ($muralJson as $mural) {
										$mural = (object) $mural;
										$title = $mural->title;
										$location = $mural->location;
										$displayAddress = $mural->address;
										$featuredImage = $mural->image;
										$business = $mural->business;
										$artistHasPage = (bool) get_field('artist_has_page', $mural->ID);
										$directions = "https://www.google.com/maps/dir//" . $location['address'];
										$year = $mural->year;

										if ($artistHasPage) {
											$relatedArtist = get_field('related_artist', $mural->ID);
										} else {
											$artistName = get_field('artistName', $mural->ID);
										}

										echo '<div class="murals-list-item">
											<div class="murals-list-item-inner" data-murals-list-item="' . $mural->ID . '">
												<div class="murals-list-item-image">
													<img src="' . $featuredImage . '" />
													<div class="murals-list-item-tag" data-year="' . $year->slug . '"><span></span> ' . $year->name . '</div>
												</div>';

										if ($artistHasPage) {
											$relatedArtist = get_field('related_artist', $mural->ID);
										} else {
											$artistName = get_field('artistName', $mural->ID);
										}

										echo '<div class="murals-list-item">
												<div class="murals-list-item-inner" data-murals-list-item="' . $mural->ID . '">
													<div class="murals-list-item-image">
														<img src="' . $featuredImage . '" />
														<div class="murals-list-item-tag" data-year="' . $year->slug . '"><span></span> ' . $year->name . '</div>
													</div>';

										if ($artistHasPage) {
											echo '<div class="murals-list-item-info">
														<h3>' . $relatedArtist->post_title . '</h3>
														<p>' . $business . '</p>
														<p>' . str_replace("\n", "<br/>", $displayAddress) . '</p>
														<p><a href="' . get_permalink($relatedArtist->ID) . '">Artist Info</a> <a href="' . $directions . '">Get Directions</a></p>
													</div>';
										} else {
											echo '<div class="murals-list-item-info">
														<h3>' . $artistName ?? $business . '</h3>
														<p>' . str_replace("\n", "<br/>", $displayAddress) . '</p>
														<p><a href="' . $directions . '">Get Directions</a></p>
													</div>';
										}
										echo '</div>
											</div>';
									}
									?>
							</div>
						</div>
						<div class="murals-map" data-murals-map></div>
						<button class="murals-switch" data-murals-switch="Map View">
							<img class="murals-switch-list" src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'public/img/tw-list.svg'; ?>">
							<img class="murals-switch-map" src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'public/img/tw-map.svg'; ?>">
						</button>
				</div>

				<div class="entry-content">
					<?php
						the_content();

						if (!$is_page_builder_used)
							wp_link_pages(array('before' => '<div class="page-links">' . esc_html__('Pages:', 'Divi'), 'after' => '</div>'));
						?>
				</div> <!-- .entry-content -->

				<?php
					if (!$is_page_builder_used && comments_open() && 'on' === et_get_option('divi_show_pagescomments', 'false')) comments_template('', true);
					?>

				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>

			<?php if (!$is_page_builder_used) : ?>

			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
</div> <!-- .container -->

<?php endif; ?>

</div> <!-- #main-content -->

<?php

get_footer();
