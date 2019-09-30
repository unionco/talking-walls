<?php

define( 'GD_VIP', '198.71.233.44' );
define( 'GD_RESELLER', 1 );
define( 'GD_ASAP_KEY', 'f9504f2e84e92bf3d254d499c248aae5' );
define( 'GD_STAGING_SITE', true );
define( 'GD_EASY_MODE', false );
define( 'GD_SITE_CREATED', 1568987910 );
define( 'GD_ACCOUNT_UID', '0c2f1c0b-7464-4745-9abd-4ef962601a56' );
define( 'GD_TEMP_DOMAIN', 'c2x.58b.myftpupload.com' );
define( 'GD_CDN_ENABLED', TRUE );
define( 'GD_RUM_ENABLED', FALSE );

// Newrelic tracking
if ( function_exists( 'newrelic_set_appname' ) ) {
	newrelic_set_appname( '0c2f1c0b-7464-4745-9abd-4ef962601a56;' . ini_get( 'newrelic.appname' ) );
}

/**
 * Is this is a mobile client?  Can be used by batcache.
 * @return array
 */
function is_mobile_user_agent() {
	return array(
	       "mobile_browser"             => !in_array( $_SERVER['HTTP_X_UA_DEVICE'], array( 'bot', 'pc' ) ),
	       "mobile_browser_tablet"      => false !== strpos( $_SERVER['HTTP_X_UA_DEVICE'], 'tablet-' ),
	       "mobile_browser_smartphones" => in_array( $_SERVER['HTTP_X_UA_DEVICE'], array( 'mobile-iphone', 'mobile-smartphone', 'mobile-firefoxos', 'mobile-generic' ) ),
	       "mobile_browser_android"     => false !== strpos( $_SERVER['HTTP_X_UA_DEVICE'], 'android' )
	);
}