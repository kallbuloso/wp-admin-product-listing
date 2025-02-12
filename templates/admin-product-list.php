<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( isset( $_GET['message'] ) ) {
	if ( 'success' === $_GET['message'] ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Produto salvo com sucesso.', 'wp-product-listing' ) . '</p></div>';
	} elseif ( 'error' === $_GET['message'] ) {
		echo '<div class="notice notice-error is-dismissible"><p>' . __( 'Erro ao salvar o produto.', 'wp-product-listing' ) . '</p></div>';
	}
}

require_once WP_PRODUCT_LISTING_PATH . 'includes/class-wp-product-table.php';

$product_table = new WP_Product_Table();
$product_table->prepare_items();

?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e( 'Lista de Produtos', 'wp-product-listing' ); ?></h1>
	<a href="<?php echo admin_url( 'admin.php?page=wp-product-listing&action=add' ); ?>" class="page-title-action"><?php _e( 'Adicionar Novo', 'wp-product-listing' ); ?></a>
	<hr class="wp-header-end">
	<form method="post">
		<?php $product_table->display(); ?>
	</form>
</div>
