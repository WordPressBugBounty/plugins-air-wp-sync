<h4><?php esc_html_e( 'Last Sync Status', 'air-wp-sync' ); ?></h4>

<p class="<?php echo esc_attr( $status_class ); ?>">
	<?php if ( $status === 'success' ) : ?>
		<?php esc_html_e( 'Successful!', 'air-wp-sync' ); ?>
	<?php elseif ( $status === 'error' ) : ?>
		<?php esc_html_e( 'Error', 'air-wp-sync' ); ?>
	<?php elseif ( $status === 'cancel' ) : ?>
		<?php esc_html_e( 'Canceled', 'air-wp-sync' ); ?>
	<?php else : ?>
		--
	<?php endif; ?>
</p>

<?php if ( $status === 'error' && $last_error ) : ?>
	<p class="airwpsync-last-error"><?php echo esc_html( $last_error ); ?></p>
<?php endif; ?>

<h4><?php esc_html_e( 'Last Sync', 'air-wp-sync' ); ?></h4>
<p>
	<?php if ( $last_updated ) : ?>
		<?php
			echo esc_html(
				sprintf(
					/* translators: %s: Date */
					__( 'Date: %s', 'air-wp-sync' ),
					\Air_WP_Sync_Free\Air_WP_Sync_Helper::get_formatted_date_time( $last_updated )
				)
			);
		?>
	<?php else : ?>
		--
	<?php endif; ?>
</p>

<?php if ( ! empty( $content_ids ) ) : ?>
	<p>
		<?php
		echo esc_html(
			sprintf(
				/* translators: %d: Number of processed posts */
				__( 'Processed posts: %d', 'air-wp-sync' ),
				count( $content_ids )
			)
		);
		?>
	</p>
<?php elseif ( ! empty( $count_processed ) ) : ?>
	<p>
		<?php echo esc_html( sprintf( __( 'Processed posts: %d', 'air-wp-sync' ), $count_processed ) ); ?>	
	</p>
<?php endif; ?>

<?php if ( ! empty( $latest_log_url ) ) : ?>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
				/* translators: %1$s: Download link | %2$s: Download link label */
				'<a href="%s">%s</a>',
				esc_url( $latest_log_url ),
				__( 'Download the latest log file.', 'air-wp-sync' )
			)
		);
		?>
	</p>
<?php endif; ?>

<template x-if="config.scheduled_sync.type === 'cron'">
	<div>
		<p>
			<?php
			echo esc_html(
				sprintf(
					/* translators: %s: Next sync date */
					__( 'Scheduled Next Sync: %s', 'air-wp-sync' ),
					! empty( $next_sync ) ? \Air_WP_Sync_Free\Air_WP_Sync_Helper::get_formatted_date_time( $next_sync ) : '--'
				)
			);
			?>
		</p>
	</div>
</template>
