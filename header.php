<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */

<<<<<<< HEAD
?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
=======
?>
<!doctype html>
<html <?php language_attributes(); ?>>
>>>>>>> 28f0a5b4e56a5dc3cba94749a208eb478fe224d4
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<<<<<<< HEAD
<div id="outer-wrap">
<div id="inner-wrap">
<header class="header">
	<div id="top" class="l-constrained">
		<!-- <div class="l-logo">
			<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img class="logo__image" src="<?php echo get_template_directory_uri(); ?>/assets/dist/img/logo@2x.png" width="197" height="48" alt="<?php echo esc_attr( bloginfo( 'name' ) ); ?>">
			</a>
		</div> -->
		<a id="nav-open-btn" class="menu-toggle js-menu-toggle" href="#offcanvas"><span class="menu-toggle__line"></span><span class="visuallyhidden">Menu</span></a>
		<div class="offcanvas-wrap l-header-content">
			<div id="offcanvas" class="offcanvas">
				<a id="nav-close-btn" class="menu-toggle menu-toggle--close" href="#top"><span class="menu-toggle__line"></span><span class="visuallyhidden">Close</span></a>
				<nav class="l-header-main" role="navigation">
					<ul class="main">
						<li class="main__item"><a class="main__link" href="#">Link Item</a></li>
						<li class="main__item"><a class="main__link" href="#">Link Item</a></li>
						<li class="main__item"><a class="main__link" href="#">Link Item</a></li>
						<li class="main__item"><a class="main__link" href="#">Link Item</a></li>
						<li class="main__item"><a class="main__link" href="#">Link Item</a></li>
						<li class="main__item"><a class="main__link" href="#">Link Item</a></li>
					</ul>
				</nav>

				<?php // wp_nav_menu( array(
					  //  'theme_location' => 'primary',
					  //  'container'      => '',
					  //  'menu_class'     => 'main',
					  //  'walker'         => new EV_Sub_Level_Walker
					//)
				//); ?>

			</div>
		</div>
	</div>
</header>
<div class="wrap">
	<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
	<p class="site-description"><?php bloginfo( 'description' ); ?></p>
</div>
=======
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', '_s' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$_s_description = get_bloginfo( 'description', 'display' );
			if ( $_s_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $_s_description; /* WPCS: xss ok. */ ?></p>
			<?php endif; ?>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', '_s' ); ?></button>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
			) );
			?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
>>>>>>> 28f0a5b4e56a5dc3cba94749a208eb478fe224d4
