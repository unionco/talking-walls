<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );
$divi_ultimate_plugin_blog_post_header_parallax = get_option( 'divi_ultimate_plugin_blog_post_header_parallax', 'none' );
$divi_ultimate_plugin_blog_post_related_posts_number = get_option( 'divi_ultimate_plugin_blog_post_related_posts_number', '3' );
$divi_ultimate_plugin_blog_post_related_posts_gutter = get_option( 'divi_ultimate_plugin_blog_post_related_posts_gutter', '2' );
$divi_ultimate_plugin_blog_post_related_posts_style = get_option( 'divi_ultimate_plugin_blog_post_related_posts_style', 'free-blog-post-related-posts-hide' );
$divi_ultimate_plugin_blog_post_header_custom = get_option( 'divi_ultimate_plugin_blog_post_header_custom');
$divi_ultimate_plugin_blog_post_header_custom_overlay = get_option( 'divi_ultimate_plugin_blog_post_header_custom_overlay', 'free-blog-post-header-featured-overlay-none' );
$divi_ultimate_plugin_blog_post_related_posts_title = get_option( 'divi_ultimate_plugin_blog_post_related_posts_title', 'Related Posts' );
$divi_ultimate_plugin_blog_post_navigation_previous_text = get_option( 'divi_ultimate_plugin_blog_post_navigation_previous_text', 'Previous' );
$divi_ultimate_plugin_blog_post_navigation_next_text = get_option( 'divi_ultimate_plugin_blog_post_navigation_next_text', 'Next' );
?>

<div id="main-content" class="free-du-blog-1">
	<?php
		if ( et_builder_is_product_tour_enabled() ):
			// load fullwidth page in Product Tour mode
			while ( have_posts() ): the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content">
					<?php
						the_content();
					?>
					</div> <!-- .entry-content -->

				</article> <!-- .et_pb_post -->

		<?php endwhile;
		else:
	?>
	
	<?php if ( ( 'off' !== $show_default_title && $is_page_builder_used ) || ! $is_page_builder_used ) {  ?>
		
		<div class="free-blog-post-header et_pb_section et_pb_section_parallax">
		
			<div class="container"> 
				<div class="et_pb_row">
					<div class="free-blog-post-header-content">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<?php et_divi_post_meta();?>
					</div>
				</div>
			</div>
			
			<?php if ( has_post_thumbnail() ) { ?>
				<div class="free-blog-post-header-featured-wrapper <?php echo $divi_ultimate_plugin_blog_post_header_custom_overlay; ?> free-background-overlay">
					<div class="free-blog-post-header-featured-scale">
						<div class="
							<?php if ( $divi_ultimate_plugin_blog_post_header_parallax == 'parallax' ) { ?>
								et_parallax_bg 
							<?php } elseif ($divi_ultimate_plugin_blog_post_header_parallax == 'css') { ?>
								et_parallax_bg et_pb_parallax_css 
							<?php } ?>free-blog-post-header-featured" style="background-image: url(<?php echo get_the_post_thumbnail_url() ;?>) ">
						</div>
					</div>
				</div>
			<?php }; ?>
			
		</div>
			
	 <?php }; ?>
	
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if (et_get_option('divi_integration_single_top') <> '' && et_get_option('divi_integrate_singletop_enable') == 'on') echo(et_get_option('divi_integration_single_top')); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
					<?php if ( ( 'off' !== $show_default_title && $is_page_builder_used ) || ! $is_page_builder_used ) { ?>
						<div class="et_post_meta_wrapper free-blog-post-featured">

						<?php
							if ( ! post_password_required() ) :

								$thumb = '';

								$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

								$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
								$classtext = 'et_featured_image';
								$titletext = get_the_title();
								$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
								$thumb = $thumbnail["thumb"];

								$post_format = et_pb_post_format();

								if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) {
									printf(
										'<div class="et_main_video_container">
											%1$s
										</div>',
										$first_video
									);
								} else if ( ! in_array( $post_format, array( 'gallery', 'link', 'quote' ) ) && 'on' === et_get_option( 'divi_thumbnails', 'on' ) && '' !== $thumb ) {
									print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
								} else if ( 'gallery' === $post_format ) {
									et_pb_gallery_images();
								}
							?>

							<?php
								$text_color_class = et_divi_get_post_text_color();

								$inline_style = et_divi_get_post_bg_inline_style();

								switch ( $post_format ) {
									case 'audio' :
										$audio_player = et_pb_get_audio_player();

										if ( $audio_player ) {
											printf(
												'<div class="et_audio_content%1$s"%2$s>
													%3$s
												</div>',
												esc_attr( $text_color_class ),
												$inline_style,
												$audio_player
											);
										}

										break;
									case 'quote' :
										printf(
											'<div class="et_quote_content%2$s"%3$s>
												%1$s
											</div> <!-- .et_quote_content -->',
											et_get_blockquote_in_content(),
											esc_attr( $text_color_class ),
											$inline_style
										);

										break;
									case 'link' :
										printf(
											'<div class="et_link_content%3$s"%4$s>
												<a href="%1$s" class="et_link_main_url">%2$s</a>
											</div> <!-- .et_link_content -->',
											esc_url( et_get_link_url() ),
											esc_html( et_get_link_url() ),
											esc_attr( $text_color_class ),
											$inline_style
										);

										break;
								}

							endif;
						?>
					</div> <!-- .et_post_meta_wrapper -->
				<?php  } ?>

					<div class="entry-content">
					<?php
						do_action( 'et_before_content' );

						the_content();

						wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->
					<div class="et_post_meta_wrapper">
					<?php
					if ( et_get_option('divi_468_enable') == 'on' ){
						echo '<div class="et-single-post-ad">';
						if ( et_get_option('divi_468_adsense') <> '' ) echo( et_get_option('divi_468_adsense') );
						else { ?>
							<a href="<?php echo esc_url(et_get_option('divi_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('divi_468_image')); ?>" alt="468" class="foursixeight" /></a>
				<?php 	}
						echo '</div> <!-- .et-single-post-ad -->';
					}
				?>

					<?php if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); ?>

					</div> <!-- .et_post_meta_wrapper -->
				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
	
	<!-- Post Navigation -->
	<div class="free-blog-post-navigation-background-color">
		<div class="container free-blog-post-navigation"> 
			<div class="et_pb_row free-blog-post-navigation-container">
				<div class="free-blog-post-navigation-prev"><?php previous_post_link('<h4>' . $divi_ultimate_plugin_blog_post_navigation_previous_text .'</h4><div class="post-navigation-previous">%link', '%title</div>'); ?></div>
				<div class="free-blog-post-navigation-next"><?php next_post_link('<h4>' . $divi_ultimate_plugin_blog_post_navigation_next_text .'</h4><div class="post-navigation-next">%link', '%title</div>'); ?></div>
			</div>
		</div>
	</div>

	<!-- Related Posts --> 
	<?php 
	$orig_post = $post;
	global $post; 
	$categories = get_the_category($post->ID);
	if ($categories) {
	$category_filter = array();
	foreach($categories as $category) $category_filter[] = $category->term_id;
	$args=array(
		'category__in' => $category_filter,
		'post__not_in' => array($post->ID),
		'posts_per_page' => $divi_ultimate_plugin_blog_post_related_posts_number,
		'ignore_sticky_posts'=> 1
	);
	$related_posts_query = new wp_query( $args );
	if( $related_posts_query->have_posts() ) {
	echo '<div class="free-blog-related-posts-background-color"><div class="container free-blog-related-posts"><div class="et_pb_row free-blog-related-posts-title"><h2>' . $divi_ultimate_plugin_blog_post_related_posts_title .'</h2></div><div class="et_pb_row free-hover free-blog-related-posts-container ' . $divi_ultimate_plugin_blog_post_related_posts_style . ' et_pb_gutters' . $divi_ultimate_plugin_blog_post_related_posts_gutter . '">';
	while( $related_posts_query->have_posts() ) {
	$related_posts_query->the_post();?>
		<div class="et_pb_column et_pb_column_1_3 et_pb_blog_grid">
			<div class="et_pb_post">
				<?php if ( has_post_thumbnail() ) { ?>
					<div class="et_pb_image_container">
						<a class="entry-featured-image-url" rel="external" href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail(array(400,250)); ?>
						</a>
					</div>
				<?php } ?>
				<h3 class="entry-title">
					<a rel="external" href="<?php the_permalink(); ?>">
						<?php echo $post->post_title; ?>
					</a>
				</h3>
				<p class="post-meta">
					<span class="published">
						<?php the_time('M j, Y') ?>
					</span>
				</p>
			</div>
		</div>
	<?php
	}
	echo '</div></div></div>';
	}
	}
	$post = $orig_post;
	wp_reset_query(); ?> 
	
	<!-- Blog Comments -->
	<?php
		if ( ( comments_open() || get_comments_number() ) && 'on' == et_get_option( 'divi_show_postcomments', 'on' ) ) {
			echo '<div class="free-blog-comment-background-color"><div class="container free-blog-comment-container"><div class="et_pb_row">';
			comments_template( '', true );
			echo '</div></div></div>';
		}
	?>
	
	<?php endif; ?>
</div> <!-- #main-content -->

<?php get_footer(); ?>
