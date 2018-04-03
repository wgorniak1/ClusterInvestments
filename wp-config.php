<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Azure connection
define('AZURE_USER','cluster.investments');
define('AZURE_PASSWORD','aLa@q8u9');
define('AZURE_DOMAIN','https://doekodev.azurewebsites.net');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'doekogroupxp_doeko1');

/** MySQL database username */
define('DB_USER', 'root');
//define('DB_USER', 'doekogroupxp_doeko1');

/** MySQL database password */
define('DB_PASSWORD', 'sKs@q8u1');
//define('DB_PASSWORD', 'aGz9gJuc');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'O3~EQ[h.<^)zquz/.X<rT7>@%FcP51uiTw)8|k{ki2@$g|G^79w:vD!J!4F=9&x}');
define('SECURE_AUTH_KEY',  ',Ym#v=0F_rZK{#!3fKbi@t^%n0uP%C_]=<[NC6XCS;Bin/4nD5Ixr.|Bh>=Si-O&');
define('LOGGED_IN_KEY',    'Sl%ldpRfMnl?F~4C1UXwJ-Qd5O?PSd:CBs]);FX4x*l4J~}`U;?3v7*VIofF2j9e');
define('NONCE_KEY',        'QnFUF>UAf~QV{*+),{xQ(4(Y8L!?/rKWKeI9qB5rjEf#YVpgMJ}!?yY}d)3f88KA');
define('AUTH_SALT',        'rT/Qw:..$;Clmv[_}dA- bW[uXZjN 4tf]KN>Wzzm`PfNR@.V55%P/nZ)td_jxh8');
define('SECURE_AUTH_SALT', 'Nir&(m+y0J3LW9wF:*eqYRe>)p>V*RNKeMy2~jMhBT.{UCec|5K&,t^iB:0]n!l}');
define('LOGGED_IN_SALT',   'rdgA|Xk!9.bEP0FReD9#}J)YPZ$*[d0r+=fe>x5@&EQ2BEL?G}CmcwgA$pIulV$G');
define('NONCE_SALT',       '_9cxQ*1]cqx!)@6ZmtUDz`Y_kDL]j8$&v03nvE)_8FR,fO3pQU1<piV2{0C|-Rqb');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
