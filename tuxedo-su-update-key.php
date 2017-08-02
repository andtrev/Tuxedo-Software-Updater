<?php
/**
 * Tuxedo Software Update Licensing update key.
 *
 * Create, retrieve, store and display update keys.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

/**
 * Validate the formatting of an update key.
 *
 * @since 1.0.0
 *
 * @param string $key Update key.
 *
 * @return bool True on valid format, false on invalid format.
 */
function tux_su_validate_update_key( $key ) {

	if ( ! is_string( $key ) || strlen( $key ) !== 36 || preg_match( '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $key ) !== 1 ) {

		return false;

	}

	return true;

}

/**
 * Get user by update key.
 *
 * @since 1.0.0
 *
 * @param string $key      Update key.
 *
 * @return false|array {
 * False on failure.
 *
 * @type int     $id       User id.
 * @type bool    $disabled If update key is disabled.
 * }
 */
function tux_su_get_user_by_update_key( $key ) {

	global $wpdb;

	$key_info = $wpdb->get_results( $wpdb->prepare( "SELECT user_id AS id, meta_value AS update_key FROM {$wpdb->usermeta} WHERE meta_key = '_tux_su_update_key' AND (meta_value = '%s' OR meta_value = '%s')", $key, $key . '-disabled' ), ARRAY_A );

	if ( is_array( $key_info ) ) {

		$key_info = reset( $key_info );

	}

	if ( empty( $key_info['id'] ) ) {

		return false;

	}

	$key_info['disabled'] = strpos( $key_info['update_key'], '-disable' ) !== false ? true : false;

	unset( $key_info['update_key'] );

	return $key_info;

}

/**
 * Generate update key for user.
 *
 * Key will be updated in user meta as well.
 *
 * @param int $user_id User id.
 *
 * @return string Update key.
 */
function tux_su_generate_update_key( $user_id = 0 ) {

	global $wpdb;

	$key_is_unique = false;

	while ( ! $key_is_unique ) {

		$key = sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0fff ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);

		$key_query = $wpdb->get_var( $wpdb->prepare( "SELECT umeta_id FROM {$wpdb->usermeta} WHERE meta_key = '_tux_su_update_key' AND meta_value = '%s'", $key ) );

		if ( empty( $key_query ) ) {

			$key_is_unique = true;

		}
	}

	if ( ! empty( $user_id ) ) {

		update_user_meta( $user_id, '_tux_su_update_key', $key );

	}

	return $key;

}
