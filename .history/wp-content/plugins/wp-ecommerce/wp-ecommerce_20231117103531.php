<?php

/*
 * Plugin Name:       WordPress 2023 
 * Plugin URI:        #
 * Description:       Plugin photo frame and photo print
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Coder
 * Author URI:        #
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        #
 * Text Domain:       wp-photo
 * Domain Path:       /languages
 */


// Ensuring WordPress environment
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly outside of WordPress
}

define( 'WP2023_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP2023_URI', plugin_dir_url( __FILE__ ) );

// echo '<br>'.WP2023_PATH;
// echo '<br>'.WP2023_URI;
// die();

require_once WP2023_PATH . 'includes.php';

// Plugin activation

register_activation_hook(__FILE__,'wp2023_ecommerce_activation');
function wp2023_ecommerce_activation() {
    if (class_exists('WP2023_DB')) {
        // WP2023_DB::create_tables();
        global $wpdb;
        require_once( WP2023_PATH . 'includes/upgrade.php' );

        $charset_collate = $wpdb->get_charset_collate();

        $table_definitions = [
            'wp2023_customers' => "
                CREATE TABLE {$wpdb->prefix}wp2023_customers (
                    CustomerId INT NOT NULL AUTO_INCREMENT,
                    FullName VARCHAR(255) NOT NULL,
                    PRIMARY KEY (CustomerId)
                ) $charset_collate;",
    
            'wp2023_templates' => "
                CREATE TABLE {$wpdb->prefix}wp2023_templates (
                    TemplateId INT NOT NULL AUTO_INCREMENT,
                    TemplateName VARCHAR(255) NOT NULL,
                    TemplatePath VARCHAR(255) NOT NULL,
                    Description TEXT,
                    PRIMARY KEY (TemplateId)
                ) $charset_collate;",
    
            'wp2023_images' => "
                CREATE TABLE {$wpdb->prefix}wp2023_images (
                    ImageId INT NOT NULL AUTO_INCREMENT,
                    OriginalImagePath VARCHAR(255) NOT NULL,
                    EditedImagePath VARCHAR(255),
                    UploadDate DATETIME NOT NULL,
                    Status VARCHAR(255),
                    Edit_type VARCHAR(255) NOT NULL,
                    Parameters TEXT NOT NULL,
                    CustomerId INT NOT NULL,
                    ProductId INT NOT NULL,
                    TemplateId INT NOT NULL,
                    PRIMARY KEY (ImageId)
                ) $charset_collate;"
        ];

        foreach ($table_definitions as $table_name => $table_sql) {
            dbDelta($table_sql);
        }
    }
}



//Plugin deactivation
register_deactivation_hook(__FILE__,'wp2023_ecommerce_deactivation');
function wp2023_ecommerce_deactivation() {
    // require_once WP2023_PATH . 'uninstall.php';
}