<?php

namespace AI\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Report_Exporter {

    public static function generate_report() {

        $overall_score =
            \AI\Audits\Score_Audit::get_overall_score();

            return sprintf(
                "Arabia Inspector Report\n\nOverall Score: %d/100\n",
                $overall_score
            );
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