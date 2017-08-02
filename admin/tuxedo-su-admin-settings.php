<?php
/**
 * Tuxedo Software Update Licensing settings admin page.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

/**
 * Display settings admin page.
 *
 * @since 1.0.0
 */
function tux_su_settings_admin_page() {

	if ( ! current_user_can( 'administrator' ) ) {

		return;

	}

	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Tuxedo Software Updater Settings', 'tuxedo-software-updater' ); ?></h1>
		<hr class="wp-header-end">
		<form method="POST" action="options.php">
			<?php
			settings_fields( 'tux_su_settings' );
			do_settings_sections( 'tuxedo-su-settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php

}

/**
 * Register settings, sections and fields.
 *
 * @since 1.0.0
 */
function tux_su_settings_fields() {

	register_setting(
		'tux_su_settings',
		'tux_su_settings',
		'tux_su_validate_settings_options'
	);

	add_settings_section(
		'tux_ecommerce_settings',
		__( 'e-Commerce', 'tuxedo-software-updater' ),
		'tux_su_ecommerce_settings',
		'tuxedo-su-settings'
	);
	add_settings_field(
		'tux_ecommerce',
		__( 'Enable e-Commerce integration', 'tuxedo-software-updater' ),
		'tux_su_ecommerce_field',
		'tuxedo-su-settings',
		'tux_ecommerce_settings',
		array(
			'label_for' => 'tux_ecommerce',
		)
	);

	add_settings_section(
		'tux_tracking_settings',
		__( 'Tracking', 'tuxedo-software-updater' ),
		'tux_su_tracking_settings',
		'tuxedo-su-settings'
	);

	add_settings_field(
		'tux_tracking',
		__( 'Track API hits', 'tuxedo-software-updater' ),
		'tux_su_tracking_field',
		'tuxedo-su-settings',
		'tux_tracking_settings',
		array(
			'label_for' => 'tux_tracking',
		)
	);

	add_settings_field(
		'tux_tracking_cleanup',
		__( 'Remove tracking data after X days', 'tuxedo-software-updater' ),
		'tux_su_tracking_cleanup_field',
		'tuxedo-su-settings',
		'tux_tracking_settings',
		array(
			'label_for' => 'tux_tracking_cleanup',
		)
	);

}

add_action( 'admin_init', 'tux_su_settings_fields' );

/**
 * Validate setting options.
 *
 * @since 1.0.0
 *
 * @param array $input Input.
 *
 * @return array Validated input.
 */
function tux_su_validate_settings_options( $input ) {

	return array(
		'ecommerce'        => empty( $input['ecommerce'] ) ? 0 : 1,
		'tracking'         => empty( $input['tracking'] ) ? 0 : 1,
		'tracking_cleanup' => empty( $input['tracking_cleanup'] ) ? 0 : absint( $input['tracking_cleanup'] ),
	);

}

/**
 * E-Commerce section output.
 *
 * @since 1.0.0
 *
 * @param array $args Display arguments.
 */
function tux_su_ecommerce_settings( $args ) {

	if ( class_exists( 'WooCommerce' ) ) {

		?>
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<label><?php esc_html_e( 'WooCommerce detected', 'tuxedo-software-updater' ); ?></label>
				</th>
				<td>
					<span class="dashicons dashicons-yes" style="color:green;"></span>
				</td>
			</tr>
			</tbody>
		</table>
		<?php

	} elseif ( class_exists( 'Easy_Digital_Downloads' ) ) {

		?>
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<label><?php esc_html_e( 'Easy Digital Downloads detected', 'tuxedo-software-updater' ); ?></label>
				</th>
				<td>
					<span class="dashicons dashicons-yes" style="color:green;"></span>
				</td>
			</tr>
			</tbody>
		</table>
		<?php

	} else {

		?>
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<label><?php esc_html_e( 'No supported e-Commerce plugin detected', 'tuxedo-software-updater' ); ?></label>
				</th>
				<td>
					<span class="dashicons dashicons-no" style="color:red;"></span>
				</td>
			</tr>
			</tbody>
		</table>
		<?php

	} // End if().
}

/**
 * E-Commerce field output.
 *
 * @since 1.0.0
 */
function tux_su_ecommerce_field() {

	$tux_su_settings = get_option( 'tux_su_settings' );
	?>
	<input name="tux_su_settings[ecommerce]" type="hidden" value="0"/>
	<input id="tux_ecommerce" name="tux_su_settings[ecommerce]" type="checkbox" value="1" <?php checked( 1, empty( $tux_su_settings['ecommerce'] ) ? 0 : 1 ); ?> />
	<?php

}

/**
 * Tracking section output.
 *
 * Nothing is output, but required for add_settings_section call.
 *
 * @since 1.0.0
 *
 * @param array $args Display arguments.
 */
function tux_su_tracking_settings( $args ) {
}

/**
 * Tracking field output.
 *
 * @since 1.0.0
 */
function tux_su_tracking_field() {

	$tux_su_settings = get_option( 'tux_su_settings' );
	?>
	<input name="tux_su_settings[tracking]" type="hidden" value="0"/>
	<input id="tux_tracking" name="tux_su_settings[tracking]" type="checkbox" value="1" <?php checked( 1, empty( $tux_su_settings['tracking'] ) ? 0 : 1 ); ?> />
	<?php

}

/**
 * Tracking cleanup field output.
 *
 * @since 1.0.0
 */
function tux_su_tracking_cleanup_field() {

	$tux_su_settings = get_option( 'tux_su_settings' );
	?>
	<input id="tux_tracking_cleanup" name="tux_su_settings[tracking_cleanup]" type="text" value="<?php echo esc_attr( $tux_su_settings['tracking_cleanup'] ); ?>"/>
	<br>
	<span class="description"><?php echo esc_html__( 'Leave blank or 0 to never remove data.', 'tuxedo-software-updater' ); ?></span>
	<?php

}
