<?php
namespace AI\Audits;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Recommendation_Audit {

    public static function get_recommendations() {

        $recommendations = array();

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

            $recommendations[] = array(
                'priority' => 'high',
                'title'    => __( 'Disable WP_DEBUG', 'arabia-inspector' ),
                'message'  => __(
                    'WP_DEBUG should be disabled on production websites.',
                    'arabia-inspector'
                ),
            );
        }

        if ( ! is_ssl() ) {

            $recommendations[] = array(
                'priority' => 'high',
                'title'    => __( 'Enable HTTPS', 'arabia-inspector' ),
                'message'  => __(
                    'Your website is not using SSL.',
                    'arabia-inspector'
                ),
            );
        }

        if (
            ! defined( 'DISALLOW_FILE_EDIT' ) ||
            ! DISALLOW_FILE_EDIT
        ) {

            $recommendations[] = array(
                'priority' => 'medium',
                'title'    => __( 'Disable Theme and Plugin Editor', 'arabia-inspector' ),
                'message'  => __(
                    'Set DISALLOW_FILE_EDIT to true.',
                    'arabia-inspector'
                ),
            );
        }

        if (
            defined( 'WP_DEBUG_LOG' ) &&
            WP_DEBUG_LOG
        ) {

            $recommendations[] = array(
                'priority' => 'low',
                'title'    => __( 'Disable Debug Log', 'arabia-inspector' ),
                'message'  => __(
                    'Debug logs may expose sensitive information.',
                    'arabia-inspector'
                ),
            );
        }

        usort(
            $recommendations,
            function ( $a, $b ) {

                $priorities = array(
                    'high'   => 3,
                    'medium' => 2,
                    'low'    => 1,
                );

                return $priorities[ $b['priority'] ]
                    <=> $priorities[ $a['priority'] ];
            }
        );

        return $recommendations;
    }
}