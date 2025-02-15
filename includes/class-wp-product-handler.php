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
		// Novo hook para limpar DB
		add_action( 'admin_post_wp_product_clear_db', array( $this, 'handle_clear_db' ) );
		// Novo hook para edição em massa da URL da foto
		add_action( 'admin_post_wp_product_bulk_edit_photo_url', array( $this, 'handle_bulk_edit_photo_url' ) );
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

	public function handle_import_csv() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Permissão negada', 'wp-product-listing' ) );
		}
		// Verifica o nonce
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'wp_product_import_csv_nonce' ) ) {
			wp_die( __( 'Falha na verificação de segurança.', 'wp-product-listing' ) );
		}
		// Verifica o arquivo enviado
		if ( ! isset( $_FILES['csv_file'] ) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK ) {
			wp_die( __( 'Erro ao enviar arquivo CSV.', 'wp-product-listing' ) );
		}

		$file = $_FILES['csv_file']['tmp_name'];
		if ( ( $handle = fopen( $file, 'r' ) ) !== false ) {
			// Lê a primeira linha como cabeçalho e ignora
			$header = fgetcsv( $handle, 1000, "," );
			global $wpdb;
			while ( ( $data = fgetcsv( $handle, 1000, "," ) ) !== false ) {
				// Pula linhas vazias
				if ( empty( $data[0] ) ) {
					continue;
				}
				$product_data = array(
					'code'           => sanitize_text_field( $data[0] ),
					'macro_code'     => sanitize_text_field( $data[1] ),
					'name'           => sanitize_text_field( $data[2] ),
					'brand'          => sanitize_text_field( $data[3] ),
					'category'       => sanitize_text_field( $data[4] ),
					'description'    => sanitize_textarea_field( $data[5] ),
					'specifications' => sanitize_textarea_field( $data[6] ),
					'photo_url'      => esc_url_raw( $data[7] ),
					'is_main'        => intval( $data[8] ) ? 1 : 0,
				);
				$wpdb->insert( $this->table_name, $product_data );
			}
			fclose( $handle );
		}
		wp_safe_redirect( admin_url( 'admin.php?page=wp-product-listing&message=import_success' ) );
		exit;
	}

	public function handle_clear_db() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Permissão negada', 'wp-product-listing' ) );
		}
		check_admin_referer( 'wp_product_clear_db_nonce' );
		global $wpdb;
		$result = $wpdb->query( "TRUNCATE TABLE {$this->table_name}" );
		$msg = $result === false ? 'error' : 'clear_success';
		wp_safe_redirect( admin_url( 'admin.php?page=wp-product-listing&message=' . $msg ) );
		exit;
	}

	public function handle_bulk_edit_photo_url() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Permissão negada', 'wp-product-listing' ) );
		}
		check_admin_referer( 'wp_product_bulk_edit_photo_url_nonce' );

		global $wpdb;
		$new_photo_url = esc_url_raw( $_POST['bulk_photo_url'] );
		$selected_product_ids = isset($_POST['selected_product_ids']) ? explode(',', $_POST['selected_product_ids']) : array();
		$selected_product_ids = array_map( 'intval', $selected_product_ids );

		if ( empty( $selected_product_ids ) ) {
			wp_safe_redirect( admin_url( 'admin.php?page=wp-product-listing&message=error&reason=no_products_selected' ) );
			exit;
		}

		$ids_placeholder = implode( ',', array_fill( 0, count( $selected_product_ids ), '%d' ) );
		$query = $wpdb->prepare( 
			"UPDATE {$this->table_name} SET photo_url = %s WHERE id IN ($ids_placeholder)",
			array_merge( array( $new_photo_url ), $selected_product_ids )
		);
		$result = $wpdb->query( $query );

		$msg = $result === false ? 'error' : 'bulk_edit_success';
		wp_safe_redirect( admin_url( 'admin.php?page=wp-product-listing&message=' . $msg ) );
		exit;
	}
}
