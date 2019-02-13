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


// Add user role (woofers)
add_role('woofer', 'Woofer', array(
    'read' => true,
    'edit_posts' => true,
    'delete_posts' => true,
		'delete_published_posts' => true,
		'edit_published_posts' => true,
		'publish_posts' => true,
		'read' => true,
		'upload_files' => true
));


// Remove admin bar
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}

/////////////////////////////////////////////////////////
// User registration from frontend
/////////////////////////////////////////////////////////

add_action('template_redirect', 'register_a_user');
function register_a_user(){
    $user_login = esc_attr($_POST['user']);
    $user_pass = esc_attr($_POST['pass']);
    require_once(ABSPATH.WPINC.'/registration.php');

    $sanitized_user_login = sanitize_user($user_login);
    $sanitized_user_pass = sanitize_user($user_pass);
 
    $user_id = wp_create_user($sanitized_user_login, $sanitized_user_pass, $user_email);

      update_user_option($user_id, 'default_password_nag', true, true);
      wp_new_user_notification($user_id, $sanitized_user_pass);
     
   
	// In backend, there is a checkbox that needs to be ticked which sets 
	// the role of new users to woof by default.
}

/////////////////////////////////////////////////////////
// Automatic log in of new users
/////////////////////////////////////////////////////////

function auto_login_new_user( $user_id ) {
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        wp_redirect( 'localhost:8888/woofer/login.php' );
    }
add_action( 'user_register', 'auto_login_new_user' );

/////////////////////////////////////////////////////////
// Post from front end
/////////////////////////////////////////////////////////


if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == "woof") {

    $title     = 'this is a woof';
    $post_type = 'woof';
    $front_post = array(
    'post_title'    => $title,
		'post_content' => @$_POST["text"],
    'post_status'   => 'publish',
    'post_type'     => $post_type
    );

		// insert post into DB
    $post_id = wp_insert_post($front_post);
}

