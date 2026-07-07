<?php
/**
 * Manages admin page.
 *
 * @package Air_WP_Sync_Free
 */

namespace Air_WP_Sync_Free;

/**
 * Admin Page class
 */
class Air_WP_Sync_Admin_Page {
	/**
	 * Plugin settings
	 *
	 * @var Air_WP_Sync_Settings
	 */
	protected $options;

	/**
	 * Admin page slug
	 *
	 * @var string
	 */
	protected $page_slug = 'air-wp-sync-settings';

	/**
	 * Constructor
	 *
	 * @param Air_WP_Sync_Options $options Plugin settings.
	 */
	public function __construct( $options ) {
		$this->options = $options;
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	/**
	 * Add setting page in admin menu
	 */
	public function add_menu() {
		add_submenu_page(
			'edit.php?post_type=airwpsync-connection',
			__( 'Settings', 'air-wp-sync' ),
			__( 'Settings', 'air-wp-sync' ),
			apply_filters( 'airwpsync/manage_options_capability', 'manage_options' ),
			$this->page_slug,
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Render admin page
	 */
	public function admin_page() {
		$this->maybe_update_settings();
		$view = include_once AIR_WP_SYNC_PLUGIN_DIR . 'views/settings-page.php';
		$view( $this->options );
	}

	/**
	 * Update settings based on form submission
	 */
	protected function maybe_update_settings() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'air-wp-sync-settings-form' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		if ( isset( $_POST['air-wp-sync-settings-clear-cache'] ) ) {
			global $wpdb;
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_airwpsync_tables_%'" );
			$this->display_message( __( 'Table cache cleared!', 'air-wp-sync' ), 'success' );
		}

		if ( isset( $_POST['air-wp-sync-settings-update'] ) ) {
			$cache_duration = ! empty( $_POST['cache_duration'] ) ? max( 2, (int) $_POST['cache_duration'] ) : 15;
			$this->options->set( 'cache_duration', $cache_duration );

			// Save options.
			$this->options->save();
			$this->display_message( __( 'Settings saved!', 'air-wp-sync' ), 'success' );
		}
	}

	/**
	 * Display a WP notice
	 *
	 * @param string $message Message.
	 * @param string $type Message type (@see https://developer.wordpress.org/reference/hooks/admin_notices/#example).
	 */
	protected function display_message( $message, $type = 'info' ) {
		echo '<div class="notice notice-' . esc_attr( $type ) . '"><p>' . esc_html( $message ) . '</p></div>';
	}
}
