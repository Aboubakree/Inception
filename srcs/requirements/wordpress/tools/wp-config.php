<?php

define( 'DB_NAME', getenv('MARIADB_DATABASE') );

/** Database username */
define( 'DB_USER', getenv('MARIADB_USER') );

/** Database password */
define( 'DB_PASSWORD', file_get_contents('/run/secrets/mariadb_password'));

/** Database hostname */
define( 'DB_HOST', getenv('MARIADB_HOST') );

$table_prefix = 'wp_';

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', '/var/www/html/' );
}

require_once ABSPATH . 'wp-settings.php';
