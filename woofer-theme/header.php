<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package woofer
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'woofer' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-branding">
		</div><!-- .site-branding -->

		<style>
		#primary-menu li{
			color:blue !important;
			padding:20px;
		}

		#primary-menu a{
			color:blue !important;
		}
		</style>
		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'woofer' ); ?></button>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
			) );
			?>
			<?php if(is_user_logged_in()):?>
				<ul aria-expanded="false" id="primary-menu" class=" nav-menu" style="float:right;margin-right:50px;">
					<li class="page_item">
						<a href="<?php echo wp_logout_url(site_url()); ?>">Logout</a>
					</li>
				</ul>
			<?php else: ?>
				<ul aria-expanded="false" id="primary-menu" class=" nav-menu" style="float:right;margin-right:50px;">
					<li class="page_item">
						<a href="https://orphic.ca/soen341/login/">Login</a>
					</li>
					<li class="page_item">
						<a href="https://orphic.ca/soen341/new-user/">Create account</a>
					</li>
				</ul>
			<?php endif; ?>
		</nav><!-- #site-navigation -->
		<?php if(is_user_logged_in()):?>
			<div style="margin-left:50px;font-weight:800;">
				<?php
				$current_user = wp_get_current_user();
				?>
				Currently logged in as : <?php echo $current_user->display_name; ?>
			</div>
		<?php endif; ?>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
