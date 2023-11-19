<?php
/**
 * Orchid Store functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Orchid_Store
 */

$current_theme = wp_get_theme( 'orchid-store' );

define( 'ORCHID_STORE_VERSION', $current_theme->get( 'Version' ) );

if ( ! function_exists( 'orchid_store_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function orchid_store_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Orchid Store, use a find and replace
		 * to change 'orchid-store' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'orchid-store', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'orchid-store-thumbnail-extra-large', 800, 600, true );
		add_image_size( 'orchid-store-thumbnail-large', 800, 450, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary Menu', 'orchid-store' ),
				'menu-2' => esc_html__( 'Secondary Menu', 'orchid-store' ),
				'menu-3' => esc_html__( 'Top Header Menu', 'orchid-store' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'orchid_store_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 70,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		/**
		 * Remove block widget support in WordPress version 5.8 & later.
		 *
		 * @link https://make.wordpress.org/core/2021/06/29/block-based-widgets-editor-in-wordpress-5-8/
		 */
		remove_theme_support( 'widgets-block-editor' );
	}
endif;
add_action( 'after_setup_theme', 'orchid_store_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function orchid_store_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'orchid_store_content_width', 640 );
}
add_action( 'after_setup_theme', 'orchid_store_content_width', 0 );


/**
 * Enqueue scripts and styles.
 */
function orchid_store_scripts() {

	wp_enqueue_style(
		'orchid-store-style',
		get_stylesheet_uri(),
		array(),
		ORCHID_STORE_VERSION,
		'all'
	);

	wp_enqueue_style(
		'orchid-store-fonts',
		orchid_store_lite_fonts_url(),
		array(),
		ORCHID_STORE_VERSION,
		'all'
	);

	wp_enqueue_style(
		'orchid-store-boxicons',
		get_template_directory_uri() . '/assets/fonts/boxicons/boxicons.css',
		array(),
		ORCHID_STORE_VERSION,
		'all'
	);

	wp_enqueue_style(
		'orchid-store-fontawesome',
		get_template_directory_uri() . '/assets/fonts/fontawesome/fontawesome.css',
		array(),
		ORCHID_STORE_VERSION,
		'all'
	);

	if ( is_rtl() ) {

		wp_enqueue_style(
			'orchid-store-main-style-rtl',
			get_template_directory_uri() . '/assets/dist/css/main-style-rtl.css',
			array(),
			ORCHID_STORE_VERSION,
			'all'
		);

		wp_add_inline_style(
			'orchid-store-main-style-rtl',
			orchid_store_dynamic_style()
		);
	} else {

		wp_enqueue_style(
			'orchid-store-main-style',
			get_template_directory_uri() . '/assets/dist/css/main-style.css',
			array(),
			ORCHID_STORE_VERSION,
			'all'
		);

		wp_add_inline_style(
			'orchid-store-main-style',
			orchid_store_dynamic_style()
		);
	}

	wp_register_script(
		'orchid-store-bundle',
		get_template_directory_uri() . '/assets/dist/js/bundle.min.js',
		array( 'jquery' ),
		ORCHID_STORE_VERSION,
		true
	);

	$script_obj = array(
		'ajax_url'              => esc_url( admin_url( 'admin-ajax.php' ) ),
		'homeUrl'               => esc_url( home_url() ),
		'isUserLoggedIn'        => is_user_logged_in(),
		'isCartMessagesEnabled' => orchid_store_get_option( 'enable_cart_messages' ),
	);

	$script_obj['scroll_top'] = orchid_store_get_option( 'display_scroll_top_button' );

	if ( class_exists( 'WooCommerce' ) ) {

		if ( get_theme_mod( 'orchid_store_field_product_added_to_cart_message', esc_html__( 'Product successfully added to cart!', 'orchid-store' ) ) ) {

			$script_obj['added_to_cart_message'] = get_theme_mod( 'orchid_store_field_product_added_to_cart_message', esc_html__( 'Product successfully added to cart!', 'orchid-store' ) );
		}

		if ( get_theme_mod( 'orchid_store_field_product_removed_from_cart_message', esc_html__( 'Product has been removed from your cart!', 'orchid-store' ) ) ) {

			$script_obj['removed_from_cart_message'] = get_theme_mod( 'orchid_store_field_product_removed_from_cart_message', esc_html__( 'Product has been removed from your cart!', 'orchid-store' ) );
		}

		if ( get_theme_mod( 'orchid_store_field_cart_update_message', esc_html__( 'Cart items has been updated successfully!', 'orchid-store' ) ) ) {

			$script_obj['cart_updated_message'] = get_theme_mod( 'orchid_store_field_cart_update_message', esc_html__( 'Cart items has been updated successfully!', 'orchid-store' ) );
		}

		if ( get_theme_mod( 'orchid_store_field_product_cols_in_mobile', 1 ) ) {
			$script_obj['product_cols_on_mobile'] = get_theme_mod( 'orchid_store_field_product_cols_in_mobile', 1 );
		}

		if ( get_theme_mod( 'orchid_store_field_display_plus_minus_btns', true ) ) {
			$script_obj['displayPlusMinusBtns'] = get_theme_mod( 'orchid_store_field_display_plus_minus_btns', true );
		}

		if ( get_theme_mod( 'orchid_store_field_cart_display', 'default' ) ) {
			$script_obj['cartDisplay'] = ( class_exists( 'Addonify_Floating_Cart' ) ) ? apply_filters( 'orchid_store_cart_display_filter', get_theme_mod( 'orchid_store_field_cart_display', 'default' ) ) : 'default';
		}

		if ( class_exists( 'Addonify_Wishlist' ) ) {
			$script_obj['addToWishlistText']     = get_option( 'addonify_wishlist_btn_label', 'Add to wishlist' );
			$script_obj['addedToWishlistText']   = get_option( 'addonify_wishlist_btn_label_when_added_to_wishlist', 'Added to wishlist' );
			$script_obj['alreadyInWishlistText'] = get_option( 'addonify_wishlist_btn_label_if_added_to_wishlist', 'Already in wishlist' );
		}
	}

	wp_localize_script( 'orchid-store-bundle', 'orchid_store_obj', $script_obj );

	wp_enqueue_script( 'orchid-store-bundle' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'orchid_store_scripts' );


/**
 * Enqueue scripts and styles for admin.
 */
function orchid_store_admin_enqueue() {

	wp_enqueue_script( 'media-upload' );

	wp_enqueue_media();

	wp_enqueue_style(
		'orchid-store-admin-style',
		get_template_directory_uri() . '/admin/css/admin-style.css',
		array(),
		ORCHID_STORE_VERSION,
		'all'
	);

	wp_enqueue_script(
		'orchid-store-admin-script',
		get_template_directory_uri() . '/admin/js/admin-script.js',
		array( 'jquery' ),
		ORCHID_STORE_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'orchid_store_admin_enqueue' );
add_action( 'wp_ajax_wp_ajax_install_plugin', 'wp_ajax_install_plugin' );





/**
 * Activates plugin AFC plugin.
 *
 * @since 1.4.2
 */
function orchid_store_activate_plugin() {

	$return_data = array(
		'success' => false,
		'message' => '',
	);

	if ( isset( $_POST['_ajax_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) ), 'updates' ) ) {
		$return_data['message'] = esc_html__( 'Invalid security token.', 'orchid-store' );
		wp_send_json( $return_data );
	}

	$activation = activate_plugin( WP_PLUGIN_DIR . '/addonify-floating-cart/addonify-floating-cart.php' );

	if ( ! is_wp_error( $activation ) ) {

		$return_data['success'] = true;
		$return_data['message'] = esc_html__( 'The plugin is activated successfully.', 'orchid-store' );
	} else {

		$return_data['message'] = $activation->get_error_message();
	}

	wp_send_json( $return_data );
}
add_action( 'wp_ajax_orchid_store_activate_plugin', 'orchid_store_activate_plugin' );


if ( defined( 'ELEMENTOR_VERSION' ) ) {

	add_action( 'elementor/editor/before_enqueue_scripts', 'orchid_store_admin_enqueue' );
}


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {

	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {

	require get_template_directory() . '/inc/woocommerce.php';

	require get_template_directory() . '/inc/woocommerce-hooks.php';
}

/**
 * Load breadcrumb trails.
 */
require get_template_directory() . '/third-party/breadcrumbs.php';

/**
 * Load TGM plugin activation.
 */
require get_template_directory() . '/third-party/class-tgm-plugin-activation.php';

/**
 * Load plugin recommendations.
 */
require get_template_directory() . '/inc/plugin-recommendation.php';

/**
 * Load custom hooks necessary for theme.
 */
require get_template_directory() . '/inc/custom-hooks.php';

/**
 * Load custom hooks necessary for theme.
 */
require get_template_directory() . '/inc/udp/init.php';


/**
 * Load function that enhance theme functionality.
 */
require get_template_directory() . '/inc/theme-functions.php';


/**
 * Load option choices.
 */
require get_template_directory() . '/inc/option-choices.php';


/**
 * Load widgets and widget areas.
 */
require get_template_directory() . '/widget/widgets-init.php';


/**
 * Load custom fields.
 */
require get_template_directory() . '/inc/custom-fields.php';

/**
 * Load theme dependecies
 */
require get_template_directory() . '/vendor/autoload.php';



// Custom woocomerce plugins



// add_action( 'woocommerce_single_product_summary', 'add_custom_upload_button', 25 );

// function add_custom_upload_button() {
//     // Your upload button HTML here
//     echo '<button type="button" id="custom-upload-btn">Upload File</button>';
//     // You can also include a form and input field if necessary
//     echo '<form id="custom-upload-form" method="post" enctype="multipart/form-data">';
//     echo '<input type="file" name="my_custom_file_upload" />';
//     echo '<input type="submit" value="Upload" />';
//     echo '</form>';
// }

function my_theme_styles() {
    wp_enqueue_style('my-theme-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'my_theme_styles');

function add_custom_upload_button() {
    global $product;

    // Check if the product is in the 'KHUNG ĐỂ BÀN' category
    if ( has_term( 'khung-de-ban', 'product_cat', $product->get_id() ) ) {
        // Use do_shortcode to display the content of your custom shortcode
        echo do_shortcode('[tabletop_frame_template]');
    }
}
add_action('woocommerce_single_product_summary', 'add_custom_upload_button', 25);

// Hook to add the custom field to the product general options
add_action('woocommerce_product_options_general_product_data', 'wp2023_add_link_shopee_field');

// Function to display the custom field
function wp2023_add_link_shopee_field() {
    woocommerce_wp_text_input(
        array(
            'id' => 'link_shopee',
            'label' => __('Shopee Link', 'wp-photo'),
            'placeholder' => 'https://shopee...',
            'desc_tip' => 'true',
            'description' => __('Enter the Shopee link for this product.', 'wp-photo'),
        )
    );
}

function wp2023_redirect_add_to_cart() {
    global $product;
    $shopee_link = get_post_meta($product->get_id(), 'link_shopee', true);
    if (!empty($shopee_link)) {
        ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('form.cart, .woocommerce-loop-add-to-cart-link').on('click', function(e) {
        e.preventDefault(); // Prevent the default Add to Cart behavior

        // Prompt user to enter their full name
        var userFullName = prompt("Please enter your full name:");

        // Make an AJAX call to save the full name to the database
        if (userFullName != null && userFullName !== "") {
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'save_full_name',
                    full_name: userFullName,
                    product_id: <?php echo $product->get_id(); ?>,
                    security: '<?php echo wp_create_nonce('save-full-name-nonce'); ?>'
                },
                success: function(response) {
                    // If success, redirect to Shopee
                    window.location.href = '<?php echo esc_js($shopee_link); ?>';
                }
            });
        }
    });
});
</script>
<?php
    }
}
add_action('woocommerce_after_single_product', 'wp2023_redirect_add_to_cart');


function wp2023_save_full_name() {
    check_ajax_referer('save-full-name-nonce', 'security');

    $full_name = sanitize_text_field($_POST['full_name']);

    global $wpdb;
    $customer_table = $wpdb->prefix . 'wp2023_customers';

    // Assuming you want to create a new record every time a name is submitted
    // Insert a new customer record with the full name
    $wpdb->insert(
        $customer_table,
        array('FullName' => $full_name)
    );

    // Check if the insert was successful
    if ($wpdb->insert_id) {
        wp_send_json_success('Full name saved successfully.');
    } else {
        wp_send_json_error('An error occurred while saving the full name.');
    }

    wp_die(); // Terminate AJAX request
}

add_action('wp_ajax_save_full_name', 'wp2023_save_full_name');
add_action('wp_ajax_nopriv_save_full_name', 'wp2023_save_full_name');



// KHUNG ĐỂ BÀN

function wp2023_tabletop_frame_template() {
    ob_start();
    ?>
<div id="wp2023-tabletop-frame-editor">
    <!-- Image Upload Input -->
    <input type="file" id="wp2023-custom-image" accept="image/*" multiple>

    <!-- Dropdown for Selecting Standard Sizes -->
    <select id="wp2023-standard-sizes">
        <option value="10x15">10x15 cm</option>
        <option value="13x18">13x18 cm</option>
        <option value="15x20">15x20 cm</option>
    </select>


    <div id="wp2023-tabletop-frame-editor">
        <!-- Existing inputs and controls -->

        <!-- Container for Thumbnails -->
        <div id="wp2023-image-thumbnails-container"></div>

    </div>


    <!-- Modal Structure -->
    <div id="image-edit-modal" class="modal" style="display: none;">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <img id="image-to-edit">
            <!-- Controls for image editing -->
            <button id="modal-zoom-in">Zoom In</button>
            <button id="modal-zoom-out">Zoom Out</button>
            <button id="modal-rotate-left">Rotate Left</button>
            <button id="modal-rotate-right">Rotate Right</button>
            <button id="wp2023-apply-crop" style="display:none;">Apply Crop</button>
        </div>
    </div>



</div>

<!-- JavaScript for handling image upload and cropping -->
<script>
jQuery(document).ready(function($) {

    var $image = $('#wp2023-image-preview');

    let croppers = [];

    // Function to read the image file and display the thumbnail
    function readAndPreviewImage(file, index) {
        let reader = new FileReader();
        reader.onload = function(e) {
            // Create a new thumbnail image
            let thumbnail = $(
                '<img class="thumbnail" style="max-width: 100px; height: auto; cursor: pointer;">');
            thumbnail.attr('src', e.target.result);
            thumbnail.attr('data-index', index);
            $('#wp2023-image-thumbnails-container').append(thumbnail);
        };
        reader.readAsDataURL(file);
    }

    $('#wp2023-custom-image').on('change', function(event) {
        let files = event.target.files;
        $('#wp2023-image-thumbnails-container').empty();

        // Iterate through each file selected and create thumbnails
        Array.from(files).forEach((file, index) => {
            readAndPreviewImage(file, index);
        });
    });

    // Thumbnail click event
    $('#wp2023-image-thumbnails-container').on('click', '.thumbnail', function() {
        var index = $(this).data('index');
        var file = $('#wp2023-custom-image').prop('files')[index];
        var src = $(this).attr('src');
        var modal = $('#image-edit-modal');
        var imageToEdit = $('#image-to-edit');

        var reader = new FileReader();
        reader.onload = function(e) {
            var src = $(this).attr('src');
            imageToEdit.attr('src', src);
            imageToEdit.show();
            // Show the modal
            modal.css('display', 'block');

            // Destroy any existing Cropper instance
            if (cropper) {
                cropper.destroy(); // If cropper already exists, destroy it before re-initializing
            }

            var imageElement = document.getElementById(
                'image-to-edit'); // Direct DOM access for Cropper.js initialization
            cropper = new Cropper(imageElement, {
                aspectRatio: 1,
                zoomable: true,
                scrolable: true,
                rotatable: true,
                // Add any other options as per your requirements
            });
        };
        reader.readAsDataURL(file);
    });

    // Close button to hide the modal and destroy the cropper instance
    $('.close').on('click', function() {
        var modal = $('#image-edit-modal');
        modal.hide();
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });


    $('#wp2023-standard-sizes').on('change', function() {
        var selectedSize = $(this).val();
        var aspectRatio = calculateAspectRatio(selectedSize);
        if (cropper) {
            cropper.setAspectRatio(aspectRatio);
        }
    });

    function calculateAspectRatio(size) {
        // Logic to calculate aspect ratio based on standard sizes
        var dimensions = size.split('x');
        return dimensions[0] / dimensions[1];
    }

    // Apply zoom, rotate to the active (last clicked) image
    let activeCropper = null;
    $('#wp2023-image-preview-container').on('click', 'img', function() {
        let clickedImage = this;
        let cropperData = croppers.find(c => c.image === clickedImage);
        if (cropperData) {
            activeCropper = cropperData.cropper;
        }
    });

    $('#wp2023-zoom-in').on('click', function() {
        if (activeCropper) {
            activeCropper.zoom(0.1);
        }
    });



    $('#wp2023-zoom-out').on('click', function() {
        if (activeCropper) {
            activeCropper.zoom(-0.1);
        }
    });

    $('#wp2023-rotate-left').on('click', function() {
        if (activeCropper) {
            activeCropper.rotate(-45);
        }
    });

    $('#wp2023-rotate-right').on('click', function() {
        if (activeCropper) {
            activeCropper.rotate(45);
        }
    });

    $('#modal-apply-crop').on('click', function() {
        // Assuming 'currentCropper' holds the active instance of Cropper.js
        var croppedCanvas = currentCropper.getCroppedCanvas();

        // Convert the canvas to a data URL and display it
        var croppedImageSrc = croppedCanvas.toDataURL("image/png");
        $('#cropped-image-preview').attr('src', croppedImageSrc).show();

        // Alternatively, convert the canvas to a Blob and send it to the server
        croppedCanvas.toBlob(function(blob) {
            var formData = new FormData();
            formData.append('croppedImage', blob);

            $.ajax({
                url: 'path/to/server/script.php', // Server script to process the upload
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Upload success', response);
                    // Handle successful upload here
                },
                error: function(xhr, status, error) {
                    console.log('Upload error', error);
                    // Handle errors here
                }
            });
        }, 'image/png');
    });
});
</script>
<?php
    return ob_get_clean();
}
add_shortcode('tabletop_frame_template', 'wp2023_tabletop_frame_template');

function wp2023_enqueue_scripts() {
    if (is_page('khung_de_ban') || is_singular('product')) {
        wp_enqueue_style('cropper-css', 'https://cdn.example.com/cropper/1.0.0/cropper.min.css');
		wp_enqueue_script('cropper-js', 'https://cdn.example.com/cropper/1.0.0/cropper.min.js', array('jquery'), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'wp2023_enqueue_scripts');






function test() {
    global $product;

    // Ensure global $product is a valid object
    if ( ! is_a( $product, 'WC_Product' ) ) {
        return;
    }

    // Check if the product is in the 'khung-treo-tuong' category
    if ( has_term( 'khung-treo-tuong', 'product_cat', $product->get_id() ) ) {
        if ( is_singular( 'product' ) ) {
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        }

		?>
<div class="custom">TEXT</div>
<?php
    }
}
add_action('woocommerce_single_product_summary', 'test');


// function test() {
// global $product;

// // Check if the product is in the 'KHUNG ĐỂ BÀN' category
// if ( has_term( 'khung-treo-tuong', 'product_cat', $product->get_id() ) ) {
// 	if ( is_singular( 'product' ) ) {
//         remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
//     }
// }
// }
// add_action('woocommerce_single_product_summary', 'test');


// add_filter('woocommerce_product_single_add_to_cart_text', 'wp2023_custom_cart_button_text');

// function wp2023_custom_cart_button_text() {
// global $product;

// // Check if the product is in the 'KHUNG ĐỂ BÀN' category
// if ( has_term( 'khung-treo-tuong', 'product_cat', $product->get_id() ) ) {
// return __('', 'wp-photo');
// }
// }