<?php
/**
 * Plugin Name: Arabia Inspector
 * Plugin URI: https://github.com/anassrahou/arabia-inspector
 * Description: Audits WordPress websites for RTL compatibility, Arabic best practices, security, and performance.
 * Version: 1.0.0-alpha
 * Author: Anass Rahou
 * License: GPL-2.0-or-later
 * Text Domain: arabia-inspector
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AI_VERSION', '1.0.0-alpha' );
define( 'AI_PLUGIN_FILE', __FILE__ );
define( 'AI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once AI_PLUGIN_DIR . 'includes/Core/Plugin.php';

add_action(
	'plugins_loaded',
	array( 'AI\\Core\\Plugin', 'init' )
);