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
        $php_version = phpversion();
        
        if ( version_compare( $php_version, '8.1' , '<' ) ) {
            
            $status ='warning';
            $message = __( 'PHP version is below 8.1, which may lead to compatibility issues.', 'arabia-inspector' );
        } else {
            $status = 'pass';
            $message = __( 'PHP version is compatible.', 'arabia-inspector' );
        }

        return array(
            'wordpress_version' => array(
                'value'   => $wp_version,
                'status'  => version_compare( $wp_version, '6.0', '>=' ) ? 'pass' : 'warning',
                'message' => version_compare( $wp_version, '6.0', '>=' ) ? __( 'WordPress version meets the minimum recommended requirement.', 'arabia-inspector' ) : __( 'Consider updating WordPress to the latest version for better security and performance.', 'arabia-inspector' ),
            ),
            'php_version' => array(
                'value'   => $php_version,
                'status'  => $status,
                'message' => $message,
            ),
            'language'          => get_bloginfo( 'language' ),
            'rtl'               => is_rtl(),
            'theme'             => wp_get_theme()->get( 'Name' ),
        );
	}

    public static function get_score() {

        $score = 100;

        if ( version_compare( phpversion(), '8.1' , '<' ) ) {
            $score -= 30;
        }

        return max( 0, $score );
    }
}