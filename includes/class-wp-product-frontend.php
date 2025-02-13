<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_Product_Frontend {

	public function __construct() {
		add_shortcode( 'wp_product_listing', array( $this, 'render_product_grid' ) );
		add_shortcode( 'wp_product_search', array( $this, 'render_product_search' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	// Adiciona os scripts e estilos necessários
	public function enqueue_scripts() {
		wp_enqueue_style( 'wp-product-frontend', plugin_dir_url( __FILE__ ) . '../assets/frontend.css' );
		wp_enqueue_script( 'wp-product-frontend', plugin_dir_url( __FILE__ ) . '../assets/frontend.js', array( 'jquery' ), null, true );
		wp_localize_script( 'wp-product-frontend', 'wpProductAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	// Gera a saída do shortcode
	public function render_product_grid( $atts ) {
		// ...existing code...
	}

	// Gera a saída do shortcode de busca
	public function render_product_search() {
		ob_start();
		?>
		<div class="wp-product-search">
			<input type="text" id="wp-product-search-input" placeholder="<?php _e( 'Digite o código do produto', 'wp-product-listing' ); ?>">
			<button id="wp-product-search-button"><?php _e( 'Buscar', 'wp-product-listing' ); ?></button>
		</div>
		<div id="wp-product-search-result"></div>
		<?php
		return ob_get_clean();
	}
}

new WP_Product_Frontend();
