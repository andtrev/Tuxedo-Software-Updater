<?php
/**
 * Tuxedo Software Update Licensing update key admin.
 *
 * Adds update key for user profiles.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

/**
 * Output update key fields for user profile.
 *
 * @since 1.0.0
 *
 * @param object $user User object.
 */
function tux_su_update_key_admin_fields( $user ) {

	if ( ! current_user_can( 'edit_user', $user->ID ) ) {

		return;

	}

	$update_key = get_user_meta( $user->ID, '_tux_su_update_key', true );
	$disabled   = 0;

	if ( empty( $update_key ) ) {

		$update_key = tux_su_generate_update_key( $user->ID );

	}

	if ( strpos( $update_key, '-disabled' ) !== false ) {

		$update_key = str_replace( '-disabled', ' &nbsp; &nbsp; [ <span style="color:red;">' . __( 'DISABLED', 'tuxedo-software-updater' ) . '</span> ]', $update_key );
		$disabled   = 1;

	}

	?>
	<h2><?php esc_html_e( 'Tuxedo Software Updater', 'tuxedo-software-updater' ); ?></h2>
	<table class="form-table">
		<tr>
			<th>
				<label><?php esc_html_e( 'Update Key', 'tuxedo-software-updater' ); ?></label>
			</th>
			<td>
				<?php echo esc_html( $update_key ); ?>
			</td>
		</tr>
		<tr>
			<th>
				<label for="tux_generate_update_key">Generate</label>
			</th>
			<td>
				<label for="tux_generate_update_key"><input type="checkbox" id="tux_generate_update_key" name="tux_generate_update_key" value="1"/> <?php esc_html_e( 'Generate new update key', 'tuxedo-software-updater' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th>
				<label for="tux_disable_update_key">Disable</label>
			</th>
			<td>
				<label for="tux_disable_update_key"><input type="checkbox" id="tux_disable_update_key" name="tux_disable_update_key" value="1" <?php checked( $disabled, 1 ); ?> /> <?php esc_html_e( 'Disable updates for this user', 'tuxedo-software-updater' ); ?>
				</label>
			</td>
		</tr>
	</table>
	<?php

}

add_action( 'show_user_profile', 'tux_su_update_key_admin_fields' );
add_action( 'edit_user_profile', 'tux_su_update_key_admin_fields' );

/**
 * Save update key to user meta.
 *
 * @since 1.0.0
 *
 * @param object $user_id User object.
 */
function tux_su_update_key_admin_save( $user_id ) {

	if ( ! current_user_can( 'edit_user', $user_id ) ) {

		return;

	}

	$update_key_db = get_user_meta( $user_id, '_tux_su_update_key', true );
	$update_key    = $update_key_db;

	if ( isset( $_POST['tux_generate_update_key'] ) && 1 === absint( $_POST['tux_generate_update_key'] ) ) {

		$update_key = tux_su_generate_update_key();

	}

	if ( isset( $_POST['tux_disable_update_key'] ) && 1 === absint( $_POST['tux_disable_update_key'] ) ) {

		if ( strpos( $update_key, '-disabled' ) === false ) {

			$update_key .= '-disabled';

		}
	} else {

		$update_key = str_replace( '-disabled', '', $update_key );

	}

	if ( $update_key !== $update_key_db ) {

		update_user_meta( $user_id, '_tux_su_update_key', $update_key );

	}
}

add_action( 'personal_options_update', 'tux_su_update_key_admin_save' );
add_action( 'edit_user_profile_update', 'tux_su_update_key_admin_save' );
