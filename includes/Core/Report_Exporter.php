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

        $overall_score =
            \AI\Audits\Score_Audit::get_overall_score();

        $environment_results =
            Environment_Audit::run();

        $security_results =
            Security_Audit::run();

        $rtl_results =
            RTL_Audit::run();

        $recommendations =
            Recommendation_Audit::get_recommendations();

        $report  = "=====================================\n";
        $report .= "ARABIA INSPECTOR REPORT\n";
        $report .= "=====================================\n\n";

        $report .= sprintf(
            "Overall Score: %d/100\n\n",
            $overall_score
        );

        $report .= "ENVIRONMENT AUDIT\n";
        $report .= "-----------------\n";

        foreach ( $environment_results as $check => $result ) {

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

        foreach ( $security_results as $check => $result ) {

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

        foreach ( $rtl_results as $check => $result ) {

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

        foreach ( $recommendations as $recommendation ) {

            $report .= sprintf(
                "[%s] %s\n%s\n\n",
                strtoupper(
                    $recommendation['priority']
                ),
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
        header(
            'Content-Disposition: attachment; filename="arabia-inspector-report.txt"'
        );

        echo $report;
        exit;
    }
}