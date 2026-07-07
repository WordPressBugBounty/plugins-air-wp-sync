<?php
/**
 * Display the plugin settings page.
 *
 * @package Air_WP_Sync_Free
 */

/**
 * Plugin settings page.
 *
 * @param Air_WP_Sync_Options $options {
 *  @type number $cache_duration Cache duration.
 * }
 */
return function ( $options ) {
	$cache_duration = $options->get( 'cache_duration' );
	?>
<div class="wrap">

	<h2><?php esc_html_e( 'Settings', 'air-wp-sync' ); ?></h2>

	<form method="post">

		<?php wp_nonce_field( 'air-wp-sync-settings-form' ); ?>

		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="cache_duration"><?php esc_html_e( 'Cache duration (minutes)', 'air-wp-sync' ); ?></label>
				</th>
				<td>
					<div>
						<input class="regular-text ltr"
							type="number"
							name="cache_duration"
							min=2
							value="<?php echo esc_attr( $cache_duration ); ?>" />
							<p class="description"><?php esc_html_e( 'WordPress caches your Airtable table structure for 15 minutes by default to improve performance. If changes made in Airtable aren’t showing right away, try clearing the cache or adjusting the duration above.', 'air-wp-sync' ); ?></p>
					</div>
				</td>
			</tr>
		</table>
		<div id="poststuff"></div>

		<p class="submit">
			<input class="button button-primary"
					type="submit"
					name="air-wp-sync-settings-update"
					value="<?php esc_html_e( 'Update settings', 'air-wp-sync' ); ?>"
			/>
			<input class="button button-secondary"
					type="submit"
					name="air-wp-sync-settings-clear-cache"
					value="<?php esc_html_e( 'Clear Cache', 'air-wp-sync' ); ?>"
			/>
		</p>
	</form>
</div>
	<?php
};
