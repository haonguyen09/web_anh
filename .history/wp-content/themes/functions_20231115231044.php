<?php

// Add custom button after single product price
add_action( 'woocommerce_before_add_to_cart_quantity', 'add_my_custom_button', 13 );

function add_my_custom_button() {
    // Your button HTML here
    echo '<div>This is upload image</div>';
}

?>