<?php
class WP2023_DB {
    public static function create_tables() {
        global $wpdb;
        require_once( WP2023_PATH . 'includes/upgrade.php' );

        $charset_collate = $wpdb->get_charset_collate();

        $table_definitions = [
            'wp2023_categories' => "
                CREATE TABLE {$wpdb->prefix}wp2023_categories (
                    CategoryId INT NOT NULL AUTO_INCREMENT,
                    CategoryName VARCHAR(255) NOT NULL,
                    Description TEXT,
                    PRIMARY KEY (CategoryId)
                ) $charset_collate;",

            'wp2023_products' => "
                CREATE TABLE {$wpdb->prefix}wp2023_products (
                    ProductId INT NOT NULL AUTO_INCREMENT,
                    ProductName VARCHAR(255) NOT NULL,
                    Price DECIMAL(10, 2) NOT NULL,
                    Description TEXT,
                    ImageURL VARCHAR(255),
                    CategoryId INT NOT NULL,
                    PRIMARY KEY (ProductId)
                    -- FOREIGN KEY (CategoryId) REFERENCES {$wpdb->prefix}wp2023_categories(CategoryId)
                ) $charset_collate;",

            'wp2023_customers' => "
                CREATE TABLE {$wpdb->prefix}wp2023_customers (
                    CustomerId INT NOT NULL AUTO_INCREMENT,
                    CustomerName VARCHAR(255) NOT NULL,
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
                    -- FOREIGN KEY (CustomerId) REFERENCES {$wpdb->prefix}wp2023_customers(CustomerId)
                    -- FOREIGN KEY (ProductId) REFERENCES {$wpdb->prefix}wp2023_products(ProductId)
                    -- FOREIGN KEY (TemplateId) REFERENCES {$wpdb->prefix}wp2023_templates(TemplateId)
                ) $charset_collate;"
        ];

        foreach ($table_definitions as $table_name => $table_sql) {
            if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}{$table_name}'") != "{$wpdb->prefix}{$table_name}") {
                dbDelta($table_sql);
            }
        }
    }
}