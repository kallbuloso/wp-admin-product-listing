<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_Product_Handler {

	private $table_name;

	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'wp_product_listing';

		add_action( 'admin_post_wp_product_add', array( $this, 'handle_add_product' ) );
		add_action( 'admin_post_wp_product_edit', array( $this, 'handle_edit_product' ) );
		add_action( 'admin_post_wp_product_delete', array( $this, 'handle_delete_product' ) );
		add_action( 'admin_post_wp_product_import_csv', array( $this, 'handle_import_csv' ) );
	}

	public function handle_add_product() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Permissão negada', 'wp-product-listing' ) );
		}

		check_admin_referer( 'wp_product_nonce' );

		global $wpdb;
		$data = array(
			'code'          => sanitize_text_field( $_POST['code'] ),
			'macro_code'    => sanitize_text_field( $_POST['macro_code'] ),
			'name'          => sanitize_text_field( $_POST['name'] ),
			'brand'         => sanitize_text_field( $_POST['brand'] ),
			'category'      => sanitize_text_field( $_POST['category'] ),
			'description'   => sanitize_textarea_field( $_POST['description'] ),
			'specifications'=> sanitize_textarea_field( $_POST['specifications'] ),
			'photo_url'     => esc_url( $_POST['photo_url'] ),
			'is_main'       => isset( $_POST['is_main'] ) ? 1 : 0,
		);

		error_log( 'Dados recebidos: ' . print_r( $data, true ) );

		$result = $wpdb->insert( $this->table_name, $data );

		if ( false === $result ) {
			error_log( 'Erro ao inserir produto: ' . $wpdb->last_error );
			$msg = 'error';
		} else {
			error_log( 'Produto inserido com sucesso: ' . print_r( $data, true ) );
			$msg = 'success';
		}

		wp_safe_redirect( admin_url( 'admin.php?page=wp-product-listing&message=' . $msg ) );
		exit;
	}

	public function handle_edit_product() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Permissão negada', 'wp-product-listing' ) );
		}

		check_admin_referer( 'wp_product_nonce' );

		global $wpdb;
		$data = array(
			'code'          => sanitize_text_field( $_POST['code'] ),
			'macro_code'    => sanitize_text_field( $_POST['macro_code'] ),
			'name'          => sanitize_text_field( $_POST['name'] ),
			'brand'         => sanitize_text_field( $_POST['brand'] ),
			'category'      => sanitize_text_field( $_POST['category'] ),
			'description'   => sanitize_textarea_field( $_POST['description'] ),
			'specifications'=> sanitize_textarea_field( $_POST['specifications'] ),
			'photo_url'     => esc_url( $_POST['photo_url'] ),
			'is_main'       => isset( $_POST['is_main'] ) ? 1 : 0,
		);

		$where = array( 'id' => intval( $_POST['id'] ) );

		error_log( 'Dados recebidos para atualização: ' . print_r( $data, true ) );

		$result = $wpdb->update( $this->table_name, $data, $where );

		if ( false === $result ) {
			error_log( 'Erro ao atualizar produto: ' . $wpdb->last_error );
			$msg = 'error';
		} else {
			error_log( 'Produto atualizado com sucesso: ' . print_r( $data, true ) );
			$msg = 'success';
		}

		wp_safe_redirect( admin_url( 'admin.php?page=wp-product-listing&message=' . $msg ) );
		exit;
	}	

	public function handle_delete_product() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Permissão negada', 'wp-product-listing' ) );
		}

		check_admin_referer( 'wp_product_nonce' );

		global $wpdb;
		$id = intval( $_GET['id'] );
		$result = $wpdb->delete( $this->table_name, array( 'id' => $id ), array( '%d' ) );

		if ( false === $result ) {
			error_log( 'Erro ao excluir produto: ' . $wpdb->last_error );
			$msg = 'error';
		} else {
			$msg = 'success';
		}

		wp_safe_redirect( admin_url( 'admin.php?page=wp-product-listing&message=' . $msg ) );
		exit;
	}
}
