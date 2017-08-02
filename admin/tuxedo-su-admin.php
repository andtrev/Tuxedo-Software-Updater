<?php
/**
 * Tuxedo Software Update Licensing admin.
 *
 * Adds custom pages to the WordPress admin.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

if ( ! class_exists( 'WP_List_Table' ) ) {

	/**
	 * WP list table class.
	 *
	 * For list tables on license rules, licenses and tracking admin pages.
	 */
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

}

/**
 * Update key options for user profiles.
 */
require_once( 'tuxedo-su-admin-update-key.php' );

/**
 * License rules admin page.
 */
require_once( 'tuxedo-su-admin-license-rules.php' );

/**
 * Licenses admin page.
 */
require_once( 'tuxedo-su-admin-licenses.php' );

/**
 * Tracking admin page.
 */
require_once( 'tuxedo-su-admin-tracking.php' );

/**
 * Settings admin page.
 */
require_once( 'tuxedo-su-admin-settings.php' );

/**
 * Tools admin page.
 */
require_once( 'tuxedo-su-admin-tools.php' );

/**
 * Add menu pages.
 *
 * @since 1.0.0
 */
function tux_su_add_menu_pages() {

	add_menu_page(
		__( 'Tuxedo Updater', 'tuxedo-software-updater' ),
		__( 'Tuxedo Updater', 'tuxedo-software-updater' ),
		'administrator',
		'tuxedo-updater',
		null,
		'dashicons-upload',
		56
	);

	$hook_suffix = add_submenu_page(
		'tuxedo-updater',
		__( 'License Rules', 'tuxedo-software-updater' ),
		__( 'License Rules', 'tuxedo-software-updater' ),
		'administrator',
		'tuxedo-su-license-rules',
		'tux_su_license_rules_admin_page'
	);

	if ( false !== $hook_suffix ) {

		add_action( "load-{$hook_suffix}", 'tux_su_admin_enqueue_media' );
		add_action( "load-{$hook_suffix}", 'tux_su_admin_styles_add_action_license_rules' );
		add_action( "load-{$hook_suffix}", 'tux_su_license_rules_admin_create' );
		add_action( "load-{$hook_suffix}", 'tux_su_admin_delete_action' );

	}

	$hook_suffix = add_submenu_page(
		'tuxedo-updater',
		__( 'Licenses', 'tuxedo-software-updater' ),
		__( 'Licenses', 'tuxedo-software-updater' ),
		'administrator',
		'tuxedo-su-licenses',
		'tux_su_licenses_admin_page'
	);

	if ( false !== $hook_suffix ) {

		add_action( "load-{$hook_suffix}", 'tux_su_admin_styles_add_action_licenses' );
		add_action( "load-{$hook_suffix}", 'tux_su_licenses_admin_create' );
		add_action( "load-{$hook_suffix}", 'tux_su_admin_delete_action' );

	}

	$hook_suffix = add_submenu_page(
		'tuxedo-updater',
		__( 'Tracking', 'tuxedo-software-updater' ),
		__( 'Tracking', 'tuxedo-software-updater' ),
		'administrator',
		'tuxedo-su-tracking',
		'tux_su_tracking_admin_page'
	);

	if ( false !== $hook_suffix ) {

		add_action( "load-{$hook_suffix}", 'tux_su_admin_styles_add_action_tracking' );
		add_action( "load-{$hook_suffix}", 'tux_su_admin_delete_action' );

	}

	add_submenu_page(
		'tuxedo-updater',
		__( 'Settings', 'tuxedo-software-updater' ),
		__( 'Settings', 'tuxedo-software-updater' ),
		'administrator',
		'tuxedo-su-settings',
		'tux_su_settings_admin_page'
	);

	$hook_suffix = add_submenu_page(
		'tuxedo-updater',
		__( 'Tools', 'tuxedo-software-updater' ),
		__( 'Tools', 'tuxedo-software-updater' ),
		'administrator',
		'tuxedo-su-tools',
		'tux_su_tools_admin_page'
	);

	if ( false !== $hook_suffix ) {

		add_action( "load-{$hook_suffix}", 'tux_su_admin_enqueue_tools' );

	}
}

add_action( 'admin_menu', 'tux_su_add_menu_pages' );

/**
 * Remove Tuxedo Updater sub menu item.
 *
 * @since 1.0.0
 */
function tux_su_remove_tuxedo_updater_menu_item() {

	global $submenu;

	if ( isset( $submenu['tuxedo-updater'] ) ) {

		unset( $submenu['tuxedo-updater'][0] );

	}

}

add_action( 'admin_head', 'tux_su_remove_tuxedo_updater_menu_item' );

/**
 * Handle delete action.
 *
 * @since 1.0.0
 */
function tux_su_admin_delete_action() {

	if ( ! current_user_can( 'administrator' ) ) {

		return;

	}

	if ( isset( $_GET['_wpnonce'] ) ) {

		if ( 'tuxedo-su-license-rules' === $_REQUEST['page'] ) {

			$delete_nonce      = 'tux_delete_license_rule';
			$bulk_delete_nonce = 'bulk-rules';
			$table             = 'licensing';

		}

		if ( 'tuxedo-su-licenses' === $_REQUEST['page'] ) {

			$delete_nonce      = 'tux_delete_license';
			$bulk_delete_nonce = 'bulk-licenses';
			$table             = 'licensing';

		}

		if ( 'tuxedo-su-tracking' === $_REQUEST['page'] ) {

			$delete_nonce      = 'tux_delete_tracking';
			$bulk_delete_nonce = 'bulk-trackings';
			$table             = 'tracking';

		}
	}

	if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] ) {

		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], $delete_nonce ) || empty( $_GET['delete_id'] ) ) {

			return;

		}

		$tux_delete = tux_su_delete_db( array( $_GET['delete_id'] ), $table );

		$sendback = remove_query_arg( array(
			'action',
			'action2',
			'_wpnonce',
			'_wp_http_referer',
			'bulk-item-selection',
			'delete_id',
			'updated',
		), wp_get_referer() );

		if ( false === $tux_delete ) {

			$sendback = add_query_arg( 'deleted', 'error', $sendback );

		} else {

			$sendback = add_query_arg( 'deleted', 'success', $sendback );

		}

		wp_redirect( $sendback );
		exit();

	}

	if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], $bulk_delete_nonce ) ) {

		if ( ( isset( $_GET['action'] ) && 'bulk-delete' === $_GET['action'] ) || ( isset( $_GET['action2'] ) && 'bulk-delete' === $_GET['action2'] ) ) {

			if ( empty( $_GET['bulk-item-selection'] ) ) {

				return;

			}

			$tux_delete = tux_su_delete_db( $_GET['bulk-item-selection'], $table );

			$sendback = remove_query_arg( array(
				'action',
				'action2',
				'_wpnonce',
				'_wp_http_referer',
				'bulk-item-selection',
				'delete_id',
				'updated',
			), wp_get_referer() );

			if ( false === $tux_delete ) {

				$sendback = add_query_arg( 'deleted', 'error', $sendback );

			} else {

				$sendback = add_query_arg( 'deleted', 'success', $sendback );

			}

			wp_redirect( $sendback );
			exit();

		}
	} // End if().
}

/**
 * Enqueue media scripts.
 *
 * For license rule file selection and uploading.
 *
 * @since 1.0.0
 */
function tux_su_admin_enqueue_media() {

	wp_enqueue_media();

}
