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
        require_once AI_PLUGIN_DIR . 'includes/Audits/Environment_Audit.php';

		self::load_componenets();
	}

    public static function load_componenets() {
     
        Admin::init();
    }
}