<?php

// COPY THIS FILE TO wp-config.local.php and enter your local DB settings


// MySQL settings
/** The name of the database for WordPress */

define('DB_NAME', 'demowp_glosscartel');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */

// used to override the wp_options and
// dynamically set the site for this environment
// http://codex.wordpress.org/Editing_wp-config.php
define("WP_HOME", "http://localhost/demowp/glosscartel/");
define("WP_SITEURL", "http://localhost/demowp/glosscartel/");

// used to determine environment from easily accessible constant
define('VIA_ENVIRONMENT', 'production');
