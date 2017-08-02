<?php
/**
 * Tuxedo Software Update Licensing rules admin page.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

/**
 * Class Tux_SU_License_Rules_List.
 */
require_once( 'class-tux-su-license-rules-list.php' );

/**
 * Display license admin page.
 *
 * @since 1.0.0
 */
function tux_su_license_rules_admin_page() {

	if ( ! current_user_can( 'administrator' ) ) {

		return;

	}

	if ( isset( $_GET['action'], $_GET['edit_id'] ) && 'edit' === $_GET['action'] ) {

		$license_rule = tux_su_get_db( array(
			'id' => absint( $_GET['edit_id'] ),
		) );

		$license_rule = reset( $license_rule );

		if ( isset( $license_rule['info'] ) ) {

			$license_rule['info'] = maybe_unserialize( $license_rule['info'] );

		}
	}

	$list_table = new Tux_SU_License_Rules_List();
	?>
	<div class="wrap">
		<?php if ( isset( $license_rule['id'] ) ) : ?>
			<h1 class="wp-heading-inline">
				<?php esc_html_e( 'Edit License Rule', 'tuxedo-software-updater' ); ?>
			</h1>
		<?php else : ?>
			<h1 class="wp-heading-inline">
				<?php esc_html_e( 'License Rules', 'tuxedo-software-updater' ); ?>
			</h1>
			<a href="javascript:void(0);" class="page-title-action" onclick="jQuery('#license-rules-add-new-container').slideToggle();">
				<?php esc_html_e( 'Add New', 'tuxedo-software-updater' ); ?>
			</a>
		<?php endif; ?>
		<hr class="wp-header-end">

		<?php
		if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) {

			if ( ! isset( $_GET['edit_id'] ) || empty( $license_rule ) || $license_rule === false ) {

				echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Error editing license rule.', 'tuxedo-software-updater' ) . '</p></div>';
				echo '</div>';

				return;

			}
		}

		if ( isset( $_GET['updated'] ) && 'success' === $_GET['updated'] ) {

			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Successfully updated license rule.', 'tuxedo-software-updater' ) . '</p></div>';

		}

		if ( isset( $_GET['updated'] ) && 'error' === $_GET['updated'] ) {

			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Error updating license rule.', 'tuxedo-software-updater' ) . '</p></div>';

		}

		if ( isset( $_GET['deleted'] ) && 'success' === $_GET['deleted'] ) {

			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Successfully deleted license rule(s).', 'tuxedo-software-updater' ) . '</p></div>';

		}

		if ( isset( $_GET['deleted'] ) && 'error' === $_GET['deleted'] ) {

			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Error deleting license rule(s).', 'tuxedo-software-updater' ) . '</p></div>';

		}

		$_SERVER['REQUEST_URI'] = remove_query_arg( array(
			'updated',
			'deleted',
			'_wpnonce',
			'_wp_http_referer',
		), $_SERVER['REQUEST_URI'] );
		?>
		<div id="poststuff">
			<div id="license-rules-add-new-container" class="postbox-container" <?php if ( ! isset( $license_rule['id'] ) ) : ?>style="display:none;"<?php endif; ?>>
				<div id="license-rules-add-new" class="postbox">
					<h2>
						<span>
							<?php if ( isset( $license_rule['id'] ) ) : ?>
								<?php echo esc_html( $license_rule['id'] . ' - ' . $license_rule['info']['product_name'] ); ?>
							<?php else : ?>
								<?php esc_html_e( 'Add New License Rule', 'tuxedo-software-updater' ); ?>
							<?php endif; ?>
						</span>
					</h2>
					<div class="inside">
						<form action="<?php echo esc_url( admin_url( 'admin.php?page=tuxedo-su-license-rules' ) ); ?>"
							  method="post">
							<p>
								<label for="tux_file_url">
									<?php esc_html_e( 'Update File URL:', 'tuxedo-software-updater' ); ?>
								</label><br>
								<input type="text" id="tux_file_url" name="tux_file_url" style="width:99%;" value="<?php echo ( isset( $license_rule['info']['file_url'] ) ) ? esc_attr( $license_rule['info']['file_url'] ) : ''; ?>"><br>
								<button class="button" id="tux_file_url_button">
									<?php esc_html_e( 'Select or Upload File', 'tuxedo-software-updater' ); ?>
								</button>
							</p>
							<p>
								<label for="tux_version">
									<?php esc_html_e( 'Version:', 'tuxedo-software-updater' ); ?>
								</label><br>
								<input type="text" id="tux_version" name="tux_version" style="width:300px;" value="<?php echo ( isset( $license_rule['info']['version'] ) ) ? esc_attr( $license_rule['info']['version'] ) : ''; ?>">
							</p>
							<p>
								<label for="tux_compatible">
									<?php esc_html_e( 'Compatible with WordPress Version:', 'tuxedo-software-updater' ); ?>
								</label><br>
								<input type="text" id="tux_compatible" name="tux_compatible" style="width:300px;" value="<?php echo ( isset( $license_rule['info']['compatible'] ) ) ? esc_attr( $license_rule['info']['compatible'] ) : ''; ?>">
							</p>
							<p>
								<input type="hidden" id="tux_autoupdate_hidden" name="tux_autoupdate" value="0">
								<label for="tux_autoupdate">
									<input type="checkbox" id="tux_autoupdate" name="tux_autoupdate" value="1" <?php if ( isset( $license_rule['info']['autoupdate'] ) ) : checked( $license_rule['info']['autoupdate'], 1 ); endif; ?>>
									<?php esc_html_e( 'Automatic Update', 'tuxedo-software-updater' ); ?>
								</label>
							</p>
							<p>
								<input type="hidden" id="tux_open_update_hidden" name="tux_open_update" value="0">
								<label for="tux_open_update">
									<input type="checkbox" id="tux_open_update" name="tux_open_update" value="1" <?php if ( isset( $license_rule['info']['open_update'] ) ) : checked( $license_rule['info']['open_update'], 1 ); endif; ?>>
									<?php esc_html_e( 'Open Update', 'tuxedo-software-updater' ); ?>
								</label><br>
								<span class="description">
									<?php esc_html_e( 'Open updates do not require a license.', 'tuxedo-software-updater' ); ?>
								</span>
							</p>
							<p>
								<label for="tux_product_id">
									<?php esc_html_e( 'Product ID:', 'tuxedo-software-updater' ); ?>
								</label><br>
								<input type="text" id="tux_product_id" name="tux_product_id" style="width:300px;" value="<?php echo ( isset( $license_rule['product_id'] ) ) ? esc_attr( $license_rule['product_id'] ) : ''; ?>">
							</p>
							<p>
								<label for="tux_product_name">
									<?php esc_html_e( 'Product Name:', 'tuxedo-software-updater' ); ?>
								</label><br>
								<input type="text" id="tux_product_name" name="tux_product_name" style="width:300px;" value="<?php echo ( isset( $license_rule['info']['product_name'] ) ) ? esc_attr( $license_rule['info']['product_name'] ) : ''; ?>"><br>
								<span class="description">
									<?php esc_html_e( 'Human readable product name for easy identification purposes.', 'tuxedo-software-updater' ); ?>
								</span>
							</p>
							<p>
								<label for="tux_product_url">
									<?php esc_html_e( 'Product URL:', 'tuxedo-software-updater' ); ?>
								</label><br>
								<input type="text" id="tux_product_url" name="tux_product_url" style="width:300px;" value="<?php echo ( isset( $license_rule['info']['product_url'] ) ) ? esc_attr( $license_rule['info']['product_url'] ) : ''; ?>"><br>
							</p>
							<p>
								<label for="tux_activation_limit">
									<?php esc_html_e( 'Activation Limit:', 'tuxedo-software-updater' ); ?>
								</label><br>
								<input type="text" id="tux_activation_limit" name="tux_activation_limit" style="width:300px;" value="<?php echo ( isset( $license_rule['info']['activation_limit'] ) ) ? esc_attr( $license_rule['info']['activation_limit'] ) : ''; ?>"><br>
								<span class="description">
									<?php esc_html_e( 'Leave blank or 0 for unlimited activations.', 'tuxedo-software-updater' ); ?>
								</span>
							</p>
							<p>
								<label for="tux_expiry">
									<?php esc_html_e( 'Expiry:', 'tuxedo-software-updater' ); ?>
								</label><br>
								<input type="text" id="tux_expiry" name="tux_expiry" style="width:300px;" value="<?php echo ( isset( $license_rule['info']['expiry'] ) ) ? esc_attr( $license_rule['info']['expiry'] ) : ''; ?>"><br>
								<span class="description">
									<?php esc_html_e( 'Enter the number of days before a license expires, or leave blank or 0 for never.', 'tuxedo-software-updater' ); ?>
								</span>
							</p>
							<?php
							$child_products = array();

							if ( ! empty( $license_rule['info']['children'] ) ) {

								$child_products = $license_rule['info']['children'];

							}

							if ( ! empty( $license_rule['product_id'] ) ) {
								if ( class_exists( 'WooCommerce' ) ) {

									$products = wc_get_products( array(
										'parent' => $license_rule['product_id'],
										'type'   => 'variation',
									) );

									$parent_product_name = get_the_title( $license_rule['product_id'] );

									foreach ( $products as $product ) {

										$child_products[ $product->get_id() ]['name']  = str_replace( $parent_product_name, '', $product->get_name() );
										$child_products[ $product->get_id() ]['found'] = true;

									}
								} elseif ( class_exists( 'Easy_Digital_Downloads' ) ) {

									$products = get_post_meta( $license_rule['product_id'], 'edd_variable_prices', true );

									if ( ! empty( $products ) ) {

										foreach ( $products as $index => $product ) {

											$child_products[ $index ]['name']  = '- ' . $product['name'];
											$child_products[ $index ]['found'] = true;

										}
									}
								}
							}

							if ( ! empty( $child_products ) ) : ?>
								<hr>
								<h2 style="padding-left:0;padding-right:0;"><?php esc_html_e( 'Child Products', 'tuxedo-software-updater' ); ?></h2>
								<?php foreach ( $child_products as $child_id => $child_product ) : ?>
									<p>
										<label><strong><?php echo esc_html( $child_id . ' ' . $child_product['name'] ); ?></strong></label>
										<input type="hidden" name="tux_child_ids[]" value="<?php echo esc_attr( $child_id ); ?>">
										<input type="hidden" name="tux_child_name_<?php echo esc_attr( $child_id ); ?>" value="<?php echo esc_attr( $child_product['name'] ); ?>">
									</p>
									<?php if ( empty( $child_product['found'] ) ) : ?>
										<p>
											<label style="color:red;"><strong><?php esc_html_e( 'Child product no longer exists.', 'tuxedo-softeare-updater' ); ?></strong></label>
											<br>
											<input type="hidden" name="tux_delete_child_<?php echo esc_attr( $child_id ); ?>" value="0">
											<label for="tux_delete_child_<?php echo esc_attr( $child_id ); ?>">
												<input type="checkbox" id="tux_delete_child_<?php echo esc_attr( $child_id ); ?>" name="tux_delete_child_<?php echo esc_attr( $child_id ); ?>" value="1">
												<?php esc_html_e( 'Delete', 'tuxedo-software-updater' ); ?>
											</label>
										</p>
									<?php endif; ?>
									<p>
										<label for="tux_activation_limit">
											<?php esc_html_e( 'Activation Limit:', 'tuxedo-software-updater' ); ?>
										</label><br>
										<input type="text" id="tux_activation_limit_<?php echo esc_attr( $child_id ); ?>" name="tux_activation_limit_<?php echo esc_attr( $child_id ); ?>" style="width:300px;" value="<?php echo ( isset( $child_product['activation_limit'] ) ) ? esc_attr( $child_product['activation_limit'] ) : ''; ?>"><br>
										<span class="description">
											<?php esc_html_e( 'Leave blank or 0 for unlimited activations.', 'tuxedo-software-updater' ); ?>
										</span>
									</p>
									<p>
										<label for="tux_expiry">
											<?php esc_html_e( 'Expiry:', 'tuxedo-software-updater' ); ?>
										</label><br>
										<input type="text" id="tux_expiry_<?php echo esc_attr( $child_id ); ?>" name="tux_expiry_<?php echo esc_attr( $child_id ); ?>" style="width:300px;" value="<?php echo ( isset( $child_product['expiry'] ) ) ? esc_attr( $child_product['expiry'] ) : ''; ?>"><br>
										<span class="description">
											<?php esc_html_e( 'Enter the number of days before a license expires, or leave blank or 0 for never.', 'tuxedo-software-updater' ); ?>
										</span>
									</p>
									<hr>
								<?php endforeach; ?>
							<?php endif; ?>
							<p>
								<?php wp_nonce_field( 'tux_create_edit_license_rule', 'tux_su_nonce' ); ?>
								<br>
								<?php if ( isset( $license_rule['id'] ) ) : ?>
									<input type="hidden" id="tux_id" name="tux_id" value="<?php echo esc_attr( $license_rule['id'] ); ?>">
									<input type="submit" class="button button-primary button-large" name="tux_create_license_rule" value="<?php esc_attr_e( 'Update License Rule', 'tuxedp-software-updater' ); ?>">
									&nbsp
									<a href="<?php echo esc_url( admin_url( 'admin.php?page=tuxedo-su-license-rules' ) ); ?>" class="button button-large">
										<?php esc_html_e( 'Cancel', 'tuxedo-software-updater' ); ?>
									</a>
								<?php else : ?>
									<input type="submit" class="button button-primary button-large" name="tux_create_license_rule" value="<?php esc_attr_e( 'Create License Rule', 'tuxedp-software-updater' ); ?>">
									&nbsp;
									<button class="button button-large" onclick="jQuery('#license-rules-add-new-container').slideToggle();">
										<?php esc_html_e( 'Cancel', 'tuxedo-software-updater' ); ?>
									</button>
								<?php endif; ?>
							</p>
						</form>
						<script>
							jQuery(document).ready(function ($) {
								var tuxedo_updater_media_frame;
								$('#tux_file_url_button').click(function (e) {
									e.preventDefault();
									if (tuxedo_updater_media_frame) {
										tuxedo_updater_media_frame.open();
										return;
									}
									tuxedo_updater_media_frame = wp.media.frames.tuxedo_updater_media_frame = wp.media({
										title: '<?php esc_html_e( 'Choose or Upload a File', 'tuxedo-updater' ); ?>',
										button: {text: '<?php esc_html_e( 'Choose', 'tuxedo-updater' ); ?>'},
										library: {type: 'application/zip'}
									});
									tuxedo_updater_media_frame.on('select', function () {
										var media_attachment = tuxedo_updater_media_frame.state().get('selection').first().toJSON();
										$('#tux_file_url').val(media_attachment.url);
									});
									tuxedo_updater_media_frame.open();
								});
							});
						</script>
					</div>
				</div>
			</div>

			<?php if ( ! isset( $_GET['action'] ) || 'edit' !== $_GET['action'] ) : ?>
				<form method="get">
					<input type="hidden" name="page" value="tuxedo-su-license-rules">
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
 * Handle new license rule creation.
 *
 * @since 1.0.0
 */
function tux_su_license_rules_admin_create() {

	if ( ! current_user_can( 'administrator' ) ) {

		return;

	}

	if ( isset( $_POST['tux_su_nonce'], $_POST['tux_file_url'], $_POST['tux_open_update'], $_POST['tux_version'], $_POST['tux_compatible'], $_POST['tux_product_url'], $_POST['tux_autoupdate'], $_POST['tux_product_id'], $_POST['tux_product_name'], $_POST['tux_activation_limit'], $_POST['tux_expiry'] ) && wp_verify_nonce( $_POST['tux_su_nonce'], 'tux_create_edit_license_rule' ) ) {

		$child_products = array();

		if ( ! empty( $_POST['tux_child_ids'] ) && is_array( $_POST['tux_child_ids'] ) ) {

			foreach ( $_POST['tux_child_ids'] as $child_id ) {

				$child_id = sanitize_text_field( $child_id );

				if ( empty( $_POST[ 'tux_delete_child_' . $child_id ] ) ) {

					$child_products[ $child_id ] = array(
						'name'             => isset( $_POST[ 'tux_child_name_' . $child_id ] ) ? sanitize_text_field( $_POST[ 'tux_child_name_' . $child_id ] ) : '',
						'activation_limit' => isset( $_POST[ 'tux_activation_limit_' . $child_id ] ) ? absint( $_POST[ 'tux_activation_limit_' . $child_id ] ) : 0,
						'expiry'           => isset( $_POST[ 'tux_expiry_' . $child_id ] ) ? absint( $_POST[ 'tux_expiry_' . $child_id ] ) : 0,
					);

				}
			}
		}

		$tux_insert = tux_su_update_db( array(
			'id'         => isset( $_POST['tux_id'] ) ? absint( $_POST['tux_id'] ) : 0,
			'product_id' => absint( $_POST['tux_product_id'] ),
			'type'       => 'rule',
			'info'       => array(
				'product_name'     => wp_strip_all_tags( sanitize_text_field( $_POST['tux_product_name'] ) ),
				'product_url'      => esc_url_raw( $_POST['tux_product_url'] ),
				'file_url'         => esc_url_raw( $_POST['tux_file_url'] ),
				'version'          => sanitize_text_field( $_POST['tux_version'] ),
				'compatible'       => sanitize_text_field( $_POST['tux_compatible'] ),
				'autoupdate'       => absint( $_POST['tux_autoupdate'] ),
				'open_update'      => absint( $_POST['tux_open_update'] ),
				'activation_limit' => absint( $_POST['tux_activation_limit'] ),
				'expiry'           => absint( $_POST['tux_expiry'] ),
				'children'         => $child_products,
			),
		) );

		$sendback = admin_url( 'admin.php?page=tuxedo-su-license-rules' );

		if ( false === $tux_insert ) {

			$sendback = add_query_arg( 'updated', 'error', $sendback );

		} else {

			$sendback = add_query_arg( 'updated', 'success', $sendback );

		}

		wp_redirect( $sendback );
		exit();

	} // End if().
}

/**
 * Add styles output function to admin_print_styles action.
 *
 * @since 1.0.0
 */
function tux_su_admin_styles_add_action_license_rules() {

	add_action( 'admin_print_styles', 'tux_su_admin_styles_license_rules' );

}

/**
 * Output css styles.
 *
 * @since 1.0.0
 */
function tux_su_admin_styles_license_rules() {

	?>
	<style>
		table.rules .row-actions {
			color: #999;
		}

		@media screen and (min-width: 783px) {
			table.rules .column-version,
			table.rules .column-open_update,
			table.rules .column-activation_limit,
			table.rules .column-expiry,
			table.rules .column-created,
			table.rules .column-modified {
				width: 150px;
			}
		}
	</style>
	<?php

}
