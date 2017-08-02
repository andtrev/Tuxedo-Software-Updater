<?php
/**
 * Plugin Name: Tuxedo Software Update Licensing
 * Plugin URI:  https://github.com/andtrev/Tuxedo-Software-Updater
 * Description: Software updater API standalone and for WooCommerce.
 * Version:     1.0.0
 * Author:      Trevor Anderson
 * Author URI:  https://github.com/andtrev
 * License:     GPLv2 or later
 * Text Domain: tuxedo-software-updater
 * Domain Path: /languages
 *
 * (C) 2017, Trevor Anderson
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @version 1.0.0
 */

/**
 * Database / Activation.
 */
require_once( 'tuxedo-su-database.php' );

/**
 * Update key.
 */
require_once( 'tuxedo-su-update-key.php' );

if ( is_admin() ) {

	/**
	 * Admin.
	 */
	require_once( 'admin/tuxedo-su-admin.php' );

}

/**
 * Rest API.
 */
require_once( 'tuxedo-su-rest-api.php' );

/**
 * Load e-commerce functionality.
 *
 * If WooCommerce or Easy Digital Downloads is active.
 *
 * @since 1.0.0
 */
function tux_su_if_ecommerce() {

	$tux_su_settings = get_option( 'tux_su_settings' );

	if ( ! empty( $tux_su_settings['ecommerce'] ) ) {

		if ( class_exists( 'WooCommerce' ) ) {

			require_once( 'tuxedo-su-woo.php' );

		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {

			require_once( 'tuxedo-su-edd.php' );

		}
	}
}

add_action( 'plugins_loaded', 'tux_su_if_ecommerce' );

/**
 * Load text domain.
 *
 * @since 1.0.0
 */
function tux_su_load_plugin_textdomain() {

	load_plugin_textdomain( 'tuxedo-software-updater', false, basename( dirname( __FILE__ ) ) . '/languages/' );

}

add_action( 'plugins_loaded', 'tux_su_load_plugin_textdomain' );

// Add the tracking cleanup schedule if it doesn't exist.
if ( ! wp_next_scheduled( 'tux_su_tracking_cleanup' ) ) {

	wp_schedule_event( time(), 'twicedaily', 'tux_su_tracking_cleanup' );

}

// Plugin activation.
register_activation_hook( __FILE__, 'tux_su_activation' );

// Plugin deactivation.
register_deactivation_hook( __FILE__, 'tux_su_deactivation' );

/**
 * Plugin activation.
 *
 * Setup initial settings and custom database tables.
 *
 * @since 1.0.0
 */
function tux_su_activation() {

	global $wpdb;

	$tux_su_settings = get_option( 'tux_su_settings' );

	if ( empty( $tux_su_settings ) ) {

		update_option( 'tux_su_settings', array(
			'ecommerce'        => 1,
			'tracking'         => 0,
			'tracking_cleanup' => 0,
		) );

	}

	add_rewrite_endpoint( 'update-licenses', EP_ROOT | EP_PAGES );
	flush_rewrite_rules();

	$wpdb->hide_errors();

	$collate = '';
	if ( $wpdb->has_cap( 'collation' ) ) {

		if ( ! empty( $wpdb->charset ) ) {

			$collate .= " DEFAULT CHARACTER SET $wpdb->charset";

		}

		if ( ! empty( $wpdb->collate ) ) {

			$collate .= " COLLATE $wpdb->collate";

		}
	}

	/** Included for dbDelta */
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$sql =
		"CREATE TABLE {$wpdb->tux_su_licensing} (
  id bigint(20) unsigned NOT NULL auto_increment,
  user_id bigint(20) unsigned NOT NULL default '0',
  product_id bigint(20) unsigned NOT NULL default '0',
  type int(10) unsigned NOT NULL default '0',
  info longtext,
  created datetime NOT NULL default '0000-00-00 00:00:00',
  modified datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (id),
  KEY user_id (user_id),
  KEY product_id (product_id)
){$collate};";

	dbDelta( $sql );

	$sql =
		"CREATE TABLE {$wpdb->tux_su_tracking} (
  id bigint(20) unsigned NOT NULL auto_increment,
  user_id bigint(20) unsigned NOT NULL default '0',
  info longtext,
  created datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (id),
  KEY user_id (user_id),
  KEY created (created)
){$collate};";

	dbDelta( $sql );

}

/**
 * Plugin deactivation.
 *
 * @since 1.0.0
 */
function tux_su_deactivation() {

	if ( wp_next_scheduled( 'tux_su_tracking_cleanup' ) ) {

		wp_unschedule_event( wp_next_scheduled( 'tux_su_tracking_cleanup' ), 'tux_su_tracking_cleanup' );

	}

	flush_rewrite_rules();

}
