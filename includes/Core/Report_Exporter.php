<?php

namespace AI\Core;

use AI\Audits\Environment_Audit;
use AI\Audits\Security_Audit;
use AI\Audits\RTL_Audit;
use AI\Audits\Recommendation_Audit;
use AI\Audits\Score_Audit;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Report_Exporter {

    public static function generate_report() {
        $data = self::get_report_data();

        $report  = "=====================================\n";
        $report .= "ARABIA INSPECTOR REPORT\n";
        $report .= "=====================================\n\n";

        $report .= sprintf(
            "Overall Score: %d/100\n\n",
            $data['scores']['overall']
        );

        $report .= "ENVIRONMENT AUDIT\n";
        $report .= "-----------------\n";
        foreach ( $data['audits']['environment'] as $check => $result ) {
            $report .= sprintf(
                "%s: %s (%s)\n",
                $check,
                $result['value'],
                strtoupper( $result['status'] )
            );
        }
        $report .= "\n";

        $report .= "SECURITY AUDIT\n";
        $report .= "--------------\n";
        foreach ( $data['audits']['security'] as $check => $result ) {
            $report .= sprintf(
                "%s: %s (%s)\n",
                $check,
                $result['value'],
                strtoupper( $result['status'] )
            );
        }
        $report .= "\n";

        $report .= "RTL AUDIT\n";
        $report .= "---------\n";
        foreach ( $data['audits']['rtl'] as $check => $result ) {
            $report .= sprintf(
                "%s: %s (%s)\n",
                $check,
                $result['value'],
                strtoupper( $result['status'] )
            );
        }
        $report .= "\n";

        $report .= "RECOMMENDATIONS\n";
        $report .= "---------------\n";
        foreach ( $data['recommendations'] as $recommendation ) {
            $report .= sprintf(
                "[%s] %s\n%s\n\n",
                strtoupper( $recommendation['priority'] ),
                $recommendation['title'],
                $recommendation['message']
            );
        }

        $report .= "\n";

        return $report;
    }

    public static function download_report() {
        $report = self::generate_report();

        header( 'Content-Type: text/plain' );
        header( 'Content-Disposition: attachment; filename="arabia-inspector-report.txt"' );

        echo $report;
        exit;
    }

    public static function download_pdf() {
        wp_die( 'PDF engine not installed yet.' );
    }

    public static function get_report_data() {
        $environment = Environment_Audit::run();
        $security    = Security_Audit::run();
        $rtl         = RTL_Audit::run();

        $overall_score = Score_Audit::get_overall_score();

        return array(
            'meta' => array(
                'generated_at' => current_time( 'mysql' ),
                'plugin'       => defined('AI_PLUGIN_NAME') ? AI_PLUGIN_NAME : 'Arabia Inspector',
                'version'      => AI_VERSION, // Kept matching Admin.php constant configuration
                'site_name'    => get_bloginfo( 'name' ),
                'site_url'     => home_url(),
            ),

            'scores' => array(
                'overall'     => $overall_score,
                'rating'      => Score_Audit::get_rating( $overall_score ),
                'environment' => Environment_Audit::get_score(),
                'security'    => Security_Audit::get_score(),
                'rtl'         => RTL_Audit::get_score(),
            ),

            'audits' => array(
                'environment' => $environment,
                'security'    => $security,
                'rtl'         => $rtl,
            ),

            'recommendations' => Recommendation_Audit::get_recommendations(),
        );
    }
}