<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class WP_Product_Table extends WP_List_Table {

	public function __construct() {
		parent::__construct( array(
			'singular' => 'product',
			'plural'   => 'products',
			'ajax'     => false,
		) );
	}

	public function prepare_items() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wp_product_listing';

		// Pagination parameters
		$per_page = 10;
		$current_page = $this->get_pagenum();
		$total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

		// Fetch the items for the current page
		$offset = ( $current_page - 1 ) * $per_page;
		$items = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name LIMIT %d OFFSET %d", $per_page, $offset ), ARRAY_A );

		$this->items = $items;
		$this->_column_headers = array(
			$this->get_columns(),
			array(),
			$this->get_sortable_columns(),
		);

		// Set pagination arguments
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page ),
		) );
	}

	public function get_columns() {
		return array(
			'code'         => __( 'Código', 'wp-product-listing' ),
			'name'         => __( 'Nome', 'wp-product-listing' ),
			'brand'        => __( 'Marca', 'wp-product-listing' ),
			'category'     => __( 'Categoria', 'wp-product-listing' ),
			'is_main'      => __( 'Principal', 'wp-product-listing' ),
			'actions'      => __( 'Ações', 'wp-product-listing' ),
		);
	}

	public function column_default( $item, $column_name ) {
        if ( $column_name === 'name' ) {
            return esc_html( $item['name'] );
        }
        return isset( $item[ $column_name ] ) ? esc_html( $item[ $column_name ] ) : '';
    }

	public function column_actions( $item ) {
		$edit_url = admin_url( 'admin.php?page=wp-product-listing&action=edit&id=' . intval( $item['id'] ) );
		$delete_url = wp_nonce_url( admin_url( 'admin-post.php?action=wp_product_delete&id=' . intval( $item['id'] ) ), 'wp_product_nonce' );
		$view_url = '#'; // Placeholder URL for the view action
		$view_link = sprintf(
			'<a href="%s" class="view-product" data-id="%d">%s</a>',
			esc_url( $view_url ),
			intval( $item['id'] ),
			__( 'Visualizar', 'wp-product-listing' )
		);
		return $view_link . ' | <a href="' . esc_url( $edit_url ) . '">' . __( 'Editar', 'wp-product-listing' ) . '</a> | ' .
			   '<a href="' . esc_url( $delete_url ) . '" onclick="return confirm(\'' . esc_js( __( 'Tem certeza que deseja excluir este produto?', 'wp-product-listing' ) ) . '\')">' . __( 'Excluir', 'wp-product-listing' ) . '</a>';
	}
    
}
