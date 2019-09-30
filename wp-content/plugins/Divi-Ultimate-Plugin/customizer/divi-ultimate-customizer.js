(function($){
	
	function divi_ultimate_plugin_print_module_styles_css( id, type, selector, value, important ){
		// sanitize id into safe style's ID
		var style_id 		= id.replace(/[ +\/\[\]]/g,'_').toLowerCase(),
			$style 			= $('#' + style_id),
			$style_length 	= $style.length;

		// create DOM
		var style = $( '<style />', {
			id : style_id
		} );

		// Determine important tag
		if ( typeof important !== 'undefined' ){
			var important_tag = '!important';
		} else {
			var important_tag = '';
		}

		// append style into DOM
		switch( type ){
			case 'font-size':
				style.text( selector + "{ font-size: " + value + "px " + important_tag + ";}" );
				break;

			case 'color':
				style.text( selector + "{ color: " + value + " " + important_tag + ";}" );
				break;

			case 'background':
				style.text( selector + "{ background: " + value + " " + important_tag + ";}" );
				break;

			case 'border-radius':
				style.text( selector + " { -moz-border-radius: " + value + "px " + important_tag + "; -webkit-border-radius: " + value + "px " + important_tag + "; border-radius: " + value + "px " + important_tag + "; }" );
				break;

			case 'blur':
				style.text( selector + " { filter: blur(" + value + "px) " + important_tag + "; -webkit-filter: blur(" + value + "px) " + important_tag + ";}" );
				break;

			case 'scale':
				style.text( selector + "{ transform: scale(1." + value + ") " + important_tag + ";}" );
				break;

			case 'padding':
				style.text( selector + "{ padding: " + value + "px " + important_tag + ";}" );
				break;

			case 'padding-bottom':
				style.text( selector + "{ padding-bottom: " + value + "px " + important_tag + ";}" );
				break;
				
			case 'padding-top':
				style.text( selector + "{ padding-top: " + value + "px " + important_tag + ";}" );
				break;			
				
			case 'max-width':
				style.text( selector + "{ max-width: " + value + "px " + important_tag + ";}" );
				break;		
				
			case 'uppercase':
				style.text( selector + "{ text-transform: " + value + " " + important_tag + ";}" );
				break;	
								
			case 'font-weight':
				style.text( selector + "{ font-weight: " + value + " " + important_tag + ";}" );
				break;	
				
			case 'offset':
				style.text( selector + "{ margin-top: -" + value + "px " + important_tag + ";}" );
				break;		
				
			case 'featured-border-width':
				style.text( selector + "{ border: " + value + "px solid #FFF " + important_tag + ";}" );
				break;
		}

		// Insert custom styling
		if ( $style_length ) {
			$style.replaceWith( style );
		} else {
			$( 'head' ).append( style );
		}
	}
	
	wp.customize( 'divi_ultimate_plugin_blog_post_related_posts_border_radius', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_related_posts_border_radius',
				'border-radius',
				'.free-du-blog-1 .free-blog-related-posts .et_pb_post',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_blur', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_header_custom_blur',
				'blur',
				'.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-featured',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_scale', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_header_custom_scale',
				'scale',
				'.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-featured-scale',
				to,
				'important'
			);
		} );
	} );

	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_padding_top', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_header_custom_padding_top',
				'padding-top',
				'.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_padding_bottom', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_header_custom_padding_bottom',
				'padding-bottom',
				'.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_width', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_header_custom_width',
				'max-width',
				'.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_title_size', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_header_custom_title_size',
				'font-size',
				'.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content .entry-title',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_featured_image_custom_offset', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_featured_image_custom_offset',
				'offset',
				'.free-blog-post-featured-image-custom .free-du-blog-1 .free-blog-post-featured>*',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_featured_image_custom_border_width', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_featured_image_custom_border_width',
				'featured-border-width',
				'.free-blog-post-featured-image-custom .free-du-blog-1 .free-blog-post-featured>*',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_style', function( value ) {
		value.bind( function( to ) {
			$('body').removeClass( 'free-blog-post-style-2' );
			$('body').removeClass( 'free-blog-post-style-3' );
			$('body').removeClass( 'free-blog-post-style-4' );
			$('body').removeClass( 'free-blog-post-style-5' );
			if ( to != 'none'){
				$('body').addClass( to );
			}
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_navigation_style', function( value ) {
		value.bind( function( to ) {
			$('body').removeClass( 'free-blog-post-navigation-hide' );
			$('body').removeClass( 'free-blog-post-navigation-style-1' );
			$('body').addClass( to );
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_related_posts_style', function( value ) {
		value.bind( function( to ) {
			$('body').removeClass( 'free-blog-post-related-posts-hide' );
			$('.free-du-blog-1 .free-blog-related-posts-container').removeClass( 'free-blog-list-2' );
			$('.free-du-blog-1 .free-blog-related-posts-container').removeClass( 'free-blog-background-solid' );
			$('.free-du-blog-1 .free-blog-related-posts-container').removeClass( 'free-blog-all-center' );
			if ( to == 'free-blog-post-related-posts-hide'){
				$('body').addClass( to );
			} else {
				$('.free-du-blog-1 .free-blog-related-posts-container').addClass( to );
			}
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom', function( value ) {
		value.bind( function( to ) {
			if ( to ){
				$('body').addClass( 'free-blog-post-header-custom' );
			} else {
				$('body').removeClass( 'free-blog-post-header-custom' );
			}
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_hide', function( value ) {
		value.bind( function( to ) {
			if ( to ){
				$('body').addClass( 'free-blog-post-header-featured-hide' );
			} else {
				$('body').removeClass( 'free-blog-post-header-featured-hide' );
			}
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_overlay', function( value ) {
		value.bind( function( to ) {
			$('.free-du-blog-1 .free-blog-post-header-featured-wrapper').removeClass( 'free-blog-post-header-featured-overlay-none' );
			$('.free-du-blog-1 .free-blog-post-header-featured-wrapper').removeClass( 'free-blog-post-header-featured-overlay-solid' );
			$('.free-du-blog-1 .free-blog-post-header-featured-wrapper').removeClass( 'free-blog-post-header-featured-overlay-gradient' );
			$('.free-du-blog-1 .free-blog-post-header-featured-wrapper').addClass( to );
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_background_color', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_header_custom_background_color',
				'background',
				'.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_title_color', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_header_custom_title_color',
				'color',
				'.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content .entry-title',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_meta_color', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_header_custom_meta_color',
				'color',
				'.free-blog-post-header-custom #main-content.free-du-blog-1 .free-blog-post-header-content .post-meta, .free-blog-post-header-custom #main-content.free-du-blog-1 .free-blog-post-header-content .post-meta a',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_alignment', function( value ) {
		value.bind( function( to ) {
			$('body').removeClass( 'free-blog-post-header-content-center' );
			$('body').removeClass( 'free-blog-post-header-content-left' );
			$('body').removeClass( 'free-blog-post-header-content-right' );
			$('body').addClass( to );
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_title_uppercase', function( value ) {
		value.bind( function( to ) {
			if ( to ){
				divi_ultimate_plugin_print_module_styles_css(
					'divi_ultimate_plugin_blog_post_header_custom_title_uppercase',
					'uppercase',
					'.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content .entry-title',
					'uppercase',
					'important'
				);
			} else {
				divi_ultimate_plugin_print_module_styles_css(
					'divi_ultimate_plugin_blog_post_header_custom_title_uppercase',
					'uppercase',
					'.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content .entry-title',
					'none',
					'important'
				);
			}
		} );
	} );	
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_meta_uppercase', function( value ) {
		value.bind( function( to ) {
			if ( to ){
				divi_ultimate_plugin_print_module_styles_css(
					'divi_ultimate_plugin_blog_post_header_custom_meta_uppercase',
					'uppercase',
					'.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content .post-meta',
					'uppercase',
					'important'
				);
			} else {
				divi_ultimate_plugin_print_module_styles_css(
					'divi_ultimate_plugin_blog_post_header_custom_meta_uppercase',
					'uppercase',
					'.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content .post-meta',
					'none',
					'important'
				);
			}
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_header_custom_title_weight', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_header_custom_title_weight',
				'font-weight',
				'.free-blog-post-header-custom .free-du-blog-1 .free-blog-post-header-content .entry-title',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_featured_image_custom', function( value ) {
		value.bind( function( to ) {
			if ( to ){
				$('body').addClass( 'free-blog-post-featured-image-custom' );
			} else {
				$('body').removeClass( 'free-blog-post-featured-image-custom' );
			}
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_featured_image_custom_hide', function( value ) {
		value.bind( function( to ) {
			$('body').removeClass( 'free-blog-post-featured-image-hide' );
			$('body').removeClass( 'free-blog-post-featured-image-show' );
			if ( to ){
				$('body').addClass( 'free-blog-post-featured-image-hide' );
			} else {
				$('body').addClass( 'free-blog-post-featured-image-show' );
			}
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_featured_image_custom_box_shadow', function( value ) {
		value.bind( function( to ) {
			if ( to ){
				$('body').addClass( 'free-blog-post-featured-image-box-shadow' );
			} else {
				$('body').removeClass( 'free-blog-post-featured-image-box-shadow' );
			}
		} );
	} );	
	
	wp.customize( 'divi_ultimate_plugin_blog_post_sidebar_hide', function( value ) {
		value.bind( function( to ) {
			if ( to ){
				$('body').addClass( 'free-blog-post-sidebar-hide' );
			} else {
				$('body').removeClass( 'free-blog-post-sidebar-hide' );
			}
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_related_posts_background_color', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_related_posts_background_color',
				'background',
				'.free-du-blog-1 .free-blog-related-posts-background-color',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_related_posts_title_color', function( value ) {
		value.bind( function( to ) {
			divi_ultimate_plugin_print_module_styles_css(
				'divi_ultimate_plugin_blog_post_related_posts_title_color',
				'color',
				'.free-du-blog-1 .free-blog-related-posts .free-blog-related-posts-title h2',
				to,
				'important'
			);
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_related_posts_title', function( value ) {
		value.bind( function( to ) {

			var $related_posts_title = $( '.free-blog-related-posts-title h2' );

			$related_posts_title.show().html( to );
				
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_navigation_previous_text', function( value ) {
		value.bind( function( to ) {

			var $related_posts_title = $( '.free-blog-post-navigation-prev h4' );

			$related_posts_title.show().html( to );
				
		} );
	} );
	
	wp.customize( 'divi_ultimate_plugin_blog_post_navigation_next_text', function( value ) {
		value.bind( function( to ) {

			var $related_posts_title = $( '.free-blog-post-navigation-next h4' );

			$related_posts_title.show().html( to );
				
		} );
	} );
	
})(jQuery);