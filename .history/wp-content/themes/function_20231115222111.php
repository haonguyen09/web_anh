function my_custom_template() {
// Your custom template content or include a separate PHP file
echo '<Button class="my-custom-section">Tải ảnh lên</Button>';
}

add_action( 'woocommerce_single_product_summary', 'my_custom_template', 11 );