<?php

namespace AI\Core;

use AI\Audits\Environment_Audit;
use AI\Audits\Security_Audit;
use AI\Audits\Score_Audit;
use AI\Audits\RTL_Audit;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action(
			'admin_menu',
			array( __CLASS__, 'register_menu' )
		);

        add_action(
            'admin_enqueue_scripts',
            array( __CLASS__, 'enqueue_assets' )
        );
	}

    /**
     * Register the admin menu.
     *
     * @return void
     */
	public static function register_menu() {

		add_menu_page(
			__( 'Arabia Inspector', 'arabia-inspector' ),
			__( 'Arabia Inspector', 'arabia-inspector' ),
			'manage_options',
			'arabia-inspector',
			array( __CLASS__, 'render_dashboard' ),
			'dashicons-search',
			65
		);
	}

    /**
     * Render the dashboard page with audit results.
     *
     * @return void
     */
	public static function render_dashboard() {

        /*
         * Run the audits and get results.
         */
        $environment_results = Environment_Audit::run();
        $environment_score   = Environment_Audit::get_score();

        $security_results    = Security_Audit::run();
        $security_score      = Security_Audit::get_score();

        $rtl_results         = RTL_Audit::run();
        $rtl_score           = RTL_Audit::get_score();

        $overall_score       = Score_Audit::get_overall_score();
        $overall_rating      = Score_Audit::get_rating( $overall_score );

		?>
        <div class="wrap">
            <div class="ai-dashboard-summary">

                <h1>
                    <?php esc_html_e(
                        'Arabia Inspector Dashboard',
                        'arabia-inspector'
                    ); ?>
                </h1>

                <div class="ai-overall-score">

                    <span class="ai-score-value">
                        <?php echo esc_html( $overall_score ); ?>/100
                    </span>
                    <span
                        class="ai-score-rating <?php echo esc_attr( self::get_rating_class( $overall_rating ) ); ?>"
                    >
                        <?php echo esc_html( $overall_rating ); ?>
                    </span>

                </div>

                <div class="ai-score-breakdown">

                    <div class="ai-score-item">
                        <strong><?php echo esc_html( $environment_score ); ?></strong>
                        <span>
                            <?php esc_html_e(
                                'Environment',
                                'arabia-inspector'
                            ); ?>
                        </span>
                    </div>

                    <div class="ai-score-item">
                        <strong><?php echo esc_html( $security_score ); ?></strong>
                        <span>
                            <?php esc_html_e(
                                'Security',
                                'arabia-inspector'
                            ); ?>
                        </span>
                    </div>

                    <div class="ai-score-item">
                        <strong><?php echo esc_html( $rtl_score ); ?></strong>
                        <span>
                            <?php esc_html_e(
                                'RTL',
                                'arabia-inspector'
                            ); ?>
                        </span>
                    </div>

                </div>

            </div>
        <?php
        
        // Define labels for each audit check.
        $environment_labels = array(
            'wordpress_version' => __( 'WordPress Version', 'arabia-inspector' ),
            'php_version'       => __( 'PHP Version', 'arabia-inspector' ),
            'language'          => __( 'Language', 'arabia-inspector' ),
            'rtl'               => __( 'RTL Enabled', 'arabia-inspector' ),
            'theme'             => __( 'Active Theme', 'arabia-inspector' ),
        );

        $security_labels = array(
            'wp_debug'      => __( 'WP Debug' , 'arabia-inspector' ),
            'file_editor'   => __( 'File Editor' , 'arabia-inspector' ),
            'ssl'           => __( 'SSL' , 'arabia-inspector' ),
            'file_mods'     => __( 'File Modifications' , 'arabia-inspector' ),
            'wp_debug_log'  => __( 'WP Debug Log' , 'arabia-inspector' ),
        );

        $rtl_labels = array(
            'site_language' => __( 'Site Language' , 'arabia-inspector' ),
            'rtl_enabled'   => __( 'RTL Enabled' , 'arabia-inspector' ),
            'rtl_stylesheet' => __( 'RTL Stylesheet', 'arabia-inspector' ),
        );

        self::render_audit_table(
            __( 'Environment Audit', 'arabia-inspector' ),
            $environment_results,
            $environment_labels
        );

        self::render_audit_table(
            __( 'Security Audit', 'arabia-inspector' ),
            $security_results,
            $security_labels
        );

        self::render_audit_table(
            __( 'RTL Audit', 'arabia-inspector' ),
            $rtl_results,
            $rtl_labels
        );
        ?>
        </div>
		<?php

	}

    /**
     * Render an audit table.
     *
     * @param string $title   Table title.
     * @param array  $results Audit results.
     * @param array  $labels  Audit labels.
     *
     * @return void
     */
    private static function render_audit_table(
        $title,
        $results,
        $labels
    ) {
        ?>
        <h2><?php echo esc_html( $title ); ?></h2>

            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Check', 'arabia-inspector' ); ?></th>
                        <th><?php esc_html_e( 'Value', 'arabia-inspector' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'arabia-inspector' ); ?></th>
                        <th><?php esc_html_e( 'Message', 'arabia-inspector' ); ?></th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ( $labels as $key => $label ) : ?>

                        <?php
                        $result  = $results[ $key ] ?? array();
                        $value   = $result['value'] ?? '';
                        $status  = $result['status'] ?? '—';
                        $message = $result['message'] ?? '—';
                        ?>

                        <tr>
                            <td><?php echo esc_html( $label ); ?></td>
                            <td><?php echo esc_html( $value ); ?></td>
                            <td><?php echo wp_kses_post(
                                    self::render_status_badge( $status )
                                ); ?>
                            </td>
                            <td><?php echo esc_html( $message ); ?></td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
        <?php
    }

    public static function render_status_badge( $status ) {

        $class = 'ai-badge';

        switch ( $status ) {

            case 'pass':
                $class .= ' ai-badge-pass';
                break;

            case 'warning':
                $class .= ' ai-badge-warning';
                break;

            case 'critical':
                $class .= ' ai-badge-critical';
                break;
        }

        return sprintf(
            '<span class="%1$s">%2$s</span>',
            esc_attr( $class ),
            esc_html( ucfirst( $status ) )
        );
    }

    public static function enqueue_assets() {

        wp_enqueue_style(
            'arabia-inspector-admin',
            AI_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            AI_VERSION
        );
    }

    /**
     * Get CSS class for a score rating.
     *
     * @param string $rating Score rating.
     *
     * @return string
     */
    private static function get_rating_class( $rating ) {

        switch ( $rating ) {

            case 'Excellent':
                return 'ai-rating-excellent';

            case 'Good':
                return 'ai-rating-good';

            case 'Needs Attention':
                return 'ai-rating-warning';

            case 'Critical':
                return 'ai-rating-critical';
        }

        return '';
    }
}