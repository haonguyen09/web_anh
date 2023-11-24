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


function my_theme_styles() {
    wp_enqueue_style('my-theme-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'my_theme_styles');




function wp2023_add_settings_page() {
    add_submenu_page(
        'options-general.php', // Parent slug
        'WP2023 Settings', // Page title
        'WP2023 Settings', // Menu title
        'manage_options', // Capability
        'wp2023-settings', // Menu slug
        'wp2023_settings_page_html' // Callback function
    );
}
add_action('admin_menu', 'wp2023_add_settings_page');

function wp2023_settings_page_html() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
<div class="wrap">
    <h1><?= esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
        <?php
            settings_fields('wp2023_options');
            do_settings_sections('wp2023-settings');
            submit_button('Save Settings');
            ?>
    </form>
</div>
<?php
}

function wp2023_settings_init() {
    register_setting('wp2023_options', 'wp2023_max_images');

    add_settings_section(
        'wp2023_settings_section',
        'WP2023 Custom Settings',
        'wp2023_settings_section_cb',
        'wp2023-settings'
    );

    add_settings_field(
        'wp2023_max_images', // Field ID
        'Max Images Upload', // Title
        'wp2023_max_images_field_cb', // Callback
        'wp2023-settings', // Page
        'wp2023_settings_section' // Section
    );
}
add_action('admin_init', 'wp2023_settings_init');

function wp2023_settings_section_cb() {
    echo '<p>Custom settings for WP2023.</p>';
}

function wp2023_max_images_field_cb() {
    $setting = get_option('wp2023_max_images');
    ?>
<input type="number" name="wp2023_max_images" value="<?= isset($setting) ? esc_attr($setting) : ''; ?>">
<?php
}


//Customer






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


include_once WP2023_PATH . 'includes.php';
WP2023_DB::seed_templates();
// KHUNG ĐỂ BÀN

function wp2023_tabletop_frame_template() {
    global $wpdb;
    ob_start();

// Get the current category ID (This is just a placeholder. You will need to replace it with actual code to get the category ID)
$current_category_id = get_the_current_category_id();

// Check if the category ID is valid
if ($current_category_id <= 0) {
    return 'Invalid category ID or no category found.'; // Handle the case where no valid category is found
}

// Query the database to get the template for the current category
$template = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}wp2023_templates WHERE CategoryId = %d",
    $current_category_id
));

// Check if a template was found
if (null === $template) {
    return 'No template found for this category.'; // Handle the case where no template is found
}

global $product;

// // Get the Shopee link from the product's custom field
// $shopee_link = get_post_meta($product->get_id(), 'link_shopee', true);
    
    ?>
<div id="wp2023-tabletop-frame-editor">
    <!-- Image Upload Input -->
    <!-- <input type="file" id="wp2023-custom-image" accept="image/*" multiple> -->

    <form id="image-upload-form" method="post" enctype="multipart/form-data">
        <input type="file" name="wp2023-custom-image" id="wp2023-custom-image" accept="image/*" multiple>
        <input type="submit" value="Upload Images">
    </form>

    <!-- Dropdown for Selecting Standard Sizes -->
    <select id="wp2023-standard-sizes">
        <option value="10x15">10x15 cm</option>
        <option value="13x18">13x18 cm</option>
        <option value="15x20">15x20 cm</option>
    </select>

    <div class="fullname-field-wrapper" style="margin: 20px 0;">
        <input type="text" id="userFullName" placeholder="Enter your full name">
        <p id="fullnameError" class="error-message" style="display:none; color:red;"></p>
    </div>

    <!-- Container for Thumbnails -->
    <div id="wp2023-image-thumbnails-container"></div>

    <!-- Error Message Container -->
    <div id="error-message-container" style="color: red; display: none;"></div>

    <!-- Loading Indicator -->
    <div id="loading-indicator" style="display: none;">
        <!-- Your loading spinner or progress bar goes here -->
        Loading...
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
            <button id="wp2023-apply-crop">Apply Crop</button>
        </div>
    </div>

    <button id="buy"
        data-shopee-link="<?php echo esc_attr( get_post_meta( $product->get_id(), 'link_shopee', true ) ); ?>">MUA
        HÀNG</button>

</div>

<!-- JavaScript for handling image upload and cropping -->
<script>
jQuery(document).ready(function($) {
            var lastEditType = '';
            var lastParameter = '';
            var $image = $('#wp2023-image-preview');

            let currentCropper = null;

            $('#image-upload-form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                formData.append('action', 'handle_original_image_upload');
                formData.append('security', your_nonce); // Replace with actual nonce
                formData.append('customerId', your_customer_id); // Replace with actual
                formData.append('fullname', $('#userFullName').val());

                // Assuming 'wp2023-custom-image' is the ID of your file input field
                var files = $('#wp2023-custom-image')[0].files;

                // Append each file to formData
                for (var i = 0; i < files.length; i++) {
                    formData.append('images[]', files[i]);
                }

                $.ajax({
                    url: ajaxurl, // Replace with actual AJAX URL
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle success
                        console.log('Upload Success:', response);
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error('Upload Error:', status, error);
                    }
                });
            });


            function readAndPreviewImage(file, index) {
                // Validate file size and type (example: limit to 5MB and only jpg/png files)
                const maxFileSize = 5 * 1024 * 1024; // 5MB
                const validFileTypes = ['image/jpeg', 'image/png'];

                if (file.size > maxFileSize) {
                    displayError('File too large. Maximum size is 5MB.');
                    return;
                }

                if (!validFileTypes.includes(file.type)) {
                    displayError('Invalid file type. Only JPG and PNG are allowed.');
                    return;
                }

                let reader = new FileReader();
                reader.onload = function(e) {

                    let thumbnailContainer = $(
                        '<img class="thumbnail" style="max-width: 100px; aspect-ratio: 1; margin-right: 5px; cursor: pointer; display: inline-block;">'
                    );
                    thumbnailContainer.attr('src', e.target.result);
                    thumbnailContainer.attr('data-index', index);


                    $('#wp2023-image-thumbnails-container').append(thumbnailContainer);
                    hideLoadingIndicator();
                };
                showLoadingIndicator();
                reader.readAsDataURL(file);
            }

            var maxUploads = parseInt(wp2023_settings.maxUploads, 10);
            $('#wp2023-custom-image').on('change', function(event) {
                var files = event.target.files;

                // Limit the number of uploads (example: limit to 10 images)
                if (files.length > maxUploads) {
                    displayError(`You can only upload up to ${maxUploads} images.`);
                    return;
                }

                $('#wp2023-image-thumbnails-container').empty();
                $('#error-message-container').hide();

                // Iterate through each file selected and create thumbnails
                Array.from(files).forEach((file, index) => {
                    readAndPreviewImage(file, index);
                });
            });

            function displayError(errorMessage) {
                // Display error message near the upload button or within the thumbnail container
                $('#error-message-container').text(errorMessage).show();
            }

            function showLoadingIndicator() {
                // Show loading indicator
                $('#loading-indicator').show();
            }

            function hideLoadingIndicator() {
                // Hide loading indicator
                $('#loading-indicator').hide();
            }
</script>
<?php
    return ob_get_clean();
}
function get_the_current_category_id() {
    global $product;

    // If on a single product page and a global product object is available
    if (is_singular('product') && is_a($product, 'WC_Product')) {
        // Fetch the product categories
        $categories = get_the_terms($product->get_id(), 'product_cat');

        // Assuming you want the ID of the first category assigned to the product
        if (!empty($categories) && !is_wp_error($categories)) {
            return $categories[0]->term_id;
        }
    } elseif (is_product_category()) {
        // If on a product category archive page
        $queried_object = get_queried_object();
        if (isset($queried_object->term_id)) {
            return $queried_object->term_id;
        }
    }

    // Return zero if no category ID was found or if not on a product page or category archive
    return 0;
}

add_shortcode('tabletop_frame_template', 'wp2023_tabletop_frame_template');

// function handle_save_fullname() {
//     // Verify the nonce
//     if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'wp2023_image_upload')) {
//         wp_send_json_error(['message' => 'Nonce verification failed']);
//         wp_die();
//     }

//     $full_name = isset($_POST['fullname']) ? sanitize_text_field($_POST['fullname']) : '';

//     // Perform your validation
//     if (empty($full_name)) {
//         wp_send_json_error(['message' => 'Full name cannot be empty.']);
//         wp_die();
//     }

//     global $wpdb;
//     $table_name = $wpdb->prefix . 'wp2023_customers';

//     // Insert the full name into the database
//     $result = $wpdb->insert(
//         $table_name,
//         array('full_name' => $full_name), // Column where you want to save the name
//         array('%s') // Data format, %s means string
//     );

//     if ($result) {
//         wp_send_json_success(['message' => 'Full name saved successfully.']);
//     } else {
//         wp_send_json_error(['message' => 'Failed to save the full name.']);
//     }
//     wp_die();
// }
// add_action('wp_ajax_save_fullname', 'handle_save_fullname');
// add_action('wp_ajax_nopriv_save_fullname', 'handle_save_fullname');


function handle_original_image_upload() {
    global $wpdb;

    // Check for nonce and other validations as before

    $fullName = isset($_POST['fullname']) ? sanitize_text_field($_POST['fullname']) : '';
    $customerId = 0;

 // Save full name to customers table and get the customer ID
    if (!empty($fullName)) {
        $wpdb->insert(
            "{$wpdb->prefix}wp2023_customers",
            array('FullName' => $fullName),
            array('%s')
        );
        $customerId = $wpdb->insert_id;
    }

    if ($customerId <= 0) {
        wp_send_json_error(['message' => 'Failed to save customer information']);
        wp_die();
    }

    // Ensure files are present in the request
    if (empty($_FILES['wp2023-custom-image'])) {
        wp_send_json_error(['message' => 'No files provided']);
        wp_die();
    }

    $upload_overrides = array('test_form' => false);
    $files = $_FILES['wp2023-custom-image'];
    $upload_success = [];

    // Iterate through each file
    foreach ($files['name'] as $key => $name) {
        if ($files['error'][$key] == 0) {
            $uploadedfile = array(
                'name'     => $files['name'][$key],
                'type'     => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error'    => $files['error'][$key],
                'size'     => $files['size'][$key]
            );

            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

            if ($movefile && !isset($movefile['error'])) {
                // Insert into database
                $result = $wpdb->insert(
                    "{$wpdb->prefix}wp2023_images",
                    array(
                        'OriginalImagePath' => $movefile['url'],
                        'UploadDate' => current_time('mysql'),
                        'CustomerId' => $customerId,
                        // Add default values or null for other fields not being set here
                        'EditedImagePath' => null,
                        'Status' => 'uploaded',
                        'EditType' => null,
                        'Parameters' => null,
                        'ProductId' => 0, // Set to 0 or actual ProductId if available
                        'TemplateId' => 0, // Set to 0 or actual TemplateId if available
                    ),
                    array('%s', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%d')
                );

                if ($result) {
                    array_push($upload_success, array('name' => $name, 'url' => $movefile['url']));
                }
            } else {
                // Handle individual file upload error
                wp_send_json_error(array('message' => "Failed to upload file: {$name}", 'error' => $movefile['error']));
                wp_die();
            }
        } else {
            // Handle individual file upload error
            wp_send_json_error(array('message' => "Error uploading file: {$name}", 'error' => $files['error'][$key]));
            wp_die();
        }
    }

    // Return success response
    if (!empty($upload_success)) {
        wp_send_json_success(array('message' => 'Files uploaded successfully', 'uploaded_files' => $upload_success));
    } else {
        wp_send_json_error(array('message' => 'No files were uploaded'));
    }
    wp_send_json_success(['message' => 'Full name and images uploaded successfully']);
    wp_die(); // Required to terminate immediately and return a proper response
}

add_action('wp_ajax_handle_original_image_upload', 'handle_original_image_upload');
add_action('wp_ajax_nopriv_handle_original_image_upload', 'handle_original_image_upload');



remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);



function wp2023_enqueue_scripts() {
    if (is_page('khung_de_ban') || is_singular('product')) {
        // Enqueue Cropper CSS
        wp_enqueue_style('cropper-css', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.css', array(), '1.5.11');

        // Enqueue Cropper JS
        wp_enqueue_script('cropper-js', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.js', array('jquery'), '1.5.11', true);

        // Enqueue your custom CSS
        // wp_enqueue_style('wp2023-custom-style', get_template_directory_uri() . '/css/wp2023-style.css', array(), '1.0.0');

        // // Enqueue your custom JS
        // wp_enqueue_script('wp2023-custom-script', get_template_directory_uri() . '/js/wp2023-script.js', array('jquery'), '1.0.0', true);

        // Localize script to include dynamic data
        wp_localize_script('wp2023-custom-script', 'wp2023_settings', array(
            'maxUploads' => get_option('wp2023_max_images', '10') // Default to 10 if not set
        ));

        wp_localize_script( 'your-script-handle', 'wp2023_ajax_object', array( 
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'wp2023_image_upload' )
        ));
    }
}
add_action('wp_enqueue_scripts', 'wp2023_enqueue_scripts');

function my_theme_scripts() {
    wp_enqueue_script('my-custom-script', get_template_directory_uri() . '/js/custom-script.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'my_theme_scripts');