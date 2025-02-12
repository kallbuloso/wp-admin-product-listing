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
	<div style="float: right; margin-top: 5px;">
		<a href="<?php echo admin_url( 'admin.php?page=wp-product-listing&action=add' ); ?>" class="page-title-action"><?php _e( 'Adicionar Novo', 'wp-product-listing' ); ?></a>
		<!-- Formulário de Importação CSV -->
		<form method="post" enctype="multipart/form-data" action="<?php echo admin_url( 'admin-post.php' ); ?>" style="display:inline-block; margin-left:10px;">
			<input type="hidden" name="action" value="wp_product_import_csv">
			<?php wp_nonce_field( 'wp_product_import_csv_nonce' ); ?>
			<input type="file" name="csv_file" accept=".csv">
			<input type="submit" class="button" value="<?php _e( 'Importar CSV', 'wp-product-listing' ); ?>">
		</form>
		<!-- Fim do formulário CSV -->
	</div>
	<div style="clear: both;"></div>
	<hr class="wp-header-end">
	<form method="post">
		<?php $product_table->display(); ?>
	</form>
</div>
