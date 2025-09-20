<?php

define( 'DB_NAME', getenv('MARIADB_DATABASE') ?: 'wordpress' );

/** Database username */
define( 'DB_USER', getenv('MARIADB_USER') ?: 'test' );

/** Database password */
define( 'DB_PASSWORD', getenv('MARIADB_PASSWORD') ?: '12345' );

/** Database hostname */
define( 'DB_HOST', getenv('MARIADB_HOST') ?: 'mariadb' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('AUTH_KEY',         'q,VrMu@W3N$8lsY~K;,&~}+mj|}PjcUdh)H0Lyk+jII0/ Plr^HP*(~IA#p#G%~p');
define('SECURE_AUTH_KEY',  'KoGFkg1,vS+gINeNrx*BjS-pl#j[/Pi24PqPshVD?rm.`F*+}mU9*b d[F# l.qR');
define('LOGGED_IN_KEY',    'E`{ub-R1I+7_0~$-wt6/7!},}uUc>Qwt#^?R[DUM~hfRDSnOz-LLc>z7s&!U?PMV');
define('NONCE_KEY',        'h<F3U;b@z H>[+=/+Qz[e-eB5vw>>1-:{9-D^<p3rg futlYzO:a;R!COqggpM+Y');
define('AUTH_SALT',        '>eX1*hly+,!f<<n1icTyla|MQi*|#_n{auFx+;mfyAwM[dvD{w1O`*]BobEE<PfT');
define('SECURE_AUTH_SALT', '%uHMhw~C/!0L3h9emJ9YkMER{Ua?k%8/|ZNce!5<XM6?Q+)^v6ex):t&WxIq+@Pi');
define('LOGGED_IN_SALT',   'nD*TTw#tk+uO{@iZ+YOHN`&*kuU@NMVSk/419Jr,,%Dfl-g?YdAi;` vW3N1FPs/');
define('NONCE_SALT',       '+f=j&zTc0Pv8#JZKbQ96S1U!& <_tuapL!C9n_^Yy&$2Q*,,`{x2~l.?q#ZScOF+');

$table_prefix = 'wp_';

define( 'WP_DEBUG', false );

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', '/var/www/html/' );
}

require_once ABSPATH . 'wp-settings.php';
