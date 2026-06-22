<?php 

namespace AI\PDF;

use Mpdf\Mpdf;

class PDF_Generator {

    public static function generate( $data ) {

        $mpdf = new Mpdf();

        $html = '<h1>Arabia Inspector Report</h1>';

        $html .= sprintf(
            '<p>Overall Score: %d/100</p>',
            $data['scores']['overall']
        );

        $mpdf->WriteHTML( $html );

        return $mpdf->Output(
            'arabia-inspector-report.pdf',
            'S'
        );
    }
}