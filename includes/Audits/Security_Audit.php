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
        $file_editor = defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT;
        $ssl_enabled = is_ssl();
        $file_mods = defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
        $wp_debug_log = defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG;

        return array(
            'wp_debug' => array(
                'value'   => $wp_debug ? __( 'Yes', 'arabia-inspector' ) : __( 'No', 'arabia-inspector' ),
                'status'  => $wp_debug ? 'warning' : 'pass',
                'message' => $wp_debug 
                    ? __( 'WP_DEBUG is enabled. It is recommended to disable it on production sites for better security.', 'arabia-inspector' ) 
                    : __( 'WP_DEBUG is disabled, which is good for security.', 'arabia-inspector' ),
            ),
            'file_editor' => array(
                'value'   => $file_editor ? __( 'No', 'arabia-inspector' ) : __( 'Yes', 'arabia-inspector' ),
                'status'  => $file_editor ? 'pass' : 'warning',
                'message' => $file_editor
                    ? __( 'File editing is disabled, which is good for security.', 'arabia-inspector' ) 
                    : __( 'File editing is enabled. It is recommended to disable it on production sites for better security.', 'arabia-inspector' ),
            ),
            'ssl' => array(
                'value'   => $ssl_enabled ? __( 'Yes', 'arabia-inspector' ) : __( 'No', 'arabia-inspector' ),
                'status'  => $ssl_enabled ? 'pass' : 'warning',
                'message' => $ssl_enabled
                    ? __( 'SSL is enabled, which is good for security.', 'arabia-inspector' ) 
                    : __( 'SSL is not enabled. It is recommended to enable SSL for better security.', 'arabia-inspector' ),
             ),
             'file_mods' => array(
                'value'   => $file_mods ? __( 'Disabled', 'arabia-inspector' ) : __( 'Enabled', 'arabia-inspector' ),
                'status'  => $file_mods ? 'pass' : 'warning',
                'message' => $file_mods
                    ? __( 'File modifications are disabled, which is good for security.', 'arabia-inspector' ) 
                    : __( 'File modifications are enabled. It is recommended to disable them on production sites for better security.', 'arabia-inspector' ),
             ),
             'wp_debug_log' => array(
                'value'   => $wp_debug_log ? __( 'Enabled', 'arabia-inspector' ) : __( 'Disabled', 'arabia-inspector' ),
                'status'  => $wp_debug_log ? 'warning' : 'pass',
                'message' => $wp_debug_log
                    ? __( 'WP_DEBUG_LOG is enabled. It is recommended to disable it on production sites for better security.', 'arabia-inspector' ) 
                    : __( 'WP_DEBUG_LOG is disabled, which is good for security.', 'arabia-inspector' ),
             ),
        );
    }

    public static function get_score() {

        $score = 100;

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $score -= 20;
        }

        if ( ! defined( 'DISALLOW_FILE_EDIT' ) || ! DISALLOW_FILE_EDIT ) {
            $score -= 20;
        }

        if ( ! is_ssl() ) {
            $score -= 30;
        }

        if ( ! defined( 'DISALLOW_FILE_MODS' ) || ! DISALLOW_FILE_MODS ) {
            $score -= 20;
        }

        if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
            $score -= 10;
        }

        return max( 0, $score );
    }
}