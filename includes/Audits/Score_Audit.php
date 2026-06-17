<?php

namespace AI\Audits;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Score_Audit {

    public static function get_overall_score() {
        $environment_score = Environment_Audit::get_score();
        $security_score = Security_Audit::get_score();

        // Simple average of the two scores for overall score
        $overall_score = ( $environment_score + $security_score ) / 2;

        return round( $overall_score );
    }
}