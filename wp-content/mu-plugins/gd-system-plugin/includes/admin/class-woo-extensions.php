<?php

namespace WPaaS\Admin;

use \WPaaS\Plugin;

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

final class Woo_Extensions {

	/**
	 * WPNUX API URL.
	 *
	 * @var string
	 */
	private $wpnux_api_url;

	/**
	 * Class constructor.
	 */
	public function __construct() {

		/**
		 * Filter the wpnux API URL.
		 *
		 * @param string WPNUX site URL
		 */
		$this->wpnux_api_url = (string) apply_filters( 'wpaas_wpnux_api_url', 'https://wpnux.godaddy.com/v2/api' );

		add_action( 'init', [ $this, 'init' ] );

	}

	/**
	 * Initialize the script.
	 *
	 * @action init
	 */
	public function init() {

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_footer',          [ $this, 'woocommerce_extensions_dialog' ] );

		add_action( 'wp_ajax_wpaas_wp_login',  [ $this, 'wp_authenticate' ] );
		add_action( 'wp_ajax_wpaas_wp_reauth', [ $this, 'wp_reauthenticate' ] );

		add_action( 'wp_ajax_render_woocommerce_extensions',  [ $this, 'render_woocommerce_extensions' ] );
		add_action( 'wp_ajax_install_woocommerce_extensions', [ $this, 'install_woocommerce_extensions' ] );

	}

	/**
	 * Enqueue the WooCommerce extensions scripts.
	 *
	 * @return null
	 */
	public function enqueue_scripts() {

		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'wpaas-woocommerce-extensions', Plugin::assets_url( "js/wpaas-woocommerce-extensions{$suffix}.js" ), [ 'jquery' ], Plugin::version(), true );

		wp_localize_script(
			'wpaas-woocommerce-extensions',
			'wpaasWooCommerceExtensions',
			[
				'dialogTitle' => __( 'WooCommerce Extensions', 'gd-system-plugin' ),
				'preloader'   => sprintf(
					'<img src="%s" class="loader" />',
					esc_url( admin_url( 'images/wpspin_light.gif' ) )
				),
				'wpcomNonce'  => wp_create_nonce( 'wpaas_wpcom_nonce' ),
			]
		);

	}

	public function woocommerce_extensions_dialog() {

		?>

		<div id="mwp_woocommerce_extensions_dialog" style="display: none;">
			<?php

			printf(
				'<p class="header-msg">%s</p>',
				esc_html__( 'Here is a list of free WooCommerce extensions that come with your site. Install the ones you would like to use.', 'gd-system-plugin' )
			);

			?>

				<form class="extensions">
					<img src="<?php echo esc_url( admin_url( 'images/wpspin_light-2x.gif' ) ); ?>" class="preloader" />
				</form>

			<?php

			$this->render_wpcom_authentication_message();

			printf(
				'<div class="actions">
					<button href="#" class="js-install-wc-extensions button button-primary button-large" disabled data-nonce="%1$s">%2$s</button>&nbsp;
					<button href="#" class="js-cancel-wc-extensions button button-secondary button-large">%3$s</button>
				</div>',
				wp_create_nonce( 'updates' ),
				esc_html__( 'Install', 'gd-system-plugin' ),
				esc_html__( 'Cancel', 'gd-system-plugin' )
			)

			?>
		</div>

		<?php

	}

	/**
	 * Check if the user is authenticated with WordPress.com.
	 *
	 * @return boolean True when authenticated, else false.
	 */
	private function is_wpcom_authenticated() {

		return ! in_array( get_option( 'wpaas_wp_com_token' ), [ false, '0' ], true );

	}

	/**
	 * Render the WordPress.com authentication message.
	 *
	 * @return mixed Markup for the WordPress.com authentication.
	 */
	private function render_wpcom_authentication_message() {

		$wpcom_token = get_option( 'wpaas_wp_com_token' );

		if ( false === $wpcom_token ) {

			return printf(
				'<div class="auth-notice">
					<h2>%1$s</h2>
					<p>%2$s</p>
				</div>',
				__( 'Connect to WordPress.com', 'gd-system-plugin' ),
				sprintf(
					/* translators: Anchor tag link to WordPress.com authentication URL */
					__( 'To give you the benefits of the premium WooCommerce features, we need to connect to a WordPress.com account. %s.', 'gd-system-plugin' ),
					sprintf(
						'<a href="%1$s" class="js-wpaas-wpcom-auth">%2$s</a>',
						esc_url( $this->get_wp_login_uri() ),
						esc_html__( 'Sign in/Sign up', 'gd-system-plugin' )
					)
				)
			);

		}

		switch ( $wpcom_token ) {

			case '0':
				$message = sprintf(
					/* translators: 1. Admin email address. 2. Link to WordPress.com authentication page. */
					__( 'Looks like you already have a WordPress account with %1$s. %2$s to your account to get the benefits of the premium WooCommerce features.', 'gd-system-plugin' ),
					sanitize_email( get_option( 'admin_email' ) ),
					sprintf(
						'<a href="%1$s">%2$s</a>',
						esc_url( $this->get_wp_login_uri() ),
						esc_html__( 'Sign In', 'gd-system-plugin' )
					)
				);
				break;

			default:
				$message = sprintf(
					/* translators: 1. Checkmark. 2. Anchor tag link to WordPress.com */
					__( '%1$s Success! You\'re connected to WordPress.com. %2$s.', 'gd-system-plugin' ),
					'<span class="dashicons dashicons-yes connected-check"></span>',
					sprintf(
						'<a href="%1$s" class="js-wpaas-wpcom-reauth">%2$s</a>',
						esc_url( $this->get_wp_login_uri() ),
						esc_html__( 'Change', 'gd-system-plugin' )
					)
				);
				break;

		}

		printf(
			'<div class="auth-notice">
				<h2>%1$s</h2>
				<p>%2$s</p>
			</div>',
			esc_html__( 'Connect to WordPress.com', 'gd-system-plugin' ),
			wp_kses_post( $message )
		);

	}

	/**
	 * Authenticate the user with WordPress.com and store the access token.
	 *
	 * @return bool True when access token is found, else false.
	 */
	public function wp_authenticate() {

		check_ajax_referer( 'wpaas_wpcom_nonce', 'login_nonce' );

		$access_token = filter_input( INPUT_POST, 'access_token', FILTER_SANITIZE_STRING );

		if ( ! isset( $access_token ) ) {

			wp_send_json_error( __( 'WordPress.com access token is missing.', 'wpem' ) );

		}

		update_option( 'wpaas_wp_com_token', $access_token );

		wp_send_json_success();

	}

	/**
	 * Reauthenticate a user with WordPress.com.
	 *
	 * @return bool Always true.
	 */
	public function wp_reauthenticate() {

		check_ajax_referer( 'wpaas_wpcom_nonce', 'reauth_nonce' );

		delete_option( 'wpaas_wp_com_token' );

		wp_send_json_success();

	}

	/**
	 * Render the WooCommerce extensions.
	 */
	public function render_woocommerce_extensions() {

		$api_url = sprintf( '%s/woocommerce/extensions/%s', $this->wpnux_api_url, get_option( 'woocommerce_default_country', 'US:AZ' ) );

		$response = wp_remote_get(
			add_query_arg( 'premium', (int) $this->is_wpcom_authenticated(), esc_url_raw( $api_url ) ),
			[ 'timeout' => 30 ]
		);

		if ( 200 !== wp_remote_retrieve_response_code( $response ) || is_wp_error( $response ) ) {

			wp_send_json_error( $response );

		}

		$extensions = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $extensions ) || ! is_array( $extensions ) ) {

			wp_send_json_success();

		}

		$markup = '<ul>';

		ob_start();

		foreach ( $extensions as $extension ) {

			if ( empty( $extension['slug'] ) || empty( $extension['name'] ) ) {

				continue;

			}

			$checkbox = file_exists( WP_PLUGIN_DIR . '/' . $extension['slug'] ) ? '<span class="dashicons dashicons-yes install-success"></span>' : sprintf(
				'<input type="checkbox" class="js-extension-checkbox" data-plugin-slug="%s" />',
				esc_attr( $extension['slug'] )
			);

			?>
			<li class="plugin">
				<?php echo $checkbox; ?>
				<div class="content">
					<strong><?php echo esc_html( $extension['name'] ); ?></strong>
					<p class="description">
						<?php echo ! empty( $extension['description'] ) ? esc_html( $extension['description'] ) : null; ?>
						<?php if ( ! empty( $extension['homepage'] ) ) : ?>
							<a href="<?php echo esc_url( $extension['homepage'] ); ?>" target="_blank"><?php esc_html_e( 'View More', 'gd-system-plugin' ); ?></a>
						<?php endif; ?>
					</p>
				</div>
			</li>
			<?php

		}

		$markup .= ob_get_clean();
		$markup .= '</ul>';

		wp_send_json_success( $markup );

	}

	/**
	 * Install WooCommerce extensions.
	 */
	public function install_woocommerce_extensions() {

		$extension_slug = filter_input( INPUT_POST, 'slug', FILTER_SANITIZE_STRING );

		if ( ! $extension_slug ) {

			wp_send_json_error();

		}

		$api_url = sprintf( '%s/woocommerce/extensions/%s', $this->wpnux_api_url, get_option( 'woocommerce_default_country', 'US:AZ' ) );

		$response = wp_remote_get(
			add_query_arg( 'premium', (int) $this->is_wpcom_authenticated(), esc_url_raw( $api_url ) ),
			[ 'timeout' => 30 ]
		);

		if ( 200 !== wp_remote_retrieve_response_code( $response ) || is_wp_error( $response ) ) {

			wp_send_json_error(
				[
					'slug'         => $extension_slug,
					'errorMessage' => $download->get_error_message(),
				]
			);

		}

		$extensions = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $extensions ) || ! is_array( $extensions ) ) {

			wp_send_json_error(
				[
					'slug'         => $extension_slug,
					'errorMessage' => __( 'Error retreiving extension data. Please try again.', 'gd-system-plugin' ),
				]
			);

		}

		$index = array_search( $extension_slug, array_column( $extensions, 'slug' ), true );

		if ( false === $index ) {

			wp_send_json_error(
				[
					'slug'         => $extension_slug,
					'errorMessage' => __( 'Extension slug not found in list of available plugins.', 'gd-system-plugin' ),
				]
			);

		}

		// Free WooCommerce extension.
		if ( ! isset( $extensions[ $index ]['download_link'] ) ) {

			$extensions[ $index ]['download_link'] = sprintf( 'https://downloads.wordpress.org/plugin/%s.latest-stable.zip', $extension_slug );

		}

		$download = $this->download_extension( $extensions[ $index ]['download_link'], $extension_slug );

		if ( is_wp_error( $download ) ) {

			wp_send_json_error(
				[
					'slug'         => $extension_slug,
					'errorMessage' => $download->get_error_message(),
				]
			);

		}

		$activate = $this->activate_extension( $extension_slug . '/' . $extension_slug . '.php' );

		if ( is_wp_error( $activate ) ) {

			wp_send_json_error(
				[
					'slug'         => $extension_slug,
					'errorMessage' => sprintf(
						/* Translators: 1. The name of the extension. 2. Link to the plugins page. */
						__( '%1$s was installed but encountered an error during activation. Manually activate the plugin on the %2$s.', 'gd-system-plugin' ),
						$extensions[ $index ]['name'],
						sprintf(
							'<a href="%1$s">%2$s</a>',
							esc_url( admin_url( 'plugins.php' ) ),
							esc_html__( 'plugins page', 'gd-system-plugin' )
						)
					),
				]
			);

		}

		wp_send_json_success( [ 'slug' => $extension_slug ] );

	}

	/**
	 * Download an extension.
	 *
	 * @param  string $download_link  URL where the extension can be downloaded from.
	 * @param  string $extension_slug Slug of the extension being installed.
	 *
	 * @return bool|WP_Error True when the extension is installed, else WP_Error.
	 */
	private function download_extension( $download_link, $extension_slug ) {

		$download = download_url( $download_link );

		if ( is_wp_error( $download ) ) {

			return $download;

		}

		WP_Filesystem();

		unzip_file( $download, WP_PLUGIN_DIR );

		@unlink( $download );

		return is_readable( trailingslashit( WP_PLUGIN_DIR ) . $extension_slug );

	}

	/**
	 * Activate a plugin.
	 * Note: Required since we need to reset the plugins cache.
	 *
	 * @param string $plugin_path Path to main the plugin file.
	 *
	 * @return bool True when plugin is activated, else false.
	 */
	private function activate_extension( $plugin_path ) {

		$plugin_header = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_path );
		$cache_plugins = wp_cache_get( 'plugins', 'plugins' );

		if ( ! empty( $cache_plugins ) && ! empty( $plugin_header ) ) {

			$cache_plugins[''][ $plugin_path ] = $plugin_header;

			wp_cache_set( 'plugins', $cache_plugins, 'plugins' );

		}

		return is_wp_error( activate_plugin( $plugin_path ) );

	}

	/**
	 * Build the WordPress.com authentication URL.
	 *
	 * @return string WordPress.com authentication URL.
	 */
	private function get_wp_login_uri() {

		$host = explode( '.', GD_TEMP_DOMAIN );

		return add_query_arg(
			[
				'client_id'     => 64757,
				'response_type' => 'token',
				'scope'         => 'auth',
				'redirect_uri'  => sprintf(
					'https://wpnux.godaddy.com/__%1$s__/%2$s/%3$s',
					$host[2],
					$host[0],
					$host[1]
				),
			],
			'https://public-api.wordpress.com/oauth2/authorize'
		);

	}

}
