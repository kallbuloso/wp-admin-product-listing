<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_Product_Modal {

	public function __construct() {
		add_action( 'admin_footer', array( $this, 'render_modal' ) );
		add_action( 'wp_ajax_get_product_details', array( $this, 'get_product_details' ) );
	}

	// Renderiza o HTML do modal
	public function render_modal() {
		?>
		<div id="wp-product-modal" class="wp-product-modal">
			<div class="wp-product-modal-content">
				<span class="wp-product-modal-close" onclick="document.getElementById('wp-product-modal').style.display='none'">&times;</span>
				<h2><?php _e( 'Detalhes do Produto', 'wp-product-listing' ); ?></h2>
				<div id="wp-product-modal-body"></div>
			</div>
		</div>
		<?php
	}

	// Busca os detalhes do produto via AJAX
	public function get_product_details() {
		if ( ! isset( $_POST['product_id'] ) ) {
			wp_send_json_error( __( 'ID do produto não fornecido.', 'wp-product-listing' ) );
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'wp_product_listing';
		$product_id = intval( $_POST['product_id'] );
		$product    = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $product_id ), ARRAY_A );

		if ( ! $product ) {
			wp_send_json_error( __( 'Produto não encontrado.', 'wp-product-listing' ) );
		}

		ob_start();
		?>
		<?php if ( ! empty( $product['photo_url'] ) ) : ?>
			<p><strong><?php _e( 'Foto:', 'wp-product-listing' ); ?></strong></p>
			<img src="<?php echo esc_url( $product['photo_url'] ); ?>" alt="<?php echo esc_attr( $product['name'] ); ?>" style="max-width: 180px; height: auto;">
		<?php endif; ?>
		<p><strong><?php _e( 'Código:', 'wp-product-listing' ); ?></strong> <?php echo esc_html( $product['code'] ); ?></p>
		<p><strong><?php _e( 'Nome:', 'wp-product-listing' ); ?></strong> <?php echo esc_html( $product['name'] ); ?></p>
		<p><strong><?php _e( 'Marca:', 'wp-product-listing' ); ?></strong> <?php echo esc_html( $product['brand'] ); ?></p>
		<p><strong><?php _e( 'Categoria:', 'wp-product-listing' ); ?></strong> <?php echo esc_html( $product['category'] ); ?></p>
		<p><strong><?php _e( 'Descrição:', 'wp-product-listing' ); ?></strong> <?php echo esc_html( $product['description'] ); ?></p>
		<p><strong><?php _e( 'Especificações:', 'wp-product-listing' ); ?></strong> <?php echo esc_html( $product['specifications'] ); ?></p>
		<?php
		wp_send_json_success( ob_get_clean() );
	}
}

new WP_Product_Modal();
