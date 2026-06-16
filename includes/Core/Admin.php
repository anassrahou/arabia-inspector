<?php

namespace AI\Core;

use AI\Audits\Environment_Audit;

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

        $audit_results = Environment_Audit::run();
        $score         = Environment_Audit::get_score();
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

        <table class="widefat striped">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Check', 'arabia-inspector' ); ?></th>
                    <th><?php esc_html_e( 'Value', 'arabia-inspector' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'arabia-inspector' ); ?></th>
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
                    // Get the value from audit results, handling cases where it might be an array or boolean.
                    $value = $audit_results[ $key ] ?? '';

                    // Handle cases where value is an array (e.g., PHP version with status and message).
                    if ( is_array( $value ) ) {
                        $status = $value['status'] ?? '—';
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
                </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

		<?php
	}
}