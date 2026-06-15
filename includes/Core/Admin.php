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
	 * Register admin page.
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
	 * Dashboard output.
	 *
	 * @return void
	 */
	public static function render_dashboard() {

        $audit_results = Environment_Audit::run();
		?>

        <table class="widefat striped">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Check', 'arabia-inspector' ); ?></th>
                    <th><?php esc_html_e( 'Value', 'arabia-inspector' ); ?></th>
                </tr>
            </thead>
            <tbody>

                <?php

                $labels = array(
                    'wordpress_version' => __( 'WordPress Version', 'arabia-inspector' ),
                    'php_version'       => __( 'PHP Version', 'arabia-inspector' ),
                    'language'          => __( 'Language', 'arabia-inspector' ),
                    'rtl'               => __( 'RTL Enabled', 'arabia-inspector' ),
                    'theme'             => __( 'Active Theme', 'arabia-inspector' ),
                );

                foreach ( $labels as $key => $label ) :

                    $value = $audit_results[ $key ] ?? '';

                    if ( is_bool( $value ) ) {
                        $value = $value
                            ? __( 'Yes', 'arabia-inspector' )
                            : __( 'No', 'arabia-inspector' );
                    }

                ?>

                <tr>
                    <td><?php echo esc_html( $label ); ?></td>
                    <td><?php echo esc_html( $value ); ?></td>
                </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

		<?php
	}
}