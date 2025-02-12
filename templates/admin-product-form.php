<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;
$product = null;
$action = 'wp_product_add';

if ( isset( $_GET['id'] ) ) {
	$product_id = intval( $_GET['id'] );
	$product = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wp_product_listing WHERE id = %d", $product_id ), ARRAY_A );
	$action = 'wp_product_edit';
}

?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo $product ? __( 'Editar Produto', 'wp-product-listing' ) : __( 'Adicionar Novo Produto', 'wp-product-listing' ); ?></h1>
	<hr class="wp-header-end">
	<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
		<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>">
		<?php if ( $product ) : ?>
			<input type="hidden" name="id" value="<?php echo esc_attr( $product['id'] ); ?>">
		<?php endif; ?>
		<?php wp_nonce_field( 'wp_product_nonce' ); ?>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="code"><?php _e( 'Código', 'wp-product-listing' ); ?></label></th>
				<td><input name="code" type="text" id="code" value="<?php echo esc_attr( $product['code'] ?? '' ); ?>" class="regular-text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="macro_code"><?php _e( 'Macro Código', 'wp-product-listing' ); ?></label></th>
				<td><input name="macro_code" type="text" id="macro_code" value="<?php echo esc_attr( $product['macro_code'] ?? '' ); ?>" class="regular-text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="name"><?php _e( 'Nome', 'wp-product-listing' ); ?></label></th>
				<td><input name="name" type="text" id="name" value="<?php echo esc_attr( $product['name'] ?? '' ); ?>" class="regular-text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="brand"><?php _e( 'Marca', 'wp-product-listing' ); ?></label></th>
				<td><input name="brand" type="text" id="brand" value="<?php echo esc_attr( $product['brand'] ?? '' ); ?>" class="regular-text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="category"><?php _e( 'Categoria', 'wp-product-listing' ); ?></label></th>
				<td><input name="category" type="text" id="category" value="<?php echo esc_attr( $product['category'] ?? '' ); ?>" class="regular-text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="description"><?php _e( 'Descrição', 'wp-product-listing' ); ?></label></th>
				<td><textarea name="description" id="description" class="large-text"><?php echo esc_textarea( $product['description'] ?? '' ); ?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="specifications"><?php _e( 'Especificações', 'wp-product-listing' ); ?></label></th>
				<td><textarea name="specifications" id="specifications" class="large-text"><?php echo esc_textarea( $product['specifications'] ?? '' ); ?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="photo_url"><?php _e( 'URL da Foto', 'wp-product-listing' ); ?></label></th>
				<td><input name="photo_url" type="text" id="photo_url" value="<?php echo esc_url( $product['photo_url'] ?? '' ); ?>" class="regular-text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="is_main"><?php _e( 'Principal', 'wp-product-listing' ); ?></label></th>
				<td><input name="is_main" type="checkbox" id="is_main" value="1" <?php checked( $product['is_main'] ?? 0, 1 ); ?>></td>
			</tr>
		</table>
		<?php submit_button( $product ? __( 'Atualizar Produto', 'wp-product-listing' ) : __( 'Adicionar Produto', 'wp-product-listing' ) ); ?>
	</form>
	<!-- Botão de voltar -->
	<p>
		<a href="<?php echo admin_url( 'admin.php?page=wp-product-listing' ); ?>" class="button"><?php _e( 'Voltar', 'wp-product-listing' ); ?></a>
	</p>
</div>
