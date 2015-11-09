<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'motr_wp_production');
//define('DB_NAME', 'motr_wp1');

/** MySQL database username */
define('DB_USER', 'motr_wp1');

/** MySQL database password */
define('DB_PASSWORD', 'wgovYW3Mne');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define( 'WP_MEMORY_LIMIT', '64M' );
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '0Qes%3GNe*foh4 z0Nh ANL^A5szpLb_HQ$3m>nBR;w5k!`vLrW4wFH+A3xm|jvw');
define('SECURE_AUTH_KEY',  'Wfax[eJB-sISYxt+&D?;HF:rX?tXM|hTHy70O`/l -@-9~9,(2+a}Q40dWNyiat%');
define('LOGGED_IN_KEY',    'W8y~)^5$z7p)oYlQevxO5ty,ZnZda $>jO#]K.lOx1acZo%2>t+rFeoq%{CAB=`z');
define('NONCE_KEY',        'Mx:%~cja) SL(z9=4!^yl:s rKC`fphG{&mO)3|E%!Jk&iB>dH42_E)*6.`Hv_.$');
define('AUTH_SALT',        '}-y=5k{$23uXq*R6+;%#w`u$%6uVcc{@%64 -1U~_`Kj!u49NuhrQDmvmRlGOb%G');
define('SECURE_AUTH_SALT', '4L|[gw7lg`-J+%Se{#^W)a>yHLV;?dQpDjxDVB[3o6[$Z?He#DN]GsQ|.!;Rn$]F');
define('LOGGED_IN_SALT',   'x;^Xp.}.M?]HPQtFL~&bg2naG+E=ICN {3u^^Tm1Q u/YN(Lu_j(qgFk*N}|v<!)');
define('NONCE_SALT',       'jZ48)G7P-R^JA3~(F.#KS]S&4dHw3$~H|VWl;M5iR6Aqm8J`j-&kR_:8KF<qt1ZM');

/**#@-*/
define('FTP_PUBKEY','/var/www/id_rsa.pub');
define('FTP_PRIKEY','/var/www/id_rsa');
define('FTP_USER','motr');
define('FTP_PASS','');
define('FTP_HOST','motr.co:1122');
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
define( 'WP_DEBUG_LOG', true ); 
define( 'DEBUG_GROUPS', 'ACTIONS,default,woo' );
define( 'DEBUG_PARAMS', 'time,timedelta,memory,memorydelta,data,backtrace,url,server' );
//define( 'DEBUG_LOG', '/home/motr/www/motor-co.com/debug.log' );
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
