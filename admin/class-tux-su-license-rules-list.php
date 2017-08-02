<?php
/**
 * Tuxedo Software Update Licensing rules list table.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

/**
 * License rules list table.
 *
 * @since 1.0.0
 *
 * @see   WP_List_Table
 */
class Tux_SU_License_Rules_List extends WP_List_Table {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		parent::__construct( array(
			'singular' => 'rule',
			'plural'   => 'rules',
			'ajax'     => false,
		) );

	}

	/**
	 * No items output.
	 *
	 * @since 1.0.0
	 */
	public function no_items() {

		esc_html_e( 'No license rules found.', 'tuxedo-software-updater' );

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
			'cb'               => '<input type="checkbox" />',
			'product_id'       => __( 'Product ID', 'tuxedo-software-updater' ),
			'file_url'         => __( 'File URL', 'tuxedo-software-updater' ),
			'version'          => __( 'Version', 'tuxedo-software-updater' ),
			'open_update'      => __( 'Open Update', 'tuxedo-software-updater' ),
			'activation_limit' => __( 'Activation Limit', 'tuxedo-software-updater' ),
			'expiry'           => __( 'Expiry', 'tuxedo-software-updater' ),
			'created'          => __( 'Created', 'tuxedo-software-updater' ),
			'modified'         => __( 'Modified', 'tuxedo-software-updater' ),
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
				'product_id' => $_GET['s'],
				'info'       => $_GET['s'],
				'type'       => 'rule',
				'count'      => true,
				'search'     => true,
			) );

		} else {

			return tux_su_get_db( array(
				'type'  => 'rule',
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

		$delete_nonce = wp_create_nonce( 'tux_delete_license_rule' );

		$title = '<strong>' . $item['product_id'] . '</strong> - ' . $item['product_name'];

		$actions = array(
			'id'       => 'ID: ' . $item['id'],
			'edit'     => sprintf( '<a href="?page=%s&action=%s&edit_id=%s">' . __( 'Edit', 'tuxedo-software-updater' ) . '</a>', esc_attr( $_REQUEST['page'] ), 'edit', absint( $item['id'] ) ),
			'delete'   => sprintf( '<a href="?page=%s&action=%s&delete_id=%s&_wpnonce=%s" onclick="return confirm(\'%s\');">' . __( 'Delete', 'tuxedo-software-updater' ) . '</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce, esc_attr__( 'Delete this license rule?', 'tuxedo-software-updater' ) ),
			'licenses' => sprintf( '<a href="?page=tuxedo-su-licenses&product_id=%d">' . __( 'Licenses' ) . '</a>', $item['product_id'] ),
		);

		return $title . $this->row_actions( $actions );

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
			'total_items' => Tux_SU_License_Rules_List::record_count(),
			'per_page'    => 20,
		) );

		if ( isset( $_GET['orderby'] ) && ( in_array( $_GET['orderby'], array( 'created', 'modified', 'product_id', ) ) ) ) {

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
				'product_id' => $_GET['s'],
				'info'       => $_GET['s'],
				'type'       => 'rule',
				'order'      => $order,
				'limit'      => 20,
				'offset'     => ( $this->get_pagenum() - 1 ) * 20,
				'search'     => true,
			) );

		} else {

			if ( isset( $_GET['product_id'] ) ) {

				$items = tux_su_get_db( array(
					'product_id' => $_GET['product_id'],
					'type'       => 'rule',
					'order'      => $order,
					'limit'      => 20,
					'offset'     => ( $this->get_pagenum() - 1 ) * 20,
				) );


			} else {

				$items = tux_su_get_db( array(
					'type'   => 'rule',
					'order'  => $order,
					'limit'  => 20,
					'offset' => ( $this->get_pagenum() - 1 ) * 20,
				) );

			}
		}

		foreach ( $items as $item ) {

			$info = array();

			if ( isset( $item['info'] ) ) {

				$info = maybe_unserialize( $item['info'] );

			}

			$this->items[] = array(
				'id'               => isset( $item['id'] ) ? $item['id'] : '',
				'product_name'     => isset( $info['product_name'] ) ? $info['product_name'] : '',
				'product_id'       => isset( $item['product_id'] ) ? $item['product_id'] : '',
				'file_url'         => isset( $info['file_url'] ) ? $info['file_url'] : '',
				'version'          => isset( $info['version'] ) ? $info['version'] : '',
				'open_update'      => isset( $info['open_update'] ) ? ( 1 === $info['open_update'] ? '<span style="color:green;">' . __( 'open', 'tuxedo-software-updater' ) . '</span>' : __( 'closed', 'tuxedo-software-updater' ) ) : __( 'closed', 'tuxedo-software-updater' ),
				'activation_limit' => isset( $info['activation_limit'] ) ? ( empty( $info['activation_limit'] ) ? __( 'unlimited', 'tuxedo-software-updater' ) : $info['activation_limit'] ) : '',
				'expiry'           => isset( $info['expiry'] ) ? ( empty( $info['expiry'] ) ? __( 'never', 'tuxedo-software-updater' ) : $info['expiry'] . ' ' . __( 'days', 'tuxedo-software-updater' ) ) : '',
				'created'          => isset( $item['created'] ) ? date( 'M j, Y', strtotime( $item['created'] ) ) : '',
				'modified'         => isset( $item['modified'] ) ? date( 'M j, Y', strtotime( $item['modified'] ) ) . '<br>' . date( 'g:i a', strtotime( $item['modified'] ) ) : '',
			);

		}

	}

}
