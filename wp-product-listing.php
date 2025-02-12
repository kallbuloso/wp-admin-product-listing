<?php
/*
Plugin Name: WP Product Listing
Plugin URI: https://github.com/kallbuloso/wp-admin-product-listing
Description: Plugin para gerenciar uma lista de produtos.
Version: 1.0.0
Author: Kallbuloso
Author URI: https://github.com/kallbuloso/wp-admin-product-listing
License: GPL2 v2 or later
Text Domain: wp-product-listing
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Segurança
}

// Corrige o caminho para utilizar a pasta correta "plugins\wp-product-listing"
define( 'WP_PRODUCT_LISTING_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_PRODUCT_LISTING_URL', plugin_dir_url( __FILE__ ) );

// Inclui os arquivos necessários
require_once WP_PRODUCT_LISTING_PATH . 'includes/class-wp-product-listing.php';

// Inicializa o plugin
function wp_product_listing_init() {
	new WP_Product_Listing();
}
add_action( 'plugins_loaded', 'wp_product_listing_init' );

// Adiciona o hook de ativação
register_activation_hook( __FILE__, array( 'WP_Product_Listing', 'install' ) );
