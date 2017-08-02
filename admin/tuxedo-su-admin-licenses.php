<?php
/**
 * Tuxedo Software Update Licensing licenses admin page.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

/**
 * Class Tux_SU_Licenses_List.
 */
require_once( 'class-tux-su-licenses-list.php' );

/**
 * Display license admin page.
 *
 * @since 1.0.0
 */
function tux_su_licenses_admin_page() {

	if ( ! current_user_can( 'administrator' ) ) {

		return;

	}

	if ( isset( $_GET['action'], $_GET['edit_id'] ) && 'edit' === $_GET['action'] ) {

		$license = tux_su_get_db( array(
			'id' => absint( $_GET['edit_id'] ),
		) );

		$license = reset( $license );

		if ( isset( $license['info'] ) ) {

			$license['info'] = maybe_unserialize( $license['info'] );

		}

		$license['info']['product_name'] = '';

		if ( isset( $license['product_id'] ) ) {

			$license_rule = tux_su_get_db( array(
				'product_id' => $license['product_id'],
				'type'       => 'rule',
			) );

			$license_rule = reset( $license_rule );

			if ( isset( $license_rule['info'] ) ) {

				$license_rule['info'] = maybe_unserialize( $license_rule['info'] );

				if ( isset( $license_rule['info']['product_name'] ) ) {

					$license['info']['product_name'] = $license_rule['info']['product_name'];

				}
			}

			unset( $license_rule );

		}
	} // End if().

	$list_table = new Tux_SU_Licenses_List();
	?>
	<div class="wrap">
		<?php if ( isset( $license['id'] ) ) : ?>
			<h1 class="wp-heading-inline">
				<?php esc_html_e( 'Edit License', 'tuxedo-software-updater' ); ?>
			</h1>
		<?php else : ?>
			<h1 class="wp-heading-inline">
				<?php esc_html_e( 'Licenses', 'tuxedo-software-updater' ); ?>
			</h1>
			<a href="javascript:void(0);" class="page-title-action" onclick="jQuery('#licenses-add-new-container').slideToggle();">
				<?php esc_html_e( 'Add New', 'tuxedo-software-updater' ); ?>
			</a>
		<?php endif; ?>
		<hr class="wp-header-end">

		<?php
		if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) {

			if ( ! isset( $_GET['edit_id'] ) || empty( $license ) || $license === false ) {

				echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Error editing license.', 'tuxedo-software-updater' ) . '</p></div>';
				echo '</div>';

				return;

			}
		}

		if ( isset( $_GET['updated'] ) && 'success' === $_GET['updated'] ) {

			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Successfully updated license.', 'tuxedo-software-updater' ) . '</p></div>';

		}

		if ( isset( $_GET['updated'] ) && 'error' === $_GET['updated'] ) {

			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Error updating license.', 'tuxedo-software-updater' ) . '</p></div>';

		}

		if ( isset( $_GET['deleted'] ) && 'success' === $_GET['deleted'] ) {

			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Successfully deleted license(s).', 'tuxedo-software-updater' ) . '</p></div>';

		}

		if ( isset( $_GET['deleted'] ) && 'error' === $_GET['deleted'] ) {

			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Error deleting license(s).', 'tuxedo-software-updater' ) . '</p></div>';

		}

		$_SERVER['REQUEST_URI'] = remove_query_arg( array(
			'updated',
			'deleted',
			'_wpnonce',
			'_wp_http_referer',
		), $_SERVER['REQUEST_URI'] );
		?>
		<div id="poststuff">
			<div id="licenses-add-new-container" class="postbox-container" <?php if ( ! isset( $license['id'] ) ) : ?>style="display:none;"<?php endif; ?>>
				<div id="licenses-add-new" class="postbox">
					<h2>
						<span>
							<?php if ( isset( $license['id'] ) ) : ?>
								<?php echo esc_html( $license['id'] . ' - ' . $license['info']['product_name'] ); ?>
							<?php else : ?>
								<?php esc_html_e( 'Add New License', 'tuxedo-software-updater' ); ?>
							<?php endif; ?>
						</span>
					</h2>
					<div class="inside">
						<form action="<?php echo esc_url( admin_url( 'admin.php?page=tuxedo-su-licenses' ) ); ?>" method="post">
							<p>
								<label for="tux_user_id">
									<?php esc_html_e( 'User ID:', 'tuxedo-software-updater' ); ?>
								</label><br>
								<input type="text" id="tux_user_id" name="tux_user_id" style="width:300px;" value="<?php echo ( isset( $license['user_id'] ) ) ? esc_attr( $license['user_id'] ) : ''; ?>">
							</p>
							<p>
								<label for="tux_product_id">
									<?php esc_html_e( 'Product ID:', 'tuxedo-software-updater' ); ?>
								</label><br>
								<input type="text" id="tux_product_id" name="tux_product_id" style="width:300px;" value="<?php echo ( isset( $license['product_id'] ) ) ? esc_attr( $license['product_id'] ) : ''; ?>">
							</p>
							<p>
								<label for="tux_child_id">
									<?php esc_html_e( 'Child Product ID:', 'tuxedo-software-updater' ); ?>
								</label><br>
								<input type="text" id="tux_child_id" name="tux_child_id" style="width:300px;" value="<?php echo ( isset( $license['info']['child_id'] ) ) ? esc_attr( $license['info']['child_id'] ) : ''; ?>">
							</p>
							<p>
								<label for="tux_order_id">
									<?php esc_html_e( 'Order ID:', 'tuxedo-software-updater' ); ?>
								</label><br>
								<input type="text" id="tux_order_id" name="tux_order_id" style="width:300px;" value="<?php echo ( isset( $license['info']['order_id'] ) ) ? esc_attr( $license['info']['order_id'] ) : ''; ?>">
							</p>
							<p>
								<?php
								if ( isset( $license['created'] ) ) {
									$year  = date( 'Y', strtotime( $license['created'] ) );
									$month = date( 'm', strtotime( $license['created'] ) );
									$day   = date( 'd', strtotime( $license['created'] ) );
								} else {
									$year  = current_time( 'Y' );
									$month = current_time( 'm' );
									$day   = current_time( 'd' );
								}
								?>
								<label>
									<?php esc_html_e( 'Created On:', 'tuxedo-software-updater' ); ?>
								</label><br>
								<input type="text" id="tux_created_year" name="tux_created_year" style="width:60px;" value="<?php echo esc_attr( $year ); ?>">
								<input type="text" id="tux_created_month" name="tux_created_month" style="width:35px;" value="<?php echo esc_attr( $month ); ?>">
								<input type="text" id="tux_created_day" name="tux_created_day" style="width:35px;" value="<?php echo esc_attr( $day ); ?>"><br>
								<span class="description">
									<?php esc_html_e( 'Year - Month - Day', 'tuxedo-software-updater' ); ?>
								</span>
							</p>
							<?php if ( isset( $license['id'], $license['info']['activations'] ) && count( $license['info']['activations'] ) > 0 ) : ?>
								<hr>
								<h2 style="padding-left:0;padding-right:0;">
									<?php esc_html_e( 'Activations', 'tuxedo-software-updater' ); ?>
								</h2>
								<p>
								<table class="wp-list-table widefat striped">
									<thead>
									<tr>
										<th style="width:50px;">
											<?php esc_html_e( 'Delete', 'tuxedo-software-updater' ); ?>
										</th>
										<th style="width:45%;">
											<?php esc_html_e( 'ID', 'tuxedo-software-updater' ); ?>
										</th>
										<th>
											<?php esc_html_e( 'IP Address', 'tuxedo-software-updater' ); ?>
										</th>
									</tr>
									</thead>
									<tbody>
									<?php foreach ( $license['info']['activations'] as $key => $activation ) : ?>
										<tr>
											<td style="width:50px;">
												<input name="activation_delete[]" type="checkbox" value="<?php echo esc_attr( $key ); ?>">
											</td>
											<td style="width:45%;">
												<?php echo esc_html( $activation['id'] ); ?>
											</td>
											<td>
												<?php echo esc_html( $activation['ip'] ); ?>
											</td>
										</tr>
									<?php endforeach; ?>
									</tbody>
								</table>
								</p>
							<?php endif; ?>
							<p>
								<?php wp_nonce_field( 'tux_create_edit_license', 'tux_su_nonce' ); ?>
								<br>
								<?php if ( isset( $license['id'] ) ) : ?>
									<input type="hidden" id="tux_id" name="tux_id" value="<?php echo esc_attr( $license['id'] ); ?>">
									<input type="submit" class="button button-primary button-large" name="tux_create_license" value="<?php esc_attr_e( 'Update License', 'tuxedo-software-updater' ); ?>">
									&nbsp;
									<a href="<?php echo esc_url( admin_url( 'admin.php?page=tuxedo-su-licenses' ) ); ?>" class="button button-large">
										<?php esc_html_e( 'Cancel', 'tuxedo-software-updater' ); ?>
									</a>
								<?php else : ?>
									<input type="submit" class="button button-primary button-large" name="tux_create_license" value="<?php esc_attr_e( 'Create License', 'tuxedo-software-updater' ); ?>">
									&nbsp;
									<button class="button button-large" onclick="jQuery('#licenses-add-new-container').slideToggle();">
										<?php esc_html_e( 'Cancel', 'tuxedo-software-updater' ); ?>
									</button>
								<?php endif; ?>
							</p>
						</form>
					</div>
				</div>
			</div>

			<?php if ( ! isset( $_GET['action'] ) || 'edit' !== $_GET['action'] ) : ?>
				<form method="get">
					<input type="hidden" name="page" value="tuxedo-su-licenses">
					<?php
					$list_table->prepare_items();
					$list_table->search_box( __( 'Search', 'tuxedo-software-updater' ), 'tux-su' );
					$list_table->display();
					?>
				</form>
			<?php endif; ?>
		</div>
	</div>
	<?php

}

/**
 * Handle new license creation.
 *
 * @since 1.0.0
 */
function tux_su_licenses_admin_create() {

	if ( ! current_user_can( 'administrator' ) || ! isset( $_POST['tux_su_nonce'], $_POST['tux_product_id'], $_POST['tux_user_id'] ) || ! wp_verify_nonce( $_POST['tux_su_nonce'], 'tux_create_edit_license' ) ) {

		return;

	}

	$user_id   = absint( $_POST['tux_user_id'] );
	$user      = get_userdata( $user_id );
	$user_name = '';

	if ( false !== $user ) {

		$user_name = $user->user_login;

	}

	if ( isset( $_POST['tux_created_year'], $_POST['tux_created_month'], $_POST['tux_created_day'] ) ) {

		$year  = absint( $_POST['tux_created_year'] );
		$month = absint( $_POST['tux_created_month'] );
		$day   = absint( $_POST['tux_created_day'] );

	} else {

		$year  = current_time( 'Y' );
		$month = current_time( 'm' );
		$day   = current_time( 'd' );

	}

	$activations = array();

	if ( isset( $_POST['tux_id'] ) ) {

		$license = tux_su_get_db( array(
			'id' => $_POST['tux_id'],
		) );

		$license = reset( $license );

		if ( isset( $license['info'] ) ) {

			$license['info'] = maybe_unserialize( $license['info'] );

		}

		if ( isset( $license['info']['activations'] ) ) {

			$activations = $license['info']['activations'];

		}
	}

	if ( isset( $_POST['activation_delete'] ) ) {

		foreach ( $_POST['activation_delete'] as $activation_delete ) {

			unset( $activations[ $activation_delete ] );

		}
	}

	$tux_insert = tux_su_update_db( array(
		'id'         => isset( $_POST['tux_id'] ) ? $_POST['tux_id'] : 0,
		'user_id'    => $user_id,
		'product_id' => $_POST['tux_product_id'],
		'type'       => 'license',
		'info'       => array(
			'child_id'    => ! empty( $_POST['tux_child_id'] ) ? absint( $_POST['tux_child_id'] ) : 0,
			'order_id'    => isset( $_POST['tux_order_id'] ) ? absint( $_POST['tux_order_id'] ) : 0,
			'user_name'   => wp_strip_all_tags( $user_name ),
			'activations' => $activations,
		),
		'created'    => date( 'Y-m-d', strtotime( "{$year}-{$month}-{$day}" ) ),
	) );

	$sendback = admin_url( 'admin.php?page=tuxedo-su-licenses' );

	if ( false === $tux_insert ) {

		$sendback = add_query_arg( 'updated', 'error', $sendback );

	} else {

		$sendback = add_query_arg( 'updated', 'success', $sendback );

	}

	wp_redirect( $sendback );
	exit();

}

/**
 * Add styles output function to admin_print_styles action.
 *
 * @since 1.0.0
 */
function tux_su_admin_styles_add_action_licenses() {

	add_action( 'admin_print_styles', 'tux_su_admin_styles_licenses' );

}

/**
 * Output css styles.
 *
 * @since 1.0.0
 */
function tux_su_admin_styles_licenses() {

	?>
	<style>
		table.licenses .row-actions {
			color: #999;
		}

		@media screen and (min-width: 783px) {
			table.licenses .column-activations,
			table.licenses .column-order_id,
			table.licenses .column-expires,
			table.licenses .column-created,
			table.licenses .column-modified {
				width: 150px;
			}
		}
	</style>
	<?php

}
