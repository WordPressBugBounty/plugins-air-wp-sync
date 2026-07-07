<?php

namespace Air_WP_Sync_Free;

require_once AIR_WP_SYNC_PLUGIN_DIR . 'includes/admin/class-air-wp-sync-admin-connections-list.php';
require_once AIR_WP_SYNC_PLUGIN_DIR . 'includes/admin/class-air-wp-sync-admin-connection.php';
require_once AIR_WP_SYNC_PLUGIN_DIR . 'includes/admin/class-air-wp-sync-admin-page.php';
require_once AIR_WP_SYNC_PLUGIN_DIR . 'includes/admin/metaboxes/class-air-wp-sync-metabox-field-mapping.php';
require_once AIR_WP_SYNC_PLUGIN_DIR . 'includes/admin/metaboxes/class-air-wp-sync-metabox-global-settings.php';
require_once AIR_WP_SYNC_PLUGIN_DIR . 'includes/admin/metaboxes/class-air-wp-sync-metabox-importer-settings.php';
require_once AIR_WP_SYNC_PLUGIN_DIR . 'includes/admin/metaboxes/class-air-wp-sync-metabox-sync-settings.php';
require_once AIR_WP_SYNC_PLUGIN_DIR . 'includes/admin/metaboxes/class-air-wp-sync-metabox-import-infos.php';

/**
 * Admin
 */
class Air_WP_Sync_Admin {
	/**
	 * Constructor
	 *
	 * @param Air_WP_Sync_Options $options Plugin settings.
	 */
	public function __construct( $options ) {

		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'in_admin_header', array( $this, 'in_admin_header' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_styles_scripts' ) );
		add_action( 'admin_notices', array( $this, 'add_notices' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'admin_footer_scripts' ) );

		add_filter( 'plugin_action_links_' . AIR_WP_SYNC_BASENAME, array( $this, 'plugin_action_links' ) );

		new Air_WP_Sync_Admin_Connections_List();
		new Air_WP_Sync_Admin_Connection();
		new Air_WP_Sync_Admin_Page( $options );
	}

	/**
	 * Add menu
	 */
	public function add_menu() {
		add_menu_page(
			__( 'Air WP Sync', 'air-wp-sync' ),
			__( 'Air WP Sync', 'air-wp-sync' ),
			apply_filters( 'airwpsync/manage_options_capability', 'manage_options' ),
			'edit.php?post_type=airwpsync-connection',
			false,
			'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxODMuOTkgMTE4Ij48cGF0aCBkPSJNNTAuNjYgOTAuMjQgMjUuMDEgNzUuNThjLS42Mi0uMzYtLjk5LTEuMDEtLjk5LTEuNzJWNDQuMTNjMC0uNzEuMzgtMS4zNi45OS0xLjcybDI2Ljk4LTE1LjQ0Uzk3LjEgNTMuODMgMTMxLjA2LjkzYy0zOC43MyAzNi44My03MS42OSA1LjgzLTc4LS4zNi0uNjQtLjYyLTEuNjItLjc1LTIuMzktLjMxTC45OSAyOC42N2MtLjYyLjM2LS45OSAxLjAxLS45OSAxLjcydjU3LjJjMCAuNzEuMzggMS4zNi45OSAxLjcybDQ5LjM2IDI4LjIzYy45Mi41MyAyLjA5LjI0IDIuNjctLjY0Qzg2Ljg2IDY2LjA4IDEzMS45OCA5MSAxMzEuOTggOTFjLTM5LjAyLTM1Ljc1LTcyLjY2LTcuMDctNzguOTQtMS4wNS0uNjQuNjEtMS42MS43NC0yLjM4LjI5WiIgZmlsbD0iI0ZGRiIgb3BhY2l0eT0iMC44Ii8+PHBhdGggZD0iTTEzMC45NiAxLjA4Qzk3LjEyIDUxLjkgNTIgMjYuOTggNTIgMjYuOThjMzkuMDIgMzUuNzUgNzIuNjYgNy4wNyA3OC45NCAxLjA1LjY0LS42MSAxLjYxLS43NCAyLjM4LS4yOWwyNS42NSAxNC42NmMuNjIuMzYuOTkgMS4wMS45OSAxLjcydjI5LjczYzAgLjcxLS4zOCAxLjM2LS45OSAxLjcybC0yNi45OCAxNS40NHMtNDUtMjcuMDEtNzguOTYgMjUuOWMzOC43My0zNi44MyA3MS41OS01LjY5IDc3LjkxLjUxLjY0LjYyIDEuNjIuNzUgMi4zOS4zMWw0OS42NS0yOC40Yy42Mi0uMzYuOTktMS4wMS45OS0xLjcyVjMwLjM4YzAtLjcxLS4zOC0xLjM2LS45OS0xLjcyTDEzMy42My40M2MtLjkyLS41My0yLjA5LS4yNC0yLjY3LjY0WiIgZmlsbD0iI0ZGRiIgLz48L3N2Zz4K'
		);
		add_submenu_page(
			'edit.php?post_type=airwpsync-connection',
			__( 'All Connections', 'air-wp-sync' ),
			__( 'All Connections', 'air-wp-sync' ),
			apply_filters( 'airwpsync/manage_options_capability', 'manage_options' ),
			'edit.php?post_type=airwpsync-connection'
		);
		add_submenu_page(
			'edit.php?post_type=airwpsync-connection',
			__( 'Add New', 'air-wp-sync' ),
			__( 'Add New', 'air-wp-sync' ),
			apply_filters( 'airwpsync/manage_options_capability', 'manage_options' ),
			'post-new.php?post_type=airwpsync-connection'
		);
		add_submenu_page(
			'edit.php?post_type=airwpsync-connection',
			__( 'Documentation', 'air-wp-sync' ),
			__( 'Documentation', 'air-wp-sync' ),
			apply_filters( 'airwpsync/manage_options_capability', 'manage_options' ),
			'https://wpconnect.co/air-wp-sync-documentation'
		);
	}

	/**
	 * Display plugin header
	 */
	public function in_admin_header() {
		$screen = get_current_screen();
		if ( 'airwpsync-connection' === $screen->post_type ) {
			include_once AIR_WP_SYNC_PLUGIN_DIR . 'views/header.php';
		}
	}

	/**
	 * Register admin styles and scripts
	 */
	public function register_styles_scripts() {
		wp_enqueue_style( 'air-wp-sync-admin', plugins_url( 'assets/css/admin-page.css', AIR_WP_SYNC_PLUGIN_FILE ), false, AIR_WP_SYNC_VERSION );
	}

	/**
	 * Show action links on the plugin screen
	 */
	public function plugin_action_links( $links ) {
		return array_merge(
			$links,
			array(
				'upgrade' => '<a href="https://wpconnect.co/air-wp-sync-plugin/" target="_blank"><b>' . esc_html__( 'Upgrade to pro+ version', 'air-wp-sync' ) . '</b></a>',
			)
		);
	}

	/**
	 * Adds target="_blank" to documentation link
	 */
	public function admin_footer_scripts() {
		?>
		<script>
			document.addEventListener( 'DOMContentLoaded', function() {
				const documentationLink = document.querySelector("#toplevel_page_edit-post_type-airwpsync-connection a[href^='https://wpconnect.co/air-wp-sync-documentation']");
				if( documentationLink ){
					documentationLink.setAttribute( 'target', '_blank' );
				}
			});
		</script>
		<?php
	}

	/**
	 * Add admin notices
	 */
	public function add_notices() {
		// Add notice if some importers have deprecated api keys
		$deprecated_key_importers = array_filter(
			Air_WP_Sync_Helper::get_importers(),
			function ( $importer ) {
				return strpos( $importer->config()->get( 'api_key' ), 'key' ) === 0;
			}
		);

		if ( $deprecated_key_importers ) {
			$list = array_map(
				function ( $importer ) {
					return '<a href="' . get_edit_post_link( $importer->infos()->get( 'id' ) ) . '">' . get_the_title( $importer->infos()->get( 'id' ) ) . '</a>';
				},
				$deprecated_key_importers
			);
			$list = implode( ', ', $list );
			/* translators: %s = list of connections using deprecated API keys */
			$message = sprintf( __( '<strong>Air WP Sync:</strong> The following connections use API Keys that will be deprecated. To benefit from all the features of our plugin in a more secure way, please use a personal access token instead: %s', 'air-wp-sync' ), $list );
			echo wp_kses(
				"<div class='notice notice-warning'><p>{$message}</p></div>",
				array(
					'div'    => array(
						'class' => array(),
					),
					'p'      => array(),
					'strong' => array(),
				)
			);
		}
	}
}
