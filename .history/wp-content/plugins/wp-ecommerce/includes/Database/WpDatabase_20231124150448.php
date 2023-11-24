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



    // Method to retrieve a category ID by its name
    public static function get_category_id_by_name($category_name) {
        global $wpdb;
        $term = $wpdb->get_var($wpdb->prepare(
            "SELECT term_id FROM {$wpdb->prefix}terms WHERE name = %s", 
            $category_name
        ));

        return is_null($term) ? 0 : (int) $term;
    }

    // Method to seed the templates table with initial data
    public static function seed_templates() {
        global $wpdb;

        // Define your category to template mappings
        $template_data = [
            'Khung để bàn' => [
                'TemplateName' => 'Template for photo upload and editing',
                'TemplatePath' => 'template-parts/templates-path/desktop-frame-template.php',
                'Description' => 'Template used for uploading and editing photos for desktop frames',
            ],
            'Khung treo tường' => [
                'TemplateName' => 'Template for wall photo upload and editing',
                'TemplatePath' => '/path/to/wall-frame-template',
                'Description' => 'Template used for uploading and editing photos for wall frames',
            ],
            'Khung khắc laser' => [
                'TemplateName' => 'Template for laser engraving and PDF editing',
                'TemplatePath' => '/path/to/laser-engraving-template',
                'Description' => 'Template allows photo uploads and PDF text editing for laser-engraved frames',
            ],
            'Khung bằng khen' => [
                'TemplateName' => 'Template for award certificate upload and editing',
                'TemplatePath' => '/path/to/award-certificate-template',
                'Description' => 'Template allows for uploading award certificate images or editing existing PDF templates',
            ],
            'Khung quảng cáo' => [
                'TemplateName' => 'Advertisement frame template',
                'TemplatePath' => '/path/to/advertisement-frame-template',
                'Description' => 'Template for advertisement frames without a purchase button',
            ],
            'Khung tranh canvas' => [
                'TemplateName' => 'Canvas frame template',
                'TemplatePath' => '/path/to/canvas-frame-template',
                'Description' => 'Template for selling pre-printed canvas frames',
            ],
            // ... add additional categories and templates as needed ...
        ];

        foreach ($template_data as $category_name => $template_details) {
            $category_id = self::get_category_id_by_name($category_name);

             // Check if a template for this category already exists
        $existing_template = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}wp2023_templates WHERE CategoryId = %d", 
            $category_id
        ));

            if ($category_id > 0) {
                $wpdb->insert(
                    "{$wpdb->prefix}wp2023_templates",
                    [
                        'TemplateName' => $template_details['TemplateName'],
                        'TemplatePath' => $template_details['TemplatePath'],
                        'Description' => $template_details['Description'],
                        'CategoryId' => $category_id,
                    ],
                    ['%s', '%s', '%s', '%d']
                );

                // Check for errors
                if ($wpdb->last_error) {
                    error_log('Database error while seeding templates: ' . $wpdb->last_error);
                    // Optionally, handle errors further, like displaying a message to the admin
                }
            } else {
                error_log("Could not find category ID for: $category_name");
                // Optionally, handle errors further
            }
        }
    }
}


// When you want to create tables and seed data, you call:
// // WP2023_DB::create_tables();
// WP2023_DB::seed_templates();