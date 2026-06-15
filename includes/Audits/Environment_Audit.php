<?php

namespace AI\Audits;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Environment_Audit {

	/**
	 * Run environment audit.
	 *
	 * @return array
	 */
	public static function run() {

        global $wp_version;
        
		return array(
            'wordpress_version' => $wp_version,
        );
	}
}