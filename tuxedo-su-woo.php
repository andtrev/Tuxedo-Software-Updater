<?php
/**
 * Tuxedo Software Update Licensing WooCommerce support.
 *
 * WooCommerce account tabs and license processing.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

/**
 * Filter WooCommerce account tabs.
 *
 * Add 'Update Licenses' to WooCommerce account tabs.
 *
 * @since 1.0.0
 *
 * @param array $items Account tab items.
 *
 * @return array
 */
function tux_su_woo_my_account_tabs( $items ) {

	$position = array_search( 'downloads', array_keys( $items ), true ) + 1;
	$array    = array_slice( $items, 0, $position, true );
	$array    += array( 'update-licenses' => __( 'Update Licenses', 'tuxedo-software-updater' ), );
	$array    += array_slice( $items, $position, count( $items ) - $position, true );

	return $array;

}

add_filter( 'woocommerce_account_menu_items', 'tux_su_woo_my_account_tabs' );

/**
 * Add update-license endpoint.
 *
 * @since 1.0.0
 */
function tux_su_woo_add_endpoints() {

	add_rewrite_endpoint( 'update-licenses', EP_ROOT | EP_PAGES );

}

add_action( 'init', 'tux_su_woo_add_endpoints' );

/**
 * Add update-license query var.
 *
 * @since 1.0.0
 *
 * @param array $vars Query vars.
 *
 * @return array
 */
function tux_su_woo_add_query_vars( $vars ) {

	$vars[] = 'update-licenses';

	return $vars;

}

add_filter( 'query_vars', 'tux_su_woo_add_query_vars', 0 );

/**
 * Add 'Update Licenses' title to endpoint.
 *
 * @since 1.0.0
 *
 * @param string $title Title.
 *
 * @return string|void
 */
function tux_su_woo_endpoint_title( $title ) {

	global $wp_query;

	$is_endpoint = isset( $wp_query->query_vars['update-licenses'] );

	if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {

		$title = __( 'Update Licenses', 'tuxedo-software-updater' );

		remove_filter( 'the_title', 'tux_su_woo_endpoint_title' );

	}

	return $title;

}

add_filter( 'the_title', 'tux_su_woo_endpoint_title' );

/**
 * WooCommerce update licensing account page.
 *
 * @since 1.0.0
 */
function tux_su_woo_endpoint_content() {

	if ( ! is_user_logged_in() ) {

		return;

	}

	$update_key = get_user_meta( get_current_user_id(), '_tux_su_update_key', true );

	if ( empty( $update_key ) ) {

		$update_key = tux_su_generate_update_key( get_current_user_id() );

	}

	if ( strpos( $update_key, '-disabled' ) !== false ) {

		esc_html_e( 'You\'re update key has been disabled. Please contact us with any questions or for more information.', 'tuxedo-software-updater' );

		return;

	}

	?>
	<table class="shop_table tux-su-table">
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
		<table class="shop_table tux-su-table">
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

}

add_action( 'woocommerce_account_update-licenses_endpoint', 'tux_su_woo_endpoint_content' );

/**
 * Create a license when an order is completed.
 *
 * @since 1.0.0
 *
 * @param int $order_id Order ID.
 */
function tux_su_woo_create_license_on_order_completed( $order_id ) {

	$order          = new WC_Order( $order_id );
	$user_id        = $order->get_customer_id();
	$order_items    = $order->get_items();
	$product_ids    = array();
	$order_note     = '';
	$date_completed = $order->get_date_completed();

	if ( empty( $date_completed ) ) {

		return;

	}

	if ( null !== $date_completed ) {

		$date_completed = $date_completed->date( 'Y-m-d' );

	}

	foreach ( $order_items as $order_item ) {

		$product_ids[] = $order_item['product_id'];

	}

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

			if ( (int) $order_item['product_id'] === (int) $license['product_id'] ) {

				// Is rule.
				if ( 1 === (int) $license['type'] && empty( $license_rule ) ) {

					$license['info'] = maybe_unserialize( $license['info'] );
					$license_rule    = $license;
					continue;

				}

				// Is license.
				if ( 2 === (int) $license['type'] && empty( $license_exists ) ) {

					$license['info'] = maybe_unserialize( $license['info'] );

					if ( isset( $license['info']['order_id'] ) && (int) $license['info']['order_id'] === (int) $order_id ) {

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
				'product_id' => $order_item['product_id'],
				'type'       => 'license',
				'info'       => array(
					'child_id'    => isset( $order_item['variation_id'] ) ? (int) $order_item['variation_id'] : 0,
					'order_id'    => absint( $order_id ),
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

		$order->add_order_note( $order_note );

	}
}

add_action( 'woocommerce_order_status_completed', 'tux_su_woo_create_license_on_order_completed' );
