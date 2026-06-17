<?php

namespace AI\Core;

use AI\Audits\Environment_Audit;
use AI\Audits\Security_Audit;

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

        $audit_results      = Environment_Audit::run();
        $score              = Environment_Audit::get_score();
        $security_results   = Security_Audit::run();
        $security_score     = Security_Audit::get_score();
		?>

        <div class="notice notice-info inline">
            <p>
                <strong>
                    <?php
                    printf(
                        esc_html__( 'Environment Score: %d/100', 'arabia-inspector' ),
                        $score
                    );
                    ?>
                </strong>
            </p>
        </div>

        <h2><?php esc_html_e( 'Environment Audit', 'arabia-inspector' ); ?></h2>
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

                <?php

                // Define labels for each audit check.
                $labels = array(
                    'wordpress_version' => __( 'WordPress Version', 'arabia-inspector' ),
                    'php_version'       => __( 'PHP Version', 'arabia-inspector' ),
                    'language'          => __( 'Language', 'arabia-inspector' ),
                    'rtl'               => __( 'RTL Enabled', 'arabia-inspector' ),
                    'theme'             => __( 'Active Theme', 'arabia-inspector' ),
                );

                foreach ( $labels as $key => $label ) :

                    $status = '—'; // Default status
                    $message = '—'; // Default message
                    // Get the value from audit results, handling cases where it might be an array or boolean.
                    $value = $audit_results[ $key ] ?? '';

                    // Handle cases where value is an array (e.g., PHP version with status and message).
                    if ( is_array( $value ) ) {
                        $status = $value['status'] ?? '—';
                        $message = $value['message'] ?? '—';
                        $value = $value['value'] ?? '';
                    }

                    // Convert boolean values to human-readable format.
                    if ( is_bool( $value ) ) {
                        $value = $value
                            ? __( 'Yes', 'arabia-inspector' )
                            : __( 'No', 'arabia-inspector' );
                    }

                ?>

                <tr>
                    <td><?php echo esc_html( $label ); ?></td>
                    <td><?php echo esc_html( $value ); ?></td>
                    <td><?php echo esc_html( $status ); ?></td>
                    <td><?php echo esc_html( $message ); ?></td>
                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

        <h2><?php esc_html_e( 'Security Audit', 'arabia-inspector' ); ?></h2>

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
                <?php $security_labels = array(
                    'wp_debug'      => __( 'WP Debug' , 'arabia-inspector' ),
                    'file_editor'   => __( 'File Editor' , 'arabia-inspector' ),
                    'ssl'           => __( 'SSL' , 'arabia-inspector' ),
                    'file_mods'     => __( 'File Modifications' , 'arabia-inspector' ),
                    'wp_debug_log'  => __( 'WP Debug Log' , 'arabia-inspector' ),
                ); ?>

                <?php 
                foreach ( $security_labels as $key => $label ) :
                    
                    $result     = $security_results[ $key ] ?? array();
                    $value      = $result['value'] ?? '';
                    $status     = $result['status'] ?? '—';
                    $message    = $result['message'] ?? '—';
                ?>

                    <tr>
                        <td><?php echo esc_html( $label ); ?></td>
                        <td><?php echo esc_html( $value ); ?></td>
                        <td><?php echo esc_html( $status ); ?></td>
                        <td><?php echo esc_html( $message ); ?></td>
                    </tr>
                <?php
                endforeach;
                
                ?>
            </tbody>
                
        </table>

		<?php
	}
}