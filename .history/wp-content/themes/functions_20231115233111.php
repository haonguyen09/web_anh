<?php

add_action( 'woocommerce_single_product_summary', 'add_custom_upload_button', 25 );

function add_custom_upload_button() {
    // Your upload button HTML here
    echo '<button type="button" id="custom-upload-btn">Upload File</button>';
    // You can also include a form and input field if necessary
    echo '<form id="custom-upload-form" method="post" enctype="multipart/form-data">';
    echo '<input type="file" name="my_custom_file_upload" />';
    echo '<input type="submit" value="Upload" />';
    echo '</form>';
}

?>