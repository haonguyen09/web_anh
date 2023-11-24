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

// require_once WP2023_PATH . 'includes.php';

// Plugin activation

register_activation_hook(__FILE__,'wp2023_ecommerce_activation');
function wp2023_ecommerce_activation() {
    // if (class_exists('WP2023_DB')) {
        // WP2023_DB::create_tables();
        global $wpdb;
       // Include the WordPress schema upgrade functions.
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $charset_collate = $wpdb->get_charset_collate();

    // Table creation SQL for wp2023_customers and wp2023_templates (without foreign keys)
    $table_customers_sql = "
        CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wp2023_customers (
            CustomerId INT NOT NULL AUTO_INCREMENT,
            FullName VARCHAR(255) NOT NULL,
            PRIMARY KEY (CustomerId)
        ) $charset_collate;";

    $table_templates_sql = "
        CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wp2023_templates (
            TemplateId INT NOT NULL AUTO_INCREMENT,
            TemplateName VARCHAR(255) NOT NULL,
            TemplatePath VARCHAR(255) NOT NULL,
            Description TEXT,
            CategoryId BIGINT(20) UNSIGNED NOT NULL,
            PRIMARY KEY (TemplateId)
        ) $charset_collate;";

    // Execute the SQL
    dbDelta($table_customers_sql);
    dbDelta($table_templates_sql);

    // Table creation SQL for wp2023_images (without foreign keys)
    $table_images_sql = "
        CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wp2023_images (
            ImageId INT NOT NULL AUTO_INCREMENT,
            OriginalImagePath VARCHAR(255) NOT NULL,
            EditedImagePath VARCHAR(255),
            UploadDate DATETIME NOT NULL,
            Status VARCHAR(255) DEFAULT NULL,
            EditType VARCHAR(255) DEFAULT NULL,
            Parameters TEXT DEFAULT NULL,
            CustomerId INT NOT NULL,
            ProductId INT NOT NULL,
            TemplateId INT NOT NULL,
            PRIMARY KEY (ImageId)
        ) $charset_collate;";

    // Execute the SQL
    dbDelta($table_images_sql);

    // Add foreign keys separately
    // Note: Make sure your tables are using InnoDB storage engine to support foreign keys.
    $foreign_keys_sql = "
        ALTER TABLE {$wpdb->prefix}wp2023_templates
        ADD FOREIGN KEY (CategoryId) REFERENCES {$wpdb->prefix}term_taxonomy(term_id)
        ON DELETE CASCADE;
        
        ALTER TABLE {$wpdb->prefix}wp2023_images
        ADD FOREIGN KEY (CustomerId) REFERENCES {$wpdb->prefix}wp2023_customers(CustomerId),
        ADD FOREIGN KEY (ProductId) REFERENCES {$wpdb->prefix}posts(ID),
        ADD FOREIGN KEY (TemplateId) REFERENCES {$wpdb->prefix}wp2023_templates(TemplateId);
    ";

    // Execute the SQL for adding foreign keys.
    // Wrapping in a try-catch block to avoid crashing the activation process.
    try {
        $wpdb->query($foreign_keys_sql);
    } catch (Exception $e) {
        error_log('Error adding foreign keys: ' . $e->getMessage());
    }

    // Check for errors after attempting to create foreign keys
    if ($wpdb->last_error) {
        error_log('Database error after adding foreign keys: ' . $wpdb->last_error);
    }
}



//Plugin deactivation
register_deactivation_hook(__FILE__,'wp2023_ecommerce_deactivation');
function wp2023_ecommerce_deactivation() {
    // require_once WP2023_PATH . 'uninstall.php';
}