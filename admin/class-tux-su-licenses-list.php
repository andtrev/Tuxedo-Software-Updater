<?php
/**
 * Tuxedo Software Update Licensing licenses list table.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

/**
 * Licenses list table.
 *
 * @since 1.0.0
 *
 * @see   WP_List_Table
 */
class Tux_SU_Licenses_List extends WP_List_Table {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		parent::__construct( array(
			'singular' => 'license',
			'plural'   => 'licenses',
			'ajax'     => false,
		) );

	}

	/**
	 * No items output.
	 *
	 * @since 1.0.0
	 */
	public function no_items() {

		esc_html_e( 'No licenses found.', 'tuxedo-software-updater' );

	}

	/**
	 * Get columns for list table.
	 *
	 * @since 1.0.0
	 *
	 * @return array Columns.
	 */
	public function get_columns() {

		return array(
			'cb'          => '<input type="checkbox" />',
			'product_id'  => __( 'Product ID', 'tuxedo-software-updater' ),
			'user_id'     => __( 'User ID', 'tuxedo-software-updater' ),
			'order_id'    => __( 'Order ID', 'tuxedo-software-updater' ),
			'activations' => __( 'Activations', 'tuxedo-software-updater' ),
			'expires'     => __( 'Expires', 'tuxedo-software-updater' ),
			'created'     => __( 'Created', 'tuxedo-software-updater' ),
			'modified'    => __( 'Modified', 'tuxedo-software-updater' ),
		);

	}

	/**
	 * Get record count from database.
	 *
	 * @since 1.0.0
	 *
	 * @return null|string Count.
	 */
	public static function record_count() {

		if ( ! empty( $_GET['s'] ) ) {

			return tux_su_get_db( array(
				'user_id'    => $_GET['s'],
				'product_id' => $_GET['s'],
				'info'       => $_GET['s'],
				'type'       => 'license',
				'count'      => true,
				'search'     => true,
			) );

		} else {

			return tux_su_get_db( array(
				'type'  => 'license',
				'count' => true,
			) );

		}
	}

	/**
	 * Default column output.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $item        List table item.
	 * @param string $column_name Column name.
	 *
	 * @return string Output.
	 */
	public function column_default( $item, $column_name ) {

		return $item[ $column_name ];

	}

	/**
	 * Check box column output.
	 *
	 * @since 1.0.0
	 *
	 * @param array $item List table item.
	 *
	 * @return string Output.
	 */
	public function column_cb( $item ) {

		return sprintf( '<input type="checkbox" class="bulk-item-selection" name="bulk-item-selection[]" value="%s" />', $item['id'] );

	}

	/**
	 * Product id column output.
	 *
	 * @since 1.0.0
	 *
	 * @param array $item List table item.
	 *
	 * @return string Output.
	 */
	public function column_product_id( $item ) {

		$delete_nonce = wp_create_nonce( 'tux_delete_license' );

		$title = '<strong>' . $item['product_id'] . '</strong> - ' . $item['product_name'];

		$actions = array(
			'id'           => 'ID: ' . $item['id'],
			'edit'         => sprintf( '<a href="?page=%s&action=%s&edit_id=%s">' . __( 'Edit', 'tuxedo-software-updater' ) . '</a>', esc_attr( $_REQUEST['page'] ), 'edit', absint( $item['id'] ) ),
			'delete'       => sprintf( '<a href="?page=%s&action=%s&delete_id=%s&_wpnonce=%s" onclick="return confirm(\'%s\');">' . __( 'Delete', 'tuxedo-software-updater' ) . '</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce, esc_attr__( 'Delete this license?', 'tuxedo-software-updater' ) ),
			'license_rule' => sprintf( '<a href="?page=tuxedo-su-license-rules&product_id=%d">' . __( 'License Rules' ) . '</a>', $item['product_id'] ),
		);

		return $title . $this->row_actions( $actions );

	}

	/**
	 * User id column output.
	 *
	 * @since 1.0.0
	 *
	 * @param array $item List table item.
	 *
	 * @return string Output.
	 */
	public function column_user_id( $item ) {

		return '<strong>' . $item['user_id'] . '</strong> - ' . $item['user_name'];

	}

	/**
	 * Get sortable columns.
	 *
	 * @since 1.0.0
	 *
	 * @return array Columns.
	 */
	public function get_sortable_columns() {

		return array(
			'product_id' => array( 'product_id', false ),
			'user_id'    => array( 'user_id', false ),
			'created'    => array( 'created', false ),
			'modified'   => array( 'modified', true ),
		);

	}

	/**
	 * Get bulk actions.
	 *
	 * @since 1.0.0
	 *
	 * @return array Bulk actions.
	 */
	public function get_bulk_actions() {

		return array(
			'bulk-delete' => __( 'Delete', 'tuxedo-software-updater' ),
		);

	}

	/**
	 * Prepare items for display.
	 *
	 * @since 1.0.0
	 */
	public function prepare_items() {

		$this->_column_headers = array(
			$this->get_columns(),
			array(),
			$this->get_sortable_columns(),
			'product_id',
		);

		$this->set_pagination_args( array(
			'total_items' => Tux_SU_Licenses_List::record_count(),
			'per_page'    => 20,
		) );

		if ( isset( $_GET['orderby'] ) && ( in_array( $_GET['orderby'], array( 'created', 'modified', 'user_id', 'product_id', ) ) ) ) {

			$order = $_GET['orderby'];

		} else {

			$order = 'modified';

		}

		if ( isset( $_GET['order'] ) && ( 'asc' === $_GET['order'] || 'desc' === $_GET['order'] ) ) {

			$order .= ' ' . $_GET['order'];

		} else {

			$order .= ' desc';

		}

		if ( ! empty( $_GET['s'] ) ) {

			$items = tux_su_get_db( array(
				'user_id'    => $_GET['s'],
				'product_id' => $_GET['s'],
				'info'       => $_GET['s'],
				'type'       => 'license',
				'order'      => $order,
				'limit'      => 20,
				'offset'     => ( $this->get_pagenum() - 1 ) * 20,
				'search'     => true,
			) );

		} else {

			if ( isset( $_GET['product_id'] ) ) {

				$items = tux_su_get_db( array(
					'product_id' => $_GET['product_id'],
					'type'       => 'license',
					'order'      => $order,
					'limit'      => 20,
					'offset'     => ( $this->get_pagenum() - 1 ) * 20,
				) );


			} else {

				$items = tux_su_get_db( array(
					'type'   => 'license',
					'order'  => $order,
					'limit'  => 20,
					'offset' => ( $this->get_pagenum() - 1 ) * 20,
				) );

			}
		} // End if().

		$product_ids = array();

		foreach ( $items as $item ) {

			if ( ! empty( $item['product_id'] ) ) {

				$product_ids[] = $item['product_id'];

			}
		}

		$license_rules = array();

		if ( ! empty( $product_ids ) ) {

			$license_rules = tux_su_get_db( array(
				'product_id' => $product_ids,
				'type'       => 'rule',
			) );

		}

		foreach ( $items as $item ) {

			$info = array();

			if ( isset( $item['info'] ) ) {

				$info = maybe_unserialize( $item['info'] );

			}

			$product_name     = '';
			$activation_limit = 0;
			$expiry           = 0;

			foreach ( $license_rules as $license_rule ) {

				if ( $license_rule['product_id'] === $item['product_id'] ) {

					$license_rule['info'] = maybe_unserialize( $license_rule['info'] );

					if ( isset( $license_rule['info']['product_name'] ) ) {

						$product_name = $license_rule['info']['product_name'];

					}

					if ( ! empty( $info['child_id'] ) ) {

						if ( ! empty( $license_rule['info']['children'][ $info['child_id'] ]['name'] ) ) {

							$product_name .= ' ' . $license_rule['info']['children'][ $info['child_id'] ]['name'];

						}

						if ( isset( $license_rule['info']['children'][ $info['child_id'] ]['activation_limit'] ) ) {

							$activation_limit = $license_rule['info']['children'][ $info['child_id'] ]['activation_limit'];

						}

						if ( isset( $license_rule['info']['children'][ $info['child_id'] ]['expiry'] ) ) {

							$expiry = $license_rule['info']['children'][ $info['child_id'] ]['expiry'];

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

			$this->items[] = array(
				'id'           => isset( $item['id'] ) ? $item['id'] : '',
				'user_id'      => isset( $item['user_id'] ) ? $item['user_id'] : '',
				'user_name'    => isset( $info['user_name'] ) ? $info['user_name'] : '',
				'product_name' => $product_name,
				'product_id'   => isset( $item['product_id'] ) ? $item['product_id'] : '',
				'order_id'     => isset( $info['order_id'] ) ? $info['order_id'] : '',
				'activations'  => empty( $activation_limit ) ? __( 'unlimited', 'tuxedo-software-updater' ) : count( isset( $info['activations'] ) ? $info['activations'] : array() ) . ' / ' . $activation_limit,
				'expires'      => isset( $item['created'] ) ? ( empty( $expiry ) ? __( 'never', 'tuxedo-software-updater' ) : date( 'M j, Y', strtotime( $item['created'] . ' + ' . $expiry . ' days' ) ) ) : __( 'error', 'tuxedo-software-updater' ),
				'created'      => isset( $item['created'] ) ? date( 'M j, Y', strtotime( $item['created'] ) ) : '',
				'modified'     => isset( $item['modified'] ) ? date( 'M j, Y', strtotime( $item['modified'] ) ) . '<br>' . date( 'g:i a', strtotime( $item['modified'] ) ) : '',
			);

		}

	}

}
