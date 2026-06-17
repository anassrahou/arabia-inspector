<?php

namespace AI\Audits;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Security_Audit {
    
    /**
     * Run security audit.
     *
     * @return array
     */
    public static function run() {

        $wp_debug = defined( 'WP_DEBUG' ) && WP_DEBUG;

        return array(
            'wp_debug' => array(
                'value'   => $wp_debug ? 'Yes' : 'No',
                'status'  => $wp_debug ? 'warning' : 'pass',
                'message' => $wp_debug ? __( 'WP_DEBUG is enabled. It is recommended to disable it on production sites for better security.', 'arabia-inspector' ) : __( 'WP_DEBUG is disabled, which is good for security.', 'arabia-inspector' ),
            ),
        );
    }
}