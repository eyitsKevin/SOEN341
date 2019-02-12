<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Woofer
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function woofer_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'woofer_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function woofer_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'woofer_pingback_header' );

// Suppress wp empty email error php
add_action('user_profile_update_errors', 'remove_user_profiles_update_errors', 10, 3 );
function remove_user_profiles_update_errors($errors, $update, $user) {
    $errors->remove('empty_email');
}

// Remove javascript and visual side of the error, for all user profile hooks just to be sure
add_action('user_new_form', 'modify_user_form', 10, 1);
add_action('show_user_profile', 'modify_user_form', 10, 1);
add_action('edit_user_profile', 'modify_user_form', 10, 1);
function modify_user_form($form_type) {
    ?>
    <script type="text/javascript">
        jQuery('#email').closest('tr').removeClass('form-required').find('.description').remove();
        // Uncheck send new user email option since there wont be an email
        <?php if (isset($form_type) && $form_type === 'add-new-user') : ?>
            jQuery('#send_user_notification').removeAttr('checked');
        <?php endif; ?>
    </script>
    <?php
}
/////////////////////////////////////////////////////////
// Remove unneeded roles
/////////////////////////////////////////////////////////

remove_role( 'editor');
remove_role( 'contributor');
remove_role( 'subscriber');
remove_role( 'author');