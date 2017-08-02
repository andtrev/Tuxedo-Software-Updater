<?php
/**
 * Tuxedo Software Update Licensing database.
 *
 * Get, update and delete license rules, licenses and activations from database.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since 1.0.0
 */

/** Set custom database table names in $wpdb global */
$GLOBALS['wpdb']->tux_su_licensing = $GLOBALS['wpdb']->prefix . 'tux_su_licensing';
$GLOBALS['wpdb']->tux_su_tracking  = $GLOBALS['wpdb']->prefix . 'tux_su_tracking';

/**
 * Get rows from the database.
 *
 * @since 1.0.0
 *
 * @param array       $args       {
 *                                Arguments for database retrieval.
 *
 * @type array|int    $id         Database row id.
 * @type array|int    $user_id    User id.
 * @type array|int    $product_id Product id.
 * @type string       $type       Type of row to retrieve ('license' or 'rule').
 * @type array|string $info       Blind info search, info is stored as serialized data.
 * @type array|string $info_value Info search for exact serialized value.
 * @type string       $group      Group by clause.
 * @type string       $order      Order by clause.
 * @type int          $limit      Limit of rows to return.
 * @type int          $offset     Offset of rows to return.
 * @type bool         $count      Return number of found rows.
 * @type bool         $search     Request is a search.
 * }
 * @param string      $table      Optional. Table to retrieve from ('licensing' or 'tracking'). Default licensing.
 *
 * @return array|null|string Null on failure, string for count, array for returned rows.
 */
function tux_su_get_db( $args = array(), $table = 'licensing' ) {

	global $wpdb;

	if ( 'licensing' === $table ) {

		$table = $wpdb->tux_su_licensing;

	} elseif ( 'tracking' === $table ) {

		$table = $wpdb->tux_su_tracking;

	} else {

		return null;

	}

	$where    = array();
	$group    = '';
	$order    = '';
	$limit    = '';
	$offset   = '';
	$count    = false;
	$search   = false;
	$operator = ' AND ';

	foreach ( $args as $key => $arg ) {

		switch ( $key ) {

			case 'id':
			case 'user_id':
			case 'product_id':
			case 'type':

				if ( 'type' === $key ) {

					if ( 'rule' === $arg ) {

						$arg = 1;

					} else {

						$arg = 2;

					}

					if ( is_array( $arg ) ) {

						array_unshift( $where, $key . ' IN (' . implode( ',', array_map( 'absint', $arg ) ) . ')' );

					} else {

						array_unshift( $where, $key . ' = ' . absint( $arg ) );

					}

					break;

				}

				if ( is_array( $arg ) ) {

					$where[] = $key . ' IN (' . implode( ',', array_map( 'absint', $arg ) ) . ')';

				} else {

					$where[] = $key . ' = ' . absint( $arg );

				}

				break;

			case 'info':

				if ( is_array( $arg ) ) {

					foreach ( $arg as $info ) {

						$where[] = $key . ' LIKE \'%' . esc_sql( $info ) . '%\'';

					}
				} else {

					$where[] = $key . ' LIKE \'%' . esc_sql( $arg ) . '%\'';

				}

				break;

			case 'info_value':

				if ( is_array( $arg ) ) {

					foreach ( $arg as $info ) {

						$where[] = 'info LIKE \':"%' . esc_sql( $info ) . '%";\'';

					}
				} else {

					$where[] = 'info LIKE \':"%' . esc_sql( $arg ) . '%";\'';

				}

				break;

			case 'group':

				$group = ' GROUP BY ' . $arg;

				break;

			case 'order':

				$order = ' ORDER BY ' . $arg;

				break;

			case 'limit':

				$limit = ' LIMIT ' . absint( $arg );

				break;

			case 'offset':

				$offset = ' OFFSET ' . absint( $arg );

				break;

			case 'count':

				if ( true === $arg ) {

					$count = true;

				}

				break;

			case 'search':

				if ( true === $arg ) {

					$search   = true;
					$operator = ' OR ';

				}

				break;

		} // End switch().
	} // End foreach().

	$where = implode( $operator, $where );

	if ( $search ) {

		if ( strpos( $where, 'type = 1 OR' ) !== false || strpos( $where, 'type = 2 OR' ) !== false ) {

			$where = str_replace( array( 'type = 1 OR', 'type = 2 OR' ), array( '(type = 1) AND (', '(type = 2) AND (' ), $where ) . ')';

		}
	}

	if ( ! empty( $where ) ) {

		$where = ' WHERE ' . $where;

	}

	if ( $count ) {

		return $wpdb->get_var( "SELECT COUNT(*) FROM {$table}{$where}" );

	}

	return $wpdb->get_results( "SELECT * FROM {$table}{$where}{$group}{$order}{$limit}{$offset}", ARRAY_A );

}

/**
 * Update or add a row to the database.
 *
 * @since 1.0.0
 *
 * @param array  $args       {
 *                           Arguments for database updating.
 *
 * @type int     $id         Optional. Database row id, a new row will be added if empty.
 * @type int     $user_id    User id.
 * @type int     $product_id Product id.
 * @type string  $type       Type of row ('license' or 'rule').
 * @type array   $info       Optional. Info is stored as serialized data.
 * @type string  $created    Optional. Date-time string.
 * @type string  $modified   Date-time string.
 * }
 * @param string $table      Optional. Table to update or add to ('licensing' or 'tracking'). Default licensing.
 *
 * @return false|int False on failure, int of updated or added row on success.
 */
function tux_su_update_db( $args = array(), $table = 'licensing' ) {

	global $wpdb;

	$defaults = array(
		'id'         => 0,
		'user_id'    => 0,
		'product_id' => 0,
		'type'       => '',
		'info'       => '',
		'created'    => '',
		'modified'   => current_time( 'mysql' ),
	);

	$args    = wp_parse_args( $args, $defaults );
	$columns = array(
		'user_id'    => absint( $args['user_id'] ),
		'product_id' => absint( $args['product_id'] ),
		'type'       => 'rule' === $args['type'] ? 1 : 2,
		'info'       => maybe_serialize( $args['info'] ),
		'created'    => empty( $args['created'] ) ? $args['modified'] : $args['created'],
		'modified'   => $args['modified'],
	);

	if ( 'licensing' === $table ) {

		$table = $wpdb->tux_su_licensing;

		if ( empty( $args['created'] ) && ! empty( $args['id'] ) ) {

			unset( $columns['created'] );
			$format = array( '%d', '%d', '%d', '%s', '%s' );

		} else {

			$format = array( '%d', '%d', '%d', '%s', '%s', '%s' );

		}
	} elseif ( 'tracking' === $table ) {

		$table = $wpdb->tux_su_tracking;

		unset( $columns['product_id'], $columns['type'], $columns['modified'] );
		$format = array( '%d', '%s', '%s' );

	} else {

		return false;

	}

	if ( empty( $args['id'] ) ) {

		return $wpdb->insert( $table, $columns, $format );

	}

	return $wpdb->update( $table, $columns, array( 'id' => absint( $args['id'] ) ), $format, '%d' );

}

/**
 * Delete from the licensing database table.
 *
 * @since 1.0.0
 *
 * @param array  $id    Array of row ids.
 * @param string $table Optional. Table to delete from ('licensing' or 'tracking'). Default licensing.
 *
 * @return false|int False on failure, number of rows deleted on success.
 */
function tux_su_delete_db( $id = array(), $table = 'licensing' ) {

	global $wpdb;

	if ( 'licensing' === $table ) {

		$table = $wpdb->tux_su_licensing;

	} elseif ( 'tracking' === $table ) {

		$table = $wpdb->tux_su_tracking;

	} else {

		return false;

	}

	if ( empty( $id ) || ! is_array( $id ) ) {

		return false;

	}

	return $wpdb->query( "DELETE FROM {$table} WHERE id IN (" . implode( ',', array_map( 'absint', $id ) ) . ")" );

}

/**
 * Tracking cleanup.
 * Remove old tracking data from the tracking table.
 *
 * @aince 1.0.0
 */
function tux_su_tracking_cleanup() {

	$tux_su_settings = get_option( 'tux_su_settings' );

	if ( empty( $tux_su_settings['tracking_cleanup'] ) ) {

		return;

	}

	global $wpdb;

	$removal_cutoff = date( 'Y-m-d H:i:s', current_time( 'timestamp' ) - ( absint( $tux_su_settings['tracking_cleanup'] ) * DAY_IN_SECONDS ) );

	$wpdb->query( "DELETE FROM {$wpdb->tux_su_tracking} WHERE created < '{$removal_cutoff}'" );

}

add_action( 'tux_su_tracking_cleanup', 'tux_su_tracking_cleanup' );
