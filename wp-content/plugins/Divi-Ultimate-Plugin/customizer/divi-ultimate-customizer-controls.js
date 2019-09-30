(function($){
	$( document ).ready( function() {
	
		var $widget_custom_header_settings          		= $( '#customize-control-divi_ultimate_plugin_widget_custom_header_settings' ),
			$widget_custom_header_settings_input    		= $widget_custom_header_settings.find( 'input[type=checkbox]' ),
			$widget_header_size_settings           			= $( '#customize-control-divi_ultimate_plugin_widget_header_size_settings' ),
			$widget_header_bold_settings          			= $( '#customize-control-divi_ultimate_plugin_widget_header_bold_settings' ),
			$widget_header_uppercase_settings       		= $( '#customize-control-divi_ultimate_plugin_widget_header_uppercase_settings' ),
			
			$header_menu_hover_main_color       			= $( '#customize-control-divi_ultimate_plugin_header_menu_hover_main_color' ),
			$header_menu_hover_text_color      				= $( '#customize-control-divi_ultimate_plugin_header_menu_hover_text_color' ),
			$header_menu_hover_style   			    		= $( '#customize-control-divi_ultimate_plugin_header_menu_hover_style select' ),
			$header_show_search    							= $( '#customize-control-divi_ultimate_plugin_header_show_search' ),
			$header_show_search_input						= $header_show_search.find( 'input[type=checkbox]' ),
			$header_show_cart      							= $( '#customize-control-divi_ultimate_plugin_header_show_cart' ),
			$header_show_cart_input							= $header_show_cart.find( 'input[type=checkbox]' ),
			$header_search_text_color       				= $( '#customize-control-divi_ultimate_plugin_header_search_text_color' ),
			$header_cart_background_color       			= $( '#customize-control-divi_ultimate_plugin_header_cart_background_color' ),
			$header_cart_total_color       					= $( '#customize-control-divi_ultimate_plugin_header_cart_total_color' ),
			
			$blog_custom_blog								= $( '#customize-control-divi_ultimate_plugin_blog_post_enable' ),
			$blog_custom_blog_input							= $blog_custom_blog.find( 'input[type=checkbox]' ),
			$blog_custom_blog_header						= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom' ),
			$blog_custom_blog_header_input					= $blog_custom_blog_header.find( 'input[type=checkbox]' ),
			$blog_custom_blog_featured_image				= $( '#customize-control-divi_ultimate_plugin_blog_post_featured_image_custom' ),
			$blog_custom_blog_featured_image_input			= $blog_custom_blog_featured_image.find( 'input[type=checkbox]' ),
			
			$blog_custom_blog_header_style					= $( '#customize-control-divi_ultimate_plugin_blog_post_header_style' ),
			$blog_custom_blog_header_parallax				= $( '#customize-control-divi_ultimate_plugin_blog_post_header_parallax' ),
			$blog_custom_blog_post_navigation_style			= $( '#customize-control-divi_ultimate_plugin_blog_post_navigation_style' ),
			$blog_custom_blog_related_posts_style			= $( '#customize-control-divi_ultimate_plugin_blog_post_related_posts_style' ),
			$blog_custom_blog_related_posts_border_radius	= $( '#customize-control-divi_ultimate_plugin_blog_post_related_posts_border_radius' ),
			
			$blog_custom_blog_header_hide					= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_hide' ),
			$blog_custom_blog_header_overlay				= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_overlay' ),
			$blog_custom_blog_header_blur					= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_blur' ),
			$blog_custom_blog_header_scale					= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_scale' ),
			$blog_custom_blog_header_background_color		= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_background_color' ),
			$blog_custom_blog_header_title_color			= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_title_color' ),
			$blog_custom_blog_header_meta_color				= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_meta_color' ),
			$blog_custom_blog_header_padding_top			= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_padding_top' ),
			$blog_custom_blog_header_padding_bottom			= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_padding_bottom' ),
			$blog_custom_blog_header_alignment				= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_alignment' ),
			$blog_custom_blog_header_width					= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_width' ),
			$blog_custom_blog_header_title_uppercase		= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_title_uppercase' ),
			$blog_custom_blog_header_meta_uppercase			= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_meta_uppercase' ),
			$blog_custom_blog_header_title_size				= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_title_size' ),
			$blog_custom_blog_header_title_weight			= $( '#customize-control-divi_ultimate_plugin_blog_post_header_custom_title_weight' ),

			$blog_custom_blog_featured_image_hide			= $( '#customize-control-divi_ultimate_plugin_blog_post_featured_image_custom_hide' ),
			$blog_custom_blog_featured_image_box_shadow		= $( '#customize-control-divi_ultimate_plugin_blog_post_featured_image_custom_box_shadow' ),
			$blog_custom_blog_featured_image_offset			= $( '#customize-control-divi_ultimate_plugin_blog_post_featured_image_custom_offset' ),
			$blog_custom_blog_featured_image_border_width	= $( '#customize-control-divi_ultimate_plugin_blog_post_featured_image_custom_border_width' ),
			
			$footer_reveal									= $( '#customize-control-divi_ultimate_plugin_footer_reveal' ),
			$footer_reveal_input							= $footer_reveal.find( 'input[type=checkbox]' ),
			
			$footer_reveal_shadow_opacity					= $( '#customize-control-divi_ultimate_plugin_footer_reveal_shadow_opacity' ),
			$footer_reveal_shadow_verical					= $( '#customize-control-divi_ultimate_plugin_footer_reveal_shadow_verical' ),
			$footer_reveal_shadow_blur						= $( '#customize-control-divi_ultimate_plugin_footer_reveal_shadow_blur' ),
			$footer_reveal_shadow_spread					= $( '#customize-control-divi_ultimate_plugin_footer_reveal_shadow_spread' );
			
		if ( $widget_custom_header_settings_input.is( ':checked') ) {
			$widget_header_size_settings.show();
			$widget_header_bold_settings.show();
			$widget_header_uppercase_settings.show();
		} else {
			$widget_header_size_settings.hide();
			$widget_header_bold_settings.hide();
			$widget_header_uppercase_settings.hide();
		}

		if ( $header_show_search_input.is( ':checked') ) {
			$header_search_text_color.show();
		} else {
			$header_search_text_color.hide();
		}
		
		if ( $header_show_cart_input.is( ':checked') ) {
			$header_cart_background_color.show();
			$header_cart_total_color.show();
		} else {
			$header_cart_background_color.hide();
			$header_cart_total_color.hide();
		}
		
		if ( 'none' === $header_menu_hover_style.val() ) {
			$header_menu_hover_main_color.hide();
			$header_menu_hover_text_color.hide();
		} else {
			$header_menu_hover_main_color.show();
			$header_menu_hover_text_color.show();
		}
		
		if ( $blog_custom_blog_input.is( ':checked') ) {
			$blog_custom_blog_header_style.show();				
			$blog_custom_blog_header_parallax.show();					
			$blog_custom_blog_post_navigation_style.show();				
			$blog_custom_blog_related_posts_style.show();				
			$blog_custom_blog_related_posts_border_radius.show();		
		} else {
			$blog_custom_blog_header_style.hide();				
			$blog_custom_blog_header_parallax.hide();					
			$blog_custom_blog_post_navigation_style.hide();				
			$blog_custom_blog_related_posts_style.hide();				
			$blog_custom_blog_related_posts_border_radius.hide();				
		}
		
		if ( $blog_custom_blog_header_input.is( ':checked') ) {
			$blog_custom_blog_header_hide.show();			
			$blog_custom_blog_header_overlay.show();				
			$blog_custom_blog_header_blur.show();		
			$blog_custom_blog_header_scale.show();				
			$blog_custom_blog_header_background_color.show();		
			$blog_custom_blog_header_title_color.show();					
			$blog_custom_blog_header_meta_color.show();						
			$blog_custom_blog_header_padding_top.show();					
			$blog_custom_blog_header_padding_bottom.show();					
			$blog_custom_blog_header_alignment.show();						
			$blog_custom_blog_header_width.show();							
			$blog_custom_blog_header_title_uppercase.show();				
			$blog_custom_blog_header_meta_uppercase.show();					
			$blog_custom_blog_header_title_size.show();						
			$blog_custom_blog_header_title_weight.show();					
		} else {
			$blog_custom_blog_header_hide.hide();				
			$blog_custom_blog_header_overlay.hide();						
			$blog_custom_blog_header_blur.hide();							
			$blog_custom_blog_header_scale.hide();							
			$blog_custom_blog_header_background_color.hide();				
			$blog_custom_blog_header_title_color.hide();					
			$blog_custom_blog_header_meta_color.hide();						
			$blog_custom_blog_header_padding_top.hide();					
			$blog_custom_blog_header_padding_bottom.hide();					
			$blog_custom_blog_header_alignment.hide();						
			$blog_custom_blog_header_width.hide();							
			$blog_custom_blog_header_title_uppercase.hide();				
			$blog_custom_blog_header_meta_uppercase.hide();					
			$blog_custom_blog_header_title_size.hide();						
			$blog_custom_blog_header_title_weight.hide();					
		}
		
		if ( $blog_custom_blog_featured_image_input.is( ':checked') ) {
			$blog_custom_blog_featured_image_hide.show();		
			$blog_custom_blog_featured_image_box_shadow.show();	
			$blog_custom_blog_featured_image_offset.show();		
			$blog_custom_blog_featured_image_border_width.show();
		} else {
			$blog_custom_blog_featured_image_hide.hide();			
			$blog_custom_blog_featured_image_box_shadow.hide();		
			$blog_custom_blog_featured_image_offset.hide();			
			$blog_custom_blog_featured_image_border_width.hide();			
		}
		
		if ( $footer_reveal_input.is( ':checked') ) {
			$footer_reveal_shadow_opacity.show();		
			$footer_reveal_shadow_verical.show();	
			$footer_reveal_shadow_blur.show();		
			$footer_reveal_shadow_spread.show();
		} else {
			$footer_reveal_shadow_opacity.hide();			
			$footer_reveal_shadow_verical.hide();		
			$footer_reveal_shadow_blur.hide();			
			$footer_reveal_shadow_spread.hide();			
		}
		
	
		$('#customize-theme-controls').on( 'change', '#customize-control-divi_ultimate_plugin_widget_custom_header_settings input[type=checkbox]', function(){
			$input = $(this);
			
			if ( $input.is(':checked') ) {
				$widget_header_size_settings.show();
				$widget_header_bold_settings.show();
				$widget_header_uppercase_settings.show();
			} else {
				$widget_header_size_settings.hide();
				$widget_header_bold_settings.hide();
				$widget_header_uppercase_settings.hide();
			}
		});
		
		$('#customize-theme-controls').on( 'change', '#customize-control-divi_ultimate_plugin_header_show_search input[type=checkbox]', function(){
			$input = $(this);
			
			if ( $input.is(':checked') ) {
				$header_search_text_color.show();
			} else {
				$header_search_text_color.hide();
			}
		});
		
		$('#customize-theme-controls').on( 'change', '#customize-control-divi_ultimate_plugin_header_show_cart input[type=checkbox]', function(){
			$input = $(this);
			
			if ( $input.is(':checked') ) {
				$header_cart_background_color.show();
				$header_cart_total_color.show();
			} else {
				$header_cart_background_color.hide();
				$header_cart_total_color.hide();
			}
		});
		
		$( '#customize-theme-controls' ).on( 'change', '#customize-control-divi_ultimate_plugin_header_menu_hover_style select', function(){
			$input = $(this);

			if ( 'none' === $input.val() ) {
				$header_menu_hover_main_color.hide();
				$header_menu_hover_text_color.hide();
			} else {
				$header_menu_hover_main_color.show();
				$header_menu_hover_text_color.show();
			}
		});
		
		$('#customize-theme-controls').on( 'change', '#customize-control-divi_ultimate_plugin_blog_post_enable input[type=checkbox]', function(){
			$input = $(this);
			
			if ( $input.is(':checked') ) {
				$blog_custom_blog_header_style.show();				
				$blog_custom_blog_header_parallax.show();					
				$blog_custom_blog_post_navigation_style.show();				
				$blog_custom_blog_related_posts_style.show();				
				$blog_custom_blog_related_posts_border_radius.show();		
			} else {
				$blog_custom_blog_header_style.hide();				
				$blog_custom_blog_header_parallax.hide();					
				$blog_custom_blog_post_navigation_style.hide();				
				$blog_custom_blog_related_posts_style.hide();				
				$blog_custom_blog_related_posts_border_radius.hide();		
			}
		});
		
		$('#customize-theme-controls').on( 'change', '#customize-control-divi_ultimate_plugin_blog_post_header_custom input[type=checkbox]', function(){
			$input = $(this);
			
			if ( $input.is(':checked') ) {
				$blog_custom_blog_header_hide.show();			
				$blog_custom_blog_header_overlay.show();				
				$blog_custom_blog_header_blur.show();		
				$blog_custom_blog_header_scale.show();				
				$blog_custom_blog_header_background_color.show();		
				$blog_custom_blog_header_title_color.show();					
				$blog_custom_blog_header_meta_color.show();						
				$blog_custom_blog_header_padding_top.show();					
				$blog_custom_blog_header_padding_bottom.show();					
				$blog_custom_blog_header_alignment.show();						
				$blog_custom_blog_header_width.show();							
				$blog_custom_blog_header_title_uppercase.show();				
				$blog_custom_blog_header_meta_uppercase.show();					
				$blog_custom_blog_header_title_size.show();						
				$blog_custom_blog_header_title_weight.show();	
			} else {
				$blog_custom_blog_header_hide.hide();				
				$blog_custom_blog_header_overlay.hide();						
				$blog_custom_blog_header_blur.hide();							
				$blog_custom_blog_header_scale.hide();							
				$blog_custom_blog_header_background_color.hide();				
				$blog_custom_blog_header_title_color.hide();					
				$blog_custom_blog_header_meta_color.hide();						
				$blog_custom_blog_header_padding_top.hide();					
				$blog_custom_blog_header_padding_bottom.hide();					
				$blog_custom_blog_header_alignment.hide();						
				$blog_custom_blog_header_width.hide();							
				$blog_custom_blog_header_title_uppercase.hide();				
				$blog_custom_blog_header_meta_uppercase.hide();					
				$blog_custom_blog_header_title_size.hide();						
				$blog_custom_blog_header_title_weight.hide();	
			}
		});
		
		$('#customize-theme-controls').on( 'change', '#customize-control-divi_ultimate_plugin_blog_post_featured_image_custom input[type=checkbox]', function(){
			$input = $(this);
			
			if ( $input.is(':checked') ) {
				$blog_custom_blog_featured_image_hide.show();		
				$blog_custom_blog_featured_image_box_shadow.show();	
				$blog_custom_blog_featured_image_offset.show();		
				$blog_custom_blog_featured_image_border_width.show();
			} else {
				$blog_custom_blog_featured_image_hide.hide();			
				$blog_custom_blog_featured_image_box_shadow.hide();		
				$blog_custom_blog_featured_image_offset.hide();			
				$blog_custom_blog_featured_image_border_width.hide();		
			}
		});
		
		$('#customize-theme-controls').on( 'change', '#customize-control-divi_ultimate_plugin_footer_reveal input[type=checkbox]', function(){
			$input = $(this);
			
			if ( $input.is(':checked') ) {
				$footer_reveal_shadow_opacity.show();		
				$footer_reveal_shadow_verical.show();	
				$footer_reveal_shadow_blur.show();		
				$footer_reveal_shadow_spread.show();
			} else {
				$footer_reveal_shadow_opacity.hide();			
				$footer_reveal_shadow_verical.hide();		
				$footer_reveal_shadow_blur.hide();			
				$footer_reveal_shadow_spread.hide();	
			}
		});
		
	});
})(jQuery);