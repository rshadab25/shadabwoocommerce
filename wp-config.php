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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'woocommerce1');

/** MySQL database username */
define('DB_USER', 'newuser');

/** MySQL database password */
define('DB_PASSWORD', 'password');

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
define('AUTH_KEY',         '_:S(.Qlc.i/Cj-|k@OR79o[@`,+S@0eT|f`PSb#|tJIf#E<@zl lWHXLM|-i@rxB');
define('SECURE_AUTH_KEY',  'CHb.Mny ?Nn3+YUEISbxY:9H!O1/t:cH+%#,hmm(|~#{eu,D|z<4NiflM56ng&`Y');
define('LOGGED_IN_KEY',    'GKX(9*n8,Qs__u;C-r5oBW^)[rs|a_.g;%^mk__|tF*Uo5~zOes/}nftaXeanT+^');
define('NONCE_KEY',        'c@+:]>S&|PIhq|6_Nl,%T}cA~]~+jV[x[(U9xhqlu+DIG }+DQefW{V-@Osn,`RC');
define('AUTH_SALT',        '>{;G)@%`_N9 (s<de)S:g/SS&Ipc65UVxY%OmMZezoi+g[bP MjyU^~h-L*J8U4|');
define('SECURE_AUTH_SALT', '~iTmJ}Z~tu:nswP+MB?UbPf8tN`U;mk23Jd!FYhJYg8SdueD[qyKe;92>QTD{a5e');
define('LOGGED_IN_SALT',   '&br3,Jm|_c0te#5:X`k|Ud3_^3|0-##-=AGg}tWic:Bt,At#] e4bt]vWh4&(-9>');
define('NONCE_SALT',       'in7pg*}pKa5uV-2_%ccHzwnu/^JQ(km_hq(L)7;Zj>8b%wZcgVu{Tp<iEQg91>m2');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpui_';
# Disable Theme Editing
define( 'DISALLOW_FILE_EDIT', true );
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
