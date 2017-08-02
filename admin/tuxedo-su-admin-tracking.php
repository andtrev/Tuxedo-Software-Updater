<?php
/**
 * Tuxedo Software Update Licensing tracking admin page.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

/**
 * Class Tux_SU_Tracking_List.
 */
require_once( 'class-tux-su-tracking-list.php' );

/**
 * Display tracking admin page.
 *
 * @since 1.0.0
 */
function tux_su_tracking_admin_page() {

	if ( ! current_user_can( 'administrator' ) ) {

		return;

	}

	$list_table = new Tux_SU_Tracking_List();
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline">
			<?php esc_html_e( 'Tracking', 'tuxedo-software-updater' ); ?>
		</h1>
		<hr class="wp-header-end">

		<?php
		if ( isset( $_GET['deleted'] ) && 'success' === $_GET['deleted'] ) {

			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Successfully deleted tracking data.', 'tuxedo-software-updater' ) . '</p></div>';

		}

		if ( isset( $_GET['deleted'] ) && 'error' === $_GET['deleted'] ) {

			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Error deleting tracking data.', 'tuxedo-software-updater' ) . '</p></div>';

		}

		$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'updated', 'deleted', '_wpnonce', '_wp_http_referer' ), $_SERVER['REQUEST_URI'] );
		?>
		<div id="poststuff">
			<?php add_thickbox(); ?>
			<form method="get">
				<input type="hidden" name="page" value="tuxedo-su-tracking">
				<?php
				$list_table->prepare_items();
				$list_table->search_box( __( 'Search', 'tuxedo-software-updater' ), 'tux-su' );
				$list_table->display();
				?>
			</form>
		</div>
	</div>
	<?php

}

/**
 * Add styles and scripts output function to admin_print_styles and admin_print_scripts action.
 *
 * @since 1.0.0
 */
function tux_su_admin_styles_add_action_tracking() {

	add_action( 'admin_print_styles', 'tux_su_admin_styles_tracking' );
	add_action( 'admin_print_scripts', 'tux_su_admin_scripts_tracking', 999 );

}

/**
 * Output css styles.
 *
 * @since 1.0.0
 */
function tux_su_admin_styles_tracking() {

	?>
	<style>
		table.trackings .row-actions {
			color: #999;
		}
		@media screen and (min-width: 783px) {
			table.trackings .column-request_response,
			table.trackings .column-created {
				width: 150px;
			}
		}
	</style>
	<?php

}

/**
 * Output scripts.
 *
 * @since 1.0.0
 */
function tux_su_admin_scripts_tracking() {

	?>
	<script>
		jQuery(document).ready(function($){
			$('.tux_arrow').click(function(){
				if ($(this).next('table').css('display') === 'table') {
					$(this).next('table').css('display', 'block');
					$(this).html('&#9207;');
				} else {
					$(this).next('table').css('display', 'table');
					$(this).html('&#9206;');
				}
			});
		});
	</script>
	<?php

}