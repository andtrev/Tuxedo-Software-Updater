<?php
/**
 * Tuxedo Software Update Licensing tracking list table.
 *
 * @package TuxedoSoftwareUpdateLicensing
 * @since   1.0.0
 */

/**
 * Tracking list table.
 *
 * @since 1.0.0
 *
 * @see   WP_List_Table
 */
class Tux_SU_Tracking_List extends WP_List_Table {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		parent::__construct( array(
			'singular' => 'tracking',
			'plural'   => 'trackings',
			'ajax'     => false,
		) );

	}

	/**
	 * No items output.
	 *
	 * @since 1.0.0
	 */
	public function no_items() {

		esc_html_e( 'No tracking found.', 'tuxedo-software-updater' );

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
			'user_id'          => __( 'User ID', 'tuxedo-software-updater' ),
			'ip_id'            => __( 'IP / ID', 'tuxedo-software-updater' ),
			'user_agent'       => __( 'User Agent', 'tuxedo-software-updater' ),
			'request_response' => __( 'Request / Response', 'tuxedo-software-updater' ),
			'created'          => __( 'Created', 'tuxedo-software-updater' ),
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
				'info'       => $_GET['s'],
				'count'      => true,
				'search'     => true,
			), 'tracking' );

		} else {

			return tux_su_get_db( array(
				'count' => true,
			), 'tracking' );

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
	 * User id column output.
	 *
	 * @since 1.0.0
	 *
	 * @param array $item List table item.
	 *
	 * @return string Output.
	 */
	public function column_user_id( $item ) {

		$delete_nonce = wp_create_nonce( 'tux_delete_tracking' );

		$title = '<strong>' . $item['user_id'] . '</strong> - ' . $item['user_name'];

		$actions = array(
			'id'           => 'ID: ' . $item['id'],
			'delete'       => sprintf( '<a href="?page=%s&action=%s&delete_id=%s&_wpnonce=%s" onclick="return confirm(\'%s\');">' . __( 'Delete', 'tuxedo-software-updater' ) . '</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce, esc_attr__( 'Delete this tracking data?', 'tuxedo-software-updater' ) ),
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
			'user_id'    => array( 'user_id', false ),
			'created'    => array( 'created', false ),
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
			'user_id',
		);

		$this->set_pagination_args( array(
			'total_items' => Tux_SU_Tracking_List::record_count(),
			'per_page'    => 20,
		) );

		if ( isset( $_GET['orderby'] ) && ( in_array( $_GET['orderby'], array( 'created', 'user_id' ) ) ) ) {

			$order = $_GET['orderby'];

		} else {

			$order = 'created';

		}

		if ( isset( $_GET['order'] ) && ( 'asc' === $_GET['order'] || 'desc' === $_GET['order'] ) ) {

			$order .= ' ' . $_GET['order'];

		} else {

			$order .= ' desc';

		}

		if ( ! empty( $_GET['s'] ) ) {

			$items = tux_su_get_db( array(
				'user_id'    => $_GET['s'],
				'info'       => $_GET['s'],
				'order'      => $order,
				'limit'      => 20,
				'offset'     => ($this->get_pagenum() - 1) * 20,
				'search'     => true,
			), 'tracking' );

		} else {

			$items = tux_su_get_db( array(
				'order'  => $order,
				'limit'  => 20,
				'offset' => ($this->get_pagenum() - 1) * 20,
			), 'tracking' );

		}

		$user_ids = array();

		foreach ( $items as $item ) {

			$user_ids[] = $item['user_id'];

		}

		$user_query = new WP_User_Query( array( 'include' => $user_ids, 'fields' => array( 'ID', 'user_login' ) ) );

		$users = $user_query->get_results();

		foreach ( $items as $item ) {

			$info = array();

			if ( isset( $item['info'] ) ) {

				$info = maybe_unserialize( $item['info'] );

			}

			$user_name = '';

			foreach ( $users as $user ) {

				if ( absint( $user->ID ) === absint( $item['user_id'] ) ) {

					$user_name = $user->user_login;

				}
			}

			$this->items[] = array(
				'id'               => isset( $item['id'] ) ? $item['id'] : '',
				'user_id'          => isset( $item['user_id'] ) ? $item['user_id'] : '',
				'user_name'        => $user_name,
				'ip_id'            => ( isset( $info['ip'] ) ? $info['ip'] : '' ) . ( isset( $info['request']['activation_id'] ) ? '<br>' . $info['request']['activation_id'] : '' ),
				'user_agent'       => isset( $info['user_agent'] ) ? $info['user_agent'] : '',
				'request_response' => '<div id="request-response-' . $item['id'] . '" style="display:none;"><pre style="margin:0;">' . __( 'Request', 'tuxedo-software-updater' ) . "\n" . json_encode( array( 'update_key' => isset( $info['request']['update_key'] ) ? $info['request']['update_key'] : '', 'ids' => isset( $info['request']['ids'] ) ? $info['request']['ids'] : '', 'versions' => isset( $info['request']['versions'] ) ? $info['request']['versions'] : '' ), JSON_PRETTY_PRINT ) .
				                      "\n\n" . __( 'Response', 'tuxedo-software-updater' ) . "\n" . ( isset( $info['response'] ) ? json_encode( $info['response'], JSON_PRETTY_PRINT ) : '' ) . '</pre></div><a href="#TB_inline?width=600&height=550&inlineId=request-response-' . $item['id'] . '" class="thickbox" title="' . esc_attr__( 'Request / Response', 'tuxedo-software-updater' ) . '">' . __( 'View', 'tuxedo-software-updater' ) . '</a>',
				'created'      => isset( $item['created'] ) ? date( 'M j, Y', strtotime( $item['created'] ) ) . '<br>' . date( 'g:i a', strtotime( $item['created'] ) ) : '',
			);

		}

	}

}
