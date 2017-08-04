<?php
/**
 * Tuxedo Software Update Licensing Easy Digital Downloads support.
 *
 * EDD license account viewing and processing.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

/**
 * EDD update licensing account page shortcode.
 *
 * @since 1.0.0
 *
 * @param array $atts Attribute values, unused.
 *
 * @return string
 */
function tux_su_edd_licensing_shortcode( $atts ) {

	if ( ! is_user_logged_in() ) {

		return '';

	}

	$update_key = get_user_meta( get_current_user_id(), '_tux_su_update_key', true );

	if ( empty( $update_key ) ) {

		$update_key = tux_su_generate_update_key( get_current_user_id() );

	}

	if ( strpos( $update_key, '-disabled' ) !== false ) {

		return esc_html__( 'You\'re update key has been disabled. Please contact us with any questions or for more information.', 'tuxedo-software-updater' );

	}

	ob_start();
	?>
	<table class="edd-table tux-su-table">
		<thead>
		<tr>
			<th>
				<?php esc_html_e( 'Update Key', 'tuxedo-software-updater' ); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>
				<?php echo esc_html( $update_key ); ?>
			</td>
		</tr>
		</tbody>
	</table>
	<?php

	$licenses = tux_su_get_db( array(
		'user_id' => get_current_user_id(),
		'type'    => 'license',
	) );

	if ( empty( $licenses ) ) {

		return;

	}

	$product_ids = array();

	foreach ( $licenses as $license ) {

		$product_ids[] = absint( $license['product_id'] );

	}

	$license_rules = array();

	if ( count( $product_ids ) > 0 ) {

		$license_rules = tux_su_get_db( array(
			'product_id' => $product_ids,
			'type'       => 'rule',
		) );

	}

	$delete_ids = array();

	foreach ( $licenses as $license ) {

		?>
		<table class="edd-table tux-su-table">
		<thead>
		<?php

		if ( isset( $license['info'] ) ) {

			$license['info'] = maybe_unserialize( $license['info'] );

		}

		$product_name     = '';
		$activation_limit = 0;
		$expiry           = 0;

		foreach ( $license_rules as $license_rule ) {

			if ( $license_rule['product_id'] === $license['product_id'] ) {

				$license_rule['info'] = maybe_unserialize( $license_rule['info'] );

				if ( isset( $license_rule['info']['product_name'] ) ) {

					$product_name = $license_rule['info']['product_name'];

				}

				if ( ! empty( $license['info']['child_id'] ) ) {

					if ( ! empty( $license_rule['info']['children'][ $license['info']['child_id'] ]['name'] ) ) {

						$product_name .= ' ' . $license_rule['info']['children'][ $license['info']['child_id'] ]['name'];

					}

					if ( isset( $license_rule['info']['children'][ $license['info']['child_id'] ]['activation_limit'] ) ) {

						$activation_limit = $license_rule['info']['children'][ $license['info']['child_id'] ]['activation_limit'];

					}

					if ( isset( $license_rule['info']['children'][ $license['info']['child_id'] ]['expiry'] ) ) {

						$expiry = $license_rule['info']['children'][ $license['info']['child_id'] ]['expiry'];

					}
				} else {

					if ( isset( $license_rule['info']['activation_limit'] ) ) {

						$activation_limit = $license_rule['info']['activation_limit'];

					}

					if ( isset( $license_rule['info']['expiry'] ) ) {

						$expiry = $license_rule['info']['expiry'];

					}
				}

				break;

			} // End if().
		} // End foreach().

		if ( ! empty( $expiry ) && current_time( 'timestamp' ) > strtotime( $license['created'] . ' + ' . $expiry . ' days' ) ) {

			$delete_ids[] = $license['id'];
			continue;

		}

		if ( isset( $_GET['delnonce'], $_GET['action'], $_GET['license_id'], $_GET['activation_id'], $license['info']['activations'] ) && 'delete' === $_GET['action'] && absint( $license['id'] ) === absint( $_GET['license_id'] ) && wp_verify_nonce( $_GET['delnonce'], 'tux_delete_activation' ) ) {

			unset( $license['info']['activations'][ sanitize_text_field( $_GET['activation_id'] ) ] );

			tux_su_update_db( array(
				'id'         => $license['id'],
				'user_id'    => $license['user_id'],
				'product_id' => $license['product_id'],
				'type'       => 'license',
				'info'       => $license['info'],
			) );

		}

		?>
		<tr>
			<th colspan="2">
				<?php echo esc_html( $product_name ); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td style="width:60%;">
				<?php esc_html_e( 'Expires', 'tuxedo-software-updater' ); ?>
			</td>
			<td style="width:40%;">
				<?php echo esc_html( isset( $license['created'] ) ? ( empty( $expiry ) ? __( 'never', 'tuxedo-software-updater' ) : ( $expiry - ( floor( ( current_time( 'timestamp' ) - strtotime( $license['created'] ) ) / DAY_IN_SECONDS ) ) ) . ' ' . __( 'days', 'tuxedo-software-updater' ) ) : __( 'error', 'tuxedo-software-updater' ) ); ?>
			</td>
		</tr>
		<tr>
			<td style="width:60%;">
				<?php esc_html_e( 'Activations', 'tuxedo-software-updater' ); ?>
			</td>
			<td style="width:40%;">
				<?php echo esc_html( empty( $activation_limit ) ? __( 'unlimited', 'tuxedo-software-updater' ) : count( isset( $license['info']['activations'] ) ? $license['info']['activations'] : array() ) . ' / ' . $activation_limit ); ?>
			</td>
		</tr>
		<?php $activation_count = 1;
		foreach ( $license['info']['activations'] as $key => $activation ) : ?>
			<tr>
				<td style="width:60%;">
					<?php echo esc_html( $activation_count ); ?>. <?php echo esc_html( $activation['id'] ); ?>
				</td>
				<td style="width:40%;">
					<a href="?action=delete&license_id=<?php echo esc_attr( $license['id'] ); ?>&activation_id=<?php echo esc_attr( $key ); ?>&delnonce=<?php echo esc_attr( wp_create_nonce( 'tux_delete_activation' ) ); ?>" onclick="return confirm('<?php esc_attr_e( 'Delete this activation?', 'tuxedo-software-updater' ); ?>">
						<?php esc_html_e( 'Delete', 'tuxedo-software-updater' ); ?>
					</a>
				</td>
			</tr>
			<?php $activation_count ++;
		endforeach;

		echo '</tbody></table>';

	} // End foreach().

	if ( ! empty( $delete_ids ) ) {

		tux_su_delete_db( $delete_ids );

	}

	return ob_get_clean();

}

add_shortcode( 'update_licenses', 'tux_su_edd_licensing_shortcode' );

/**
 * Create a license when an order is completed.
 *
 * @since 1.0.0
 *
 * @param int $payment_id Payment ID.
 */
function tux_su_edd_create_license_on_order_completed( $payment_id ) {

	$order          = new EDD_Payment( $payment_id );
	$user_id        = $order->customer_id;
	$order_items    = $order->cart_details;
	$product_ids    = array();
	$order_note     = '';
	$date_completed = $order->completed_date;

	if ( empty( $date_completed ) ) {

		return;

	}

	$date_completed = date( 'Y-m-d', strtotime( $date_completed ) );

	$all_bundled_items = array();

	foreach ( $order_items as $item_index => $order_item ) {

		$item_type = edd_get_download_type( $order_item['id'] );

		if ( 'bundle' === $item_type ) {

			$get_bundled_items = edd_get_bundled_products( $order_item['id'] );
			$bundled_items     = array();

			foreach ( $get_bundled_items as $bundled_item ) {

				$bundled_item_id = explode( '_', $bundled_item );

				if ( isset( $bundled_item_id[1] ) ) {

					$bundled_item_id[1] = array(
						'item_number' => array(
							'options' => array(
								'price_id' => $bundled_item_id[1],
							),
						),
					);

				} else {

					$bundled_item_id[1] = array();

				}

				$bundled_items[] = array_merge( array(
					'id'   => $bundled_item_id[0],
					'name' => get_the_title( $bundled_item_id[0] ),
				), $bundled_item_id[1] );

				$product_ids[] = $bundled_item_id[0];

			}

			$all_bundled_items = array_merge( $all_bundled_items, $bundled_items );

		} else {

			$product_ids[] = $order_item['id'];

		} // End if().
	} // End foreach().

	$order_items = array_merge( $order_items, $all_bundled_items );

	$licenses = tux_su_get_db( array(
		'user_id'    => array( 0, $user_id ),
		'product_id' => $product_ids,
		'order'      => 'type ASC',
	) );

	foreach ( $order_items as $order_item ) {

		$license_rule      = array();
		$license_exists    = array();
		$license_update_id = false;

		foreach ( $licenses as $license ) {

			if ( (int) $order_item['id'] === (int) $license['product_id'] ) {

				// Is rule.
				if ( 1 === (int) $license['type'] && empty( $license_rule ) ) {

					$license['info'] = maybe_unserialize( $license['info'] );
					$license_rule    = $license;
					continue;

				}

				// Is license.
				if ( 2 === (int) $license['type'] && empty( $license_exists ) ) {

					$license['info'] = maybe_unserialize( $license['info'] );

					if ( isset( $license['info']['order_id'] ) && (int) $license['info']['order_id'] === (int) $payment_id ) {

						$license_exists = $license;
						break;

					}
				}
			}
		}

		if ( ! empty( $license_rule['info']['open_update'] ) ) {

			continue;

		}

		if ( ! empty( $license_rule ) && empty( $license_exists ) ) {

			$user      = get_userdata( $user_id );
			$user_name = $user->user_login;

			$license_update_id = tux_su_update_db( array(
				'user_id'    => $user_id,
				'product_id' => $order_item['id'],
				'type'       => 'license',
				'info'       => array(
					'child_id'    => isset( $order_item['item_number']['options']['price_id'] ) ? (int) $order_item['item_number']['options']['price_id'] : 0,
					'order_id'    => absint( $payment_id ),
					'user_name'   => wp_strip_all_tags( $user_name ),
					'activations' => array(),
				),
				'created'    => $date_completed,
			) );

		}

		if ( false !== $license_update_id ) {

			if ( empty( $order_note ) ) {

				$order_note = __( 'Tuxedo Software Update Licensing', 'tuxedo-software-updater' ) . "\n";

			}

			/* translators: %s: order item name */
			$order_note .= '* ' . sprintf( __( 'Updated license for %s.', 'tuxedo-software-updater' ), wp_strip_all_tags( $order_item['name'] ) ) . "\n";

		} elseif ( ! empty( $license_rule ) && empty( $license_exists ) ) {

			if ( empty( $order_note ) ) {

				$order_note = __( 'Tuxedo Software Update Licensing', 'tuxedo-software-updater' ) . "\n";

			}

			/* translators: %s: order item name */
			$order_note .= '* ' . sprintf( __( 'Error updating license for %s.', 'tuxedo-software-updater' ), wp_strip_all_tags( $order_item['name'] ) ) . "\n";

		}
	} // End foreach().

	if ( ! empty( $order_note ) ) {

		edd_insert_payment_note( $payment_id, $order_note );

	}

}

add_action( 'edd_complete_purchase', 'tux_su_edd_create_license_on_order_completed' );
