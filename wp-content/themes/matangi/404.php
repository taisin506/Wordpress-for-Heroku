<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

	<div id="primary" <?php matangi_content_class(); ?>>
		<main id="main" <?php matangi_main_class(); ?>>
			<?php
			/**
			 * matangi_before_main_content hook.
			 *
			 */
			do_action( 'matangi_before_main_content' );
			?>

			<div class="inside-article">

				<?php
				/**
				 * matangi_before_content hook.
				 *
				 *
				 * @hooked matangi_featured_page_header_inside_single - 10
				 */
				do_action( 'matangi_before_content' );
				?>

				<header class="entry-header">
					<h1 class="entry-title" itemprop="headline"><?php echo esc_html( apply_filters( 'matangi_404_title', __( 'Oops! That page can&rsquo;t be found.', 'matangi' ) ) ); // WPCS: XSS OK. ?></h1>
				</header><!-- .entry-header -->

				<?php
				/**
				 * matangi_after_entry_header hook.
				 *
				 *
				 * @hooked matangi_post_image - 10
				 */
				do_action( 'matangi_after_entry_header' );
				?>

				<div class="entry-content" itemprop="text">
					<?php
					echo '<p>' . esc_html( apply_filters( 'matangi_404_text', __( 'It looks like nothing was found at this location. Maybe try searching?', 'matangi' ) ) ) . '</p>'; // WPCS: XSS OK.

					get_search_form();
					?>
				</div><!-- .entry-content -->

				<?php
				/**
				 * matangi_after_content hook.
				 *
				 */
				do_action( 'matangi_after_content' );
				?>

			</div><!-- .inside-article -->

			<?php
			/**
			 * matangi_after_main_content hook.
			 *
			 */
			do_action( 'matangi_after_main_content' );
			?>
		</main><!-- #main -->
	</div><!-- #primary -->

	<?php
	/**
	 * matangi_after_primary_content_area hook.
	 *
	 */
	 do_action( 'matangi_after_primary_content_area' );

	 matangi_construct_sidebars();

get_footer();
