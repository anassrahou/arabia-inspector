<?php

namespace AI\Audits;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * RTL Audit class to check if the site is using a right-to-left language and if the RTL support is enabled.
 */
class RTL_Audit {

    public static function run() {

        $language = get_bloginfo( 'language' );
        $rtl = is_rtl();
        $theme = wp_get_theme();

        if ( strpos( $language, 'ar' ) !== false ) {

            $rtl_status = $rtl ? 'pass' : 'warning';
            
            $rtl_message = $rtl
                ? __( 'RTL support is correctly configured.', 'arabia-inspector' )
                : __( 'Site language is Arabic but RTL support is disabled.', 'arabia-inspector' );

        } else {

            $rtl_status = 'pass';

            $rtl_message = __( 'RTL support is not required for the current site language.', 'arabia-inspector' );
        }

        $rtl_style = file_exists(
            $theme->get_stylesheet_directory() . '/rtl.css'
        );

        return array (
            'site_language' => array(
                'value'   => $language,
                'status'  => 'pass',
                'message' => __( 'Site language is configured.', 'arabia-inspector' ),
            ),
            'rtl_enabled' => array(
                'value'   => $rtl
                    ? __( 'Yes', 'arabia-inspector' )
                    : __( 'No', 'arabia-inspector' ),
                'status'  => $rtl_status,
                'message' => $rtl_message,
            ),
            'rtl_stylesheet' => array(
                'value'   => $rtl_style
                    ? __( 'Yes', 'arabia-inspector' )
                    : __( 'No', 'arabia-inspector' ),
                'status'  => $rtl_style ? 'pass' : 'warning',
                'message' => $rtl_style
                    ? __( 'RTL stylesheet file was detected in the active theme.', 'arabia-inspector' )
                    : __( 'RTL stylesheet file was not found in the active theme.', 'arabia-inspector' ),
            ),
        );
    }

    public static function get_score() {
        
        $language = get_bloginfo( 'language' );
        $rtl = is_rtl();

        $score = 100; // Default to pass

        if  ( strpos( $language, 'ar' ) !== false ) {
            
            if ( !$rtl ) {
                $score -= 50; // Warning if RTL is not enabled for Arabic language

            }

            if ( ! $rtl_style ) {
                $score -= 25;
            }
        }

        return max( 0, $score ); // For non-Arabic languages, RTL is not required, so we consider it a pass.
    }

}