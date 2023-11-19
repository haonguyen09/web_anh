<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'hoanghamobile' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'mTeegt63QKqJMuSze4T5nVUjcpWkUSV35n1CsFYB13k1pgGjrOq4E28gvIPiJQer' );
define( 'SECURE_AUTH_KEY',  'N6gPrOTz9FFQKaNAVb6ZrQwsjA2cXDiOfedwZPW1cPB5ryLgKIuMfqUAfsD5sxly' );
define( 'LOGGED_IN_KEY',    'rZPqShE8naw1Pnp2X8LPaWBNTYA6aOCfTpP2ZrTxgfbyHfToBcBqR0sI1x9O43rR' );
define( 'NONCE_KEY',        'H7X80krVV0hqTKYl8LeRZH12jA2qtngg0gNNRQmvi3ZDy1EXhEhTCa3ez1zgJ8uZ' );
define( 'AUTH_SALT',        'We6NpneJu83QX7xFhqITOrqfJcrMNVVhq9LAIfOslONHpLoxX7XnocDL9t1Jpcy8' );
define( 'SECURE_AUTH_SALT', '8esADQjRDMz9838HXhM23puaaUiuIzB46yQQrP44rjeULtqHL267v6SU4U3TGZd7' );
define( 'LOGGED_IN_SALT',   'ZvlWDxWBXTKxeMPty63htAwTgCjWBFXLPFosR5xhBG8Gi1JubEugGXhRnRHs3FnU' );
define( 'NONCE_SALT',       'VqUprwgMojI8TX1u4HnhcwIlGBTzoaDWoKm76cqLLK4BE4NxuXboGz4vcGAAMNiY' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
