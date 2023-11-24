<?php
class WP2023_DB {
    public static function create_tables() {
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

            'wp2023_templates' => "CREATE TABLE {$wpdb->prefix}wp2023_templates (
                TemplateId INT NOT NULL AUTO_INCREMENT,
                TemplateName VARCHAR(255) NOT NULL,
                TemplatePath VARCHAR(255) NOT NULL,
                Description TEXT,
                CategoryId BIGINT(20) UNSIGNED NOT NULL,
                PRIMARY KEY (TemplateId),
                FOREIGN KEY (CategoryId) REFERENCES {$wpdb->prefix}term_taxonomy(term_id) ON DELETE CASCADE
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
                    ProductId INT NOT NULL, // Assuming linking to WooCommerce products
                    TemplateId INT NOT NULL,
                    PRIMARY KEY (ImageId),
                    FOREIGN KEY (CustomerId) REFERENCES {$wpdb->prefix}wp2023_customers(CustomerId),
                    FOREIGN KEY (ProductId) REFERENCES {$wpdb->prefix}posts(ID), // Linking to WooCommerce products
                    FOREIGN KEY (TemplateId) REFERENCES {$wpdb->prefix}wp2023_templates(TemplateId)
                ) $charset_collate;"
        ];

        foreach ($table_definitions as $table_name => $table_sql) {
            dbDelta($table_sql);
        }

        // Check for errors
        if ($wpdb->last_error) {
            error_log('Database error: ' . $wpdb->last_error);
            // Optionally, handle errors further, like displaying a message to the admin
        }
    }


    
}