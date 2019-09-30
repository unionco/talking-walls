<?php

namespace WPaaS;

use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

final class Yoast_SEO {

	public function __construct() {

		if ( ! defined( 'GD_ACCOUNT_UID' ) || empty( $_SERVER['HTTP_X_ACCOUNT_UID'] ) || $_SERVER['HTTP_X_ACCOUNT_UID'] !== GD_ACCOUNT_UID ) {

			return;

		}

		add_action( 'rest_api_init', [ $this, 'register_rest_route' ] );

	}

	public function register_rest_route() {

		register_rest_route( 'wpaas/v1', 'yoast', [
			'methods'  => WP_REST_Server::READABLE,
			'callback' => function () {
				$wpseo = (array) get_option( 'wpseo', [] );
				return [
					'active'                 => defined( 'WPSEO_VERSION' ),
					'environment_type'       => ! empty( $wpseo['environment_type'] ) ? $wpseo['environment_type'] : null,
					'first_activated_on'     => ! empty( $wpseo['first_activated_on'] ) ? $wpseo['first_activated_on'] : null,
					'show_onboarding_notice' => ! empty( $wpseo['show_onboarding_notice'] ),
					'site_type'              => ! empty( $wpseo['site_type'] ) ? $wpseo['site_type'] : null,
					'version'                => ! empty( $wpseo['version'] ) ? $wpseo['version'] : null,
				];
			},
		] );

	}

}
