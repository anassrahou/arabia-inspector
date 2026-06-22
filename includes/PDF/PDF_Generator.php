<?php 

namespace AI\PDF;

use Mpdf\Mpdf;

class PDF_Generator {

    public static function generate( $data ) {

        $mpdf = new Mpdf();

        $html = self::build_html( $data );

        $mpdf->WriteHTML( $html );

        return $mpdf->Output(
            'arabia-inspector-report.pdf',
            'S'
        );
    }

    private static function build_html( $data ) {

        $html  = '';

        $html = '
            <style>
            h1 {
                text-align: center;
            }

            h2 {
                border-bottom: 1px solid #ccc;
                padding-bottom: 5px;
            }

            .audit-item {
                margin-bottom: 10px;
            }
            </style>
        ';

        $html .= self::build_header( $data );

        $html .= self::build_scores( $data );

        $html .= self::build_audit(
            'Environment Audit',
            $data['audits']['environment']
        );

        $html .= self::build_audit(
            'Security Audit',
            $data['audits']['security']
        );

        $html .= self::build_audit(
            'RTL Audit',
            $data['audits']['rtl']
        );

        return $html;
    }

    private static function build_header( $data ) {

        return sprintf(
            '
            <h1>Arabia Inspector Report</h1>

            <p>
                <strong>Site:</strong> %s<br>

                <strong>Generated:</strong> %s<br>

                <strong>Version:</strong> %s
            </p>
            ',
            esc_html( $data['meta']['site_name'] ),
            esc_html( $data['meta']['generated_at'] ),
            esc_html( $data['meta']['version'] )
        );
    }

    private static function build_scores( $data ) {

        return sprintf(
            '
            <h2>Overall Score</h2>

            <p>
                %d/100 (%s)
            </p>
            ',
            $data['scores']['overall'],
            esc_html( $data['scores']['rating'] )
        );
    }

    private static function build_audit( $title, $results ) {

        $html = sprintf(
            '<h2>%s</h2>',
            esc_html( $title )
        );

        foreach ( $results as $check => $result ) {

            $html .= sprintf(
                '
                <p>
                    <strong>%s</strong><br>
                    Value: %s<br>
                    Status: %s

                </p>
                ',
                esc_html( $check ),
                esc_html( $result['value'] ),
                esc_html( strtoupper( $result['status'] ) )
            );
        }

        return $html;
    }

    private static function build_recommendations_section() {

    }
}