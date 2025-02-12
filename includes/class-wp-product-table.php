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
		$items = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A );

		$this->items = $items;
		$this->_column_headers = array(
			$this->get_columns(),
			array(),
			$this->get_sortable_columns(),
		);
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
            return sprintf(
                '%s <button class="button view-product" data-id="%d">%s</button>',
                esc_html( $item['name'] ),
                intval( $item['id'] ),
                __( 'Visualizar', 'wp-product-listing' )
            );
        }
        return isset( $item[ $column_name ] ) ? esc_html( $item[ $column_name ] ) : '';
    }

	public function column_actions( $item ) {
		$edit_url = admin_url( 'admin.php?page=wp-product-listing&action=edit&id=' . intval( $item['id'] ) );
		$delete_url = wp_nonce_url( admin_url( 'admin-post.php?action=wp_product_delete&id=' . intval( $item['id'] ) ), 'wp_product_nonce' );
		return '<a href="' . esc_url( $edit_url ) . '">' . __( 'Editar', 'wp-product-listing' ) . '</a> | ' .
			   '<a href="' . esc_url( $delete_url ) . '" onclick="return confirm(\'' . esc_js( __( 'Tem certeza que deseja excluir este produto?', 'wp-product-listing' ) ) . '\')">' . __( 'Excluir', 'wp-product-listing' ) . '</a>';
	}
    
}
