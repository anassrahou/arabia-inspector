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
            __( 'WordPress Version', 'arabia-inspector' ) => $wp_version,
            __( 'PHP Version', 'arabia-inspector' )       => phpversion(),
            __( 'Language', 'arabia-inspector' )          => get_bloginfo( 'language' ),
            __( 'RTL Enabled', 'arabia-inspector' )       => is_rtl(),
            __( 'Active Theme', 'arabia-inspector' )      => wp_get_theme()->get( 'Name' ),
        );
	}

    public static function get_score() {

        return 80;
    }
}