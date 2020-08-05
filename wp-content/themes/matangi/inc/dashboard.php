<?php
/**
 * Builds our admin page.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'matangi_create_menu' ) ) {
	add_action( 'admin_menu', 'matangi_create_menu' );
	/**
	 * Adds our "Matangi" dashboard menu item
	 *
	 */
	function matangi_create_menu() {
		$matangi_page = add_theme_page( 'Matangi', 'Matangi', apply_filters( 'matangi_dashboard_page_capability', 'edit_theme_options' ), 'matangi-options', 'matangi_settings_page' );
		add_action( "admin_print_styles-$matangi_page", 'matangi_options_styles' );
	}
}

if ( ! function_exists( 'matangi_options_styles' ) ) {
	/**
	 * Adds any necessary scripts to the Matangi dashboard page
	 *
	 */
	function matangi_options_styles() {
		wp_enqueue_style( 'matangi-options', get_template_directory_uri() . '/css/admin/admin-style.css', array(), MATANGI_VERSION );
	}
}

if ( ! function_exists( 'matangi_settings_page' ) ) {
	/**
	 * Builds the content of our Matangi dashboard page
	 *
	 */
	function matangi_settings_page() {
		?>
		<div class="wrap">
			<div class="metabox-holder">
				<div class="matangi-masthead clearfix">
					<div class="matangi-container">
						<div class="matangi-title">
							<a href="<?php echo esc_url(MATANGI_THEME_URL); ?>" target="_blank"><?php esc_html_e( 'Matangi', 'matangi' ); ?></a> <span class="matangi-version"><?php echo esc_html( MATANGI_VERSION ); ?></span>
						</div>
						<div class="matangi-masthead-links">
							<?php if ( ! defined( 'MATANGI_PREMIUM_VERSION' ) ) : ?>
								<a class="matangi-masthead-links-bold" href="<?php echo esc_url(MATANGI_THEME_URL); ?>" target="_blank"><?php esc_html_e( 'Premium', 'matangi' );?></a>
							<?php endif; ?>
							<a href="<?php echo esc_url(MATANGI_WPKOI_AUTHOR_URL); ?>" target="_blank"><?php esc_html_e( 'WPKoi', 'matangi' ); ?></a>
                            <a href="<?php echo esc_url(MATANGI_DOCUMENTATION); ?>" target="_blank"><?php esc_html_e( 'Documentation', 'matangi' ); ?></a>
						</div>
					</div>
				</div>

				<?php
				/**
				 * matangi_dashboard_after_header hook.
				 *
				 */
				 do_action( 'matangi_dashboard_after_header' );
				 ?>

				<div class="matangi-container">
					<div class="postbox-container clearfix" style="float: none;">
						<div class="grid-container grid-parent">

							<?php
							/**
							 * matangi_dashboard_inside_container hook.
							 *
							 */
							 do_action( 'matangi_dashboard_inside_container' );
							 ?>

							<div class="form-metabox grid-70" style="padding-left: 0;">
								<h2 style="height:0;margin:0;"><!-- admin notices below this element --></h2>
								<form method="post" action="options.php">
									<?php settings_fields( 'matangi-settings-group' ); ?>
									<?php do_settings_sections( 'matangi-settings-group' ); ?>
									<div class="customize-button hide-on-desktop">
										<?php
										printf( '<a id="matangi_customize_button" class="button button-primary" href="%1$s">%2$s</a>',
											esc_url( admin_url( 'customize.php' ) ),
											esc_html__( 'Customize', 'matangi' )
										);
										?>
									</div>

									<?php
									/**
									 * matangi_inside_options_form hook.
									 *
									 */
									 do_action( 'matangi_inside_options_form' );
									 ?>
								</form>

								<?php
								$modules = array(
									'Backgrounds' => array(
											'url' => MATANGI_THEME_URL,
									),
									'Blog' => array(
											'url' => MATANGI_THEME_URL,
									),
									'Colors' => array(
											'url' => MATANGI_THEME_URL,
									),
									'Copyright' => array(
											'url' => MATANGI_THEME_URL,
									),
									'Disable Elements' => array(
											'url' => MATANGI_THEME_URL,
									),
									'Demo Import' => array(
											'url' => MATANGI_THEME_URL,
									),
									'Hooks' => array(
											'url' => MATANGI_THEME_URL,
									),
									'Import / Export' => array(
											'url' => MATANGI_THEME_URL,
									),
									'Menu Plus' => array(
											'url' => MATANGI_THEME_URL,
									),
									'Page Header' => array(
											'url' => MATANGI_THEME_URL,
									),
									'Secondary Nav' => array(
											'url' => MATANGI_THEME_URL,
									),
									'Spacing' => array(
											'url' => MATANGI_THEME_URL,
									),
									'Typography' => array(
											'url' => MATANGI_THEME_URL,
									),
									'Elementor Addon' => array(
											'url' => MATANGI_THEME_URL,
									)
								);

								if ( ! defined( 'MATANGI_PREMIUM_VERSION' ) ) : ?>
									<div class="postbox matangi-metabox">
										<h3 class="hndle"><?php esc_html_e( 'Premium Modules', 'matangi' ); ?></h3>
										<div class="inside" style="margin:0;padding:0;">
											<div class="premium-addons">
												<?php foreach( $modules as $module => $info ) { ?>
												<div class="add-on activated matangi-clear addon-container grid-parent">
													<div class="addon-name column-addon-name" style="">
														<a href="<?php echo esc_url( $info[ 'url' ] ); ?>" target="_blank"><?php echo esc_html( $module ); ?></a>
													</div>
													<div class="addon-action addon-addon-action" style="text-align:right;">
														<a href="<?php echo esc_url( $info[ 'url' ] ); ?>" target="_blank"><?php esc_html_e( 'More info', 'matangi' ); ?></a>
													</div>
												</div>
												<div class="matangi-clear"></div>
												<?php } ?>
											</div>
										</div>
									</div>
								<?php
								endif;

								/**
								 * matangi_options_items hook.
								 *
								 */
								do_action( 'matangi_options_items' );
								?>
							</div>

							<div class="matangi-right-sidebar grid-30" style="padding-right: 0;">
								<div class="customize-button hide-on-mobile">
									<?php
									printf( '<a id="matangi_customize_button" class="button button-primary" href="%1$s">%2$s</a>',
										esc_url( admin_url( 'customize.php' ) ),
										esc_html__( 'Customize', 'matangi' )
									);
									?>
								</div>

								<?php
								/**
								 * matangi_admin_right_panel hook.
								 *
								 */
								 do_action( 'matangi_admin_right_panel' );

								  ?>
                                
                                <div class="wpkoi-doc">
                                	<h3><?php esc_html_e( 'Matangi documentation', 'matangi' ); ?></h3>
                                	<p><?php esc_html_e( 'If You`ve stuck, the documentation may help on WPKoi.com', 'matangi' ); ?></p>
                                    <a href="<?php echo esc_url(MATANGI_DOCUMENTATION); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Matangi documentation', 'matangi' ); ?></a>
                                </div>
                                
                                <div class="wpkoi-social">
                                	<h3><?php esc_html_e( 'WPKoi on Facebook', 'matangi' ); ?></h3>
                                	<p><?php esc_html_e( 'If You want to get useful info about WordPress and the theme, follow WPKoi on Facebook.', 'matangi' ); ?></p>
                                    <a href="<?php echo esc_url(MATANGI_WPKOI_SOCIAL_URL); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Go to Facebook', 'matangi' ); ?></a>
                                </div>
                                
                                <div class="wpkoi-review">
                                	<h3><?php esc_html_e( 'Help with You review', 'matangi' ); ?></h3>
                                	<p><?php esc_html_e( 'If You like Matangi theme, show it to the world with Your review. Your feedback helps a lot.', 'matangi' ); ?></p>
                                    <a href="<?php echo esc_url(MATANGI_WORDPRESS_REVIEW); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Add my review', 'matangi' ); ?></a>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'matangi_admin_errors' ) ) {
	add_action( 'admin_notices', 'matangi_admin_errors' );
	/**
	 * Add our admin notices
	 *
	 */
	function matangi_admin_errors() {
		$screen = get_current_screen();

		if ( 'appearance_page_matangi-options' !== $screen->base ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) {
			 add_settings_error( 'matangi-notices', 'true', esc_html__( 'Settings saved.', 'matangi' ), 'updated' );
		}

		if ( isset( $_GET['status'] ) && 'imported' == $_GET['status'] ) {
			 add_settings_error( 'matangi-notices', 'imported', esc_html__( 'Import successful.', 'matangi' ), 'updated' );
		}

		if ( isset( $_GET['status'] ) && 'reset' == $_GET['status'] ) {
			 add_settings_error( 'matangi-notices', 'reset', esc_html__( 'Settings removed.', 'matangi' ), 'updated' );
		}

		settings_errors( 'matangi-notices' );
	}
}
