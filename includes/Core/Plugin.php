<?php

namespace AI\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

	/**
	 * Bootstrap plugin.
	 *
	 * @return void
	 */
	public static function init() {

		require_once AI_PLUGIN_DIR . 'includes/Core/Admin.php';

		Admin::init();
	}
}