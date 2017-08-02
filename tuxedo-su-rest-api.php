<?php
/**
 * Tuxedo Software Update Licensing rest api.
 *
 * Adds rest endpoints and handles requests.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

/**
 * Register rest routes.
 *
 * @since 1.0.0
 */
function tux_su_register_rest_api_routes() {

	register_rest_route( 'tuxedo-updater/v1', '/get-updates/', array(
		'methods'  => 'GET,POST',
		'callback' => 'tux_su_get_updates',
		'args'     => array(
			'update_key'    => array(
				'required'    => false,
				'description' => __( 'Update key.', 'tuxedo-software-updater' ),
				'type'        => 'string',
			),
			'ids'           => array(
				'required'    => true,
				'description' => __( 'Comma separated list of product IDs.', 'tuxedo-software-updater' ),
				'type'        => 'string',
			),
			'versions'      => array(
				'required'    => true,
				'description' => __( 'Comma separated list of current versions requesting updates, per product ID.', 'tuxedo-software-updater' ),
				'type'        => 'string',
			),
			'activation_id' => array(
				'required'    => false,
				'description' => __( 'Human readable ID for the current activation.', 'tuxedo-software-updater' ),
				'type'        => 'string',
			),
		),
	) );

}

add_action( 'rest_api_init', 'tux_su_register_rest_api_routes' );

/**
 * Process REST API get updates request.
 *
 * @since 1.0.0
 *
 * @param WP_REST_Request $request     Request class.
 *
 * @return array {
 * Response from update server, update key and error info.
 *
 * @type array            $id          {
 * Update product info, array key is the product id.
 *
 * @type string           $package     Download file url.
 * @type string           $url         Update info url.
 * @type string           $new_version Update version.
 * @type bool             $autoupdate  Should product be updated automatically?
 * @type int              $expires     Amount of days the license will expire in, -1 for never.
 * @type bool             $no_update   Version compare.
 * }
 * @type array            $update_key  {
 * Update key and error info.
 *
 * @type bool             $found       If update key is found.
 * @type bool             $disabled    If update key is disabled.
 * @type array            $error       {
 * Error info.
 *
 * @type string           $code        Error code (INVALID_UPDATE_KEY_FORMAT).
 * @type string           $message     Human readable error message.
 * }
 * }
 * }
 */
function tux_su_get_updates( WP_REST_Request $request ) {

	$user             = false;
	$product_ids      = explode( ',', $request['ids'] );
	$product_versions = explode( ',', $request['versions'] );
	$updates          = array();
	$delete_ids       = array();
	$activation_id    = empty( $request['activation_id'] ) ? '' : $request['activation_id'];
	$activation_ip    = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE );

	if ( false === $activation_ip && isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {

		$activation_ip = filter_var( $_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 );

	}

	if ( false === $activation_ip && isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {

		$activation_ip = filter_var( $_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 );

	}

	if ( false === $activation_ip ) {

		$activation_ip = $_SERVER['REMOTE_ADDR'];

	}

	$activation_ip_hash = hash( 'crc32', $activation_ip );
	$current_time       = current_time( 'timestamp' );
	$tux_su_settings    = get_option( 'tux_su_settings' );

	$update_key = str_replace( '-disabled', '', empty( $request['update_key'] ) ? '' : $request['update_key'] );

	if ( tux_su_validate_update_key( $update_key ) ) {

		$user = tux_su_get_user_by_update_key( $update_key );

	} else {

		$updates['update_key']['error'] = array(
			'code'    => 'INVALID_UPDATE_KEY_FORMAT',
			'message' => esc_html__( 'Invalid update key format.', 'tuxedo-software-updater' ),
		);

	}

	if ( false === $user ) {

		$updates['update_key']['found'] = false;

	} else {

		$updates['update_key']['found']    = true;
		$updates['update_key']['disabled'] = $user['disabled'];

	}

	if ( count( $product_ids ) === count( $product_versions ) ) {

		if ( false === $user || ! isset( $user['disabled'] ) || true === $user['disabled'] ) {

			$licenses = tux_su_get_db( array(
				'product_id' => $product_ids,
				'user_id'    => 0,
				'type'       => 'rule',
			) );

		} else {

			$licenses = tux_su_get_db( array(
				'product_id' => $product_ids,
				'user_id'    => array( 0, $user['id'] ),
				'order'      => 'product_id, type ASC',
			) );

		}

		foreach ( $licenses as $license ) {

			if ( 1 === (int) $license['type'] ) { // If is rule.

				$license_rule         = $license;
				$license_rule['info'] = maybe_unserialize( $license_rule['info'] );

				if ( 1 === (int) $license_rule['info']['open_update'] ) {

					$updates[ $license_rule['product_id'] ]['package']     = $license_rule['info']['file_url'];
					$updates[ $license_rule['product_id'] ]['url']         = $license_rule['info']['product_url'];
					$updates[ $license_rule['product_id'] ]['new_version'] = $license_rule['info']['version'];
					$updates[ $license_rule['product_id'] ]['autoupdate']  = 1 === (int) $license_rule['info']['autoupdate'] ? true : false;
					$updates[ $license_rule['product_id'] ]['tested']      = $license_rule['info']['compatible'];
					$updates[ $license_rule['product_id'] ]['expires']     = - 1;

				}

				continue;

			}

			if ( empty( $license_rule['product_id'] ) ) {

				continue;

			}

			$license['info'] = maybe_unserialize( $license['info'] );

			if ( ! isset( $updates[ $license_rule['product_id'] ] ) ) {

				$activation_limit = (int) $license_rule['info']['activation_limit'];
				$expiry = (int) $license_rule['info']['expiry'];

				if ( ! empty( $license['info']['child_id'] ) ) {

					if ( isset( $license_rule['info']['children'][ $license['info']['child_id'] ]['activation_limit'] ) ) {

						$activation_limit = (int) $license_rule['info']['children'][ $license['info']['child_id'] ]['activation_limit'];

					}

					if ( isset( $license_rule['info']['children'][ $license['info']['child_id'] ]['expiry'] ) ) {

						$expiry = (int) $license_rule['info']['children'][ $license['info']['child_id'] ]['expiry'];

					}
				}

				if ( $expiry < 1 || $current_time < strtotime( $license['created'] . ' + ' . $expiry . ' days' ) ) {

					if ( $activation_limit < 1 || count( $license['info']['activations'] ) < $activation_limit || isset( $license['info']['activations'][ $activation_ip_hash ] ) ) {

						if ( $activation_limit > 0 && ( ! isset( $license['info']['activations'][ $activation_ip_hash ]['id'] ) || $license['info']['activations'][ $activation_ip_hash ]['id'] !== $activation_id ) ) {

							$license['info']['activations'][ $activation_ip_hash ]['ip'] = sanitize_text_field( $activation_ip );
							$license['info']['activations'][ $activation_ip_hash ]['id'] = sanitize_text_field( $activation_id );

							tux_su_update_db( array(
								'id'         => $license['id'],
								'user_id'    => $license['user_id'],
								'product_id' => $license['product_id'],
								'type'       => 'license',
								'info'       => $license['info'],
							) );

						}

						$updates[ $license_rule['product_id'] ]['package']     = $license_rule['info']['file_url'];
						$updates[ $license_rule['product_id'] ]['url']         = $license_rule['info']['product_url'];
						$updates[ $license_rule['product_id'] ]['new_version'] = $license_rule['info']['version'];
						$updates[ $license_rule['product_id'] ]['autoupdate']  = 1 === (int) $license_rule['info']['autoupdate'] ? true : false;
						$updates[ $license_rule['product_id'] ]['tested']      = $license_rule['info']['compatible'];

						if ( $expiry < 1 ) {

							$updates[ $license_rule['product_id'] ]['expires'] = -1;

						} else {

							$updates[ $license_rule['product_id'] ]['expires'] = ceil( ( strtotime( $license['created'] . ' + ' . $expiry . ' days' ) - $current_time ) / DAY_IN_SECONDS );

						}
					}
				} else {

					$delete_ids[] = $license['id'];

				} // End if().
			} // End if().
		} // End foreach().

		if ( ! empty( $delete_ids ) ) {

			tux_su_delete_db( $delete_ids );

		}
	} else {

		$updates = new WP_Error( 'ID_VERSION_MISMATCH', esc_html( 'Count of product ids does not match count of versions.', 'tuxedo-software-updater' ), array( 'status' => 200 ) );

	} // End if().

	if ( ! empty( $tux_su_settings['tracking'] ) ) {

		tux_su_update_db( array(
			'user_id' => empty( $user['id'] ) ? 0 : $user['id'],
			'info'    => array(
				'ip'         => sanitize_text_field( $activation_ip ),
				'request'    => array(
					'update_key'    => sanitize_text_field( $update_key ),
					'ids'           => implode( ',', $product_ids ),
					'versions'      => implode( ',', $product_versions ),
					'activation_id' => $activation_id,
				),
				'user_agent' => sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ),
				'response'   => $updates,
			),
		), 'tracking' );

	}

	return $updates;

}
