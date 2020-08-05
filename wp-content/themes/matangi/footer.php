<?php
/**
 * The template for displaying the footer.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

	</div><!-- #content -->
</div><!-- #page -->

<?php
/**
 * matangi_before_footer hook.
 *
 */
do_action( 'matangi_before_footer' );
?>

<div <?php matangi_footer_class(); ?>>
	<?php
	/**
	 * matangi_before_footer_content hook.
	 *
	 */
	do_action( 'matangi_before_footer_content' );

	/**
	 * matangi_footer hook.
	 *
	 *
	 * @hooked matangi_construct_footer_widgets - 5
	 * @hooked matangi_construct_footer - 10
	 */
	do_action( 'matangi_footer' );

	/**
	 * matangi_after_footer_content hook.
	 *
	 */
	do_action( 'matangi_after_footer_content' );
	?>
</div><!-- .site-footer -->

<?php
/**
 * matangi_after_footer hook.
 *
 */
do_action( 'matangi_after_footer' );

wp_footer();
?>

</body>
</html>
