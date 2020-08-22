<?php
/**
 * The template for displaying the header.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php matangi_body_schema();?> <?php body_class(); ?>>
	<?php
	/**
	 * new WordPress action since version 5.2
	 */
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	
	/**
	 * matangi_before_header hook.
	 *
	 *
	 * @hooked matangi_do_skip_to_content_link - 2
	 * @hooked matangi_top_bar - 5
	 * @hooked matangi_add_navigation_before_header - 5
	 */
	do_action( 'matangi_before_header' );

	/**
	 * matangi_header hook.
	 *
	 *
	 * @hooked matangi_construct_header - 10
	 */
	do_action( 'matangi_header' );

	/**
	 * matangi_after_header hook.
	 *
	 *
	 * @hooked matangi_featured_page_header - 10
	 */
	do_action( 'matangi_after_header' );
	?>

	<div id="page" class="hfeed site grid-container container grid-parent">
		<div id="content" class="site-content">
			<?php
			/**
			 * matangi_inside_container hook.
			 *
			 */
			do_action( 'matangi_inside_container' );
