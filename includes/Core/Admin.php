<?php

namespace AI\Core;

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
		?>

		<div class="wrap">
			<h1><?php esc_html_e( 'Arabia Inspector', 'arabia-inspector' ); ?></h1>

			<p>
				<?php esc_html_e(
					'Welcome to Arabia Inspector.',
					'arabia-inspector'
				); ?>
			</p>
		</div>

		<?php
	}
}