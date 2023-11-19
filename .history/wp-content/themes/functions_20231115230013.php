<?php

// Add custom button after single product price
add_action( 'woocommerce_single_product_summary', 'add_my_custom_button', 13 );

function add_my_custom_button() {
    // Your button HTML here
    echo '<a href="#" class="my-custom-button">My Custom Button</a>';
}

?>