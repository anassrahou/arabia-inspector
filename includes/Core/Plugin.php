<?php

namespace AI\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

    /**
     * Initialize the plugin.
     *
     * @return void
     */
	public static function init() {

		require_once AI_PLUGIN_DIR . 'includes/Core/Admin.php';
        require_once AI_PLUGIN_DIR . 'includes/Audits/Environment_Audit.php';
        require_once AI_PLUGIN_DIR . 'includes/Audits/Security_Audit.php';
        require_once AI_PLUGIN_DIR . 'includes/Audits/Score_Audit.php';
        require_once AI_PLUGIN_DIR . 'includes/Audits/RTL_Audit.php';

		self::load_components();
	}

    private static function load_components() {
     
        Admin::init();
    }
}