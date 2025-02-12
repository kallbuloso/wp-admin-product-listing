<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_Product_Listing {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Inclui classes adicionais
		require_once WP_PRODUCT_LISTING_PATH . 'includes/class-wp-product-table.php';
		require_once WP_PRODUCT_LISTING_PATH . 'includes/class-wp-product-handler.php';
		require_once WP_PRODUCT_LISTING_PATH . 'includes/class-wp-product-modal.php';
		
		// Instancia o handler para registrar os hooks de add/edit
		new WP_Product_Handler();
	}

	public static function install() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wp_product_listing';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id INT AUTO_INCREMENT PRIMARY KEY,
			code VARCHAR(50) NOT NULL,
			macro_code VARCHAR(50),
			name TEXT NOT NULL,
			brand VARCHAR(100),
			category VARCHAR(100),
			description TEXT,
			specifications TEXT,
			photo_url TEXT,
			is_main TINYINT(1) DEFAULT 0,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	public function register_admin_menu() {
		add_menu_page(
			__( 'Lista de Produtos', 'wp-product-listing' ),
			__( 'Produtos', 'wp-product-listing' ),
			'manage_options',
			'wp-product-listing',
			array( $this, 'render_admin_page' ),
			'dashicons-cart',
			25
		);
	}

	public function render_admin_page() {
		if ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'add', 'edit' ) ) ) {
			require_once WP_PRODUCT_LISTING_PATH . 'templates/admin-product-form.php';
		} else {
			require_once WP_PRODUCT_LISTING_PATH . 'templates/admin-product-list.php';
		}
	}

	public function enqueue_admin_scripts() {
		wp_enqueue_style( 'wp-product-listing-admin', WP_PRODUCT_LISTING_URL . 'assets/admin.css' );
		wp_enqueue_script( 'wp-product-listing-admin', WP_PRODUCT_LISTING_URL . 'assets/admin.js', array( 'jquery' ), null, true );
	}
}
