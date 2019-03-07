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
        wp_redirect( 'localhost:8888/woofer/login.php');
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

/////////////////////////////////////////////////////////
// Register woof post type
/////////////////////////////////////////////////////////

require get_template_directory() . '/inc/woof-post-type.php';



/////////////////////////////////////////////////////////
// Woof custom login form
/////////////////////////////////////////////////////////


function woof_custom_login( $args = array() ) {
    $defaults = array(
        'echo' => true,
        'redirect' => 'localhost:8888/woofer/login.php',
        'form_id' => 'loginform',
        'label_username' => __( 'Username or Email Address' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in' => __( 'Log In' ),
        'id_username' => 'user_login',
        'id_password' => 'user_pass',
        'id_remember' => 'rememberme',
        'id_submit' => 'wp-submit',
        'remember' => true,
        'value_username' => '',
        'value_remember' => false,
    );
    $args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );
    $login_form_top = apply_filters( 'login_form_top', '', $args );
    $login_form_middle = apply_filters( 'login_form_middle', '', $args );
    $login_form_bottom = apply_filters( 'login_form_bottom', '', $args );

    $form = '
        <form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . esc_url( site_url( 'wp-login.php', 'login_post' ) ) . '" method="post">
            ' . $login_form_top . '
            <p class="login-username">
                <label for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '</label>
                <input type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="input" value="' . esc_attr( $args['value_username'] ) . '" size="20" />
            </p>
            <p class="login-password">
                <label for="' . esc_attr( $args['id_password'] ) . '">' . esc_html( $args['label_password'] ) . '</label>
                <input type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="input" value="" size="20" />
            </p>
            ' . $login_form_middle . '
            ' . ( $args['remember'] ? '<p class="login-remember"><label><input name="rememberme" type="checkbox" id="' . esc_attr( $args['id_remember'] ) . '" value="forever"' . ( $args['value_remember'] ? ' checked="checked"' : '' ) . ' /> ' . esc_html( $args['label_remember'] ) . '</label></p>' : '' )
						 . '
            <p class="login-submit">
                <input type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="button button-primary" value="' . esc_attr( $args['label_log_in'] ) . '" />
                <input type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />
            </p>
            ' . $login_form_bottom . '
        </form>';

    if ( $args['echo'] )
        echo $form;
    else
        return $form;
}


/////////////////////////////////////////////////////////
// Redirect to our login form on failure
/////////////////////////////////////////////////////////


/*Login Error Handle*/
add_action( 'wp_login_failed', 'woof_user_login_failed' ); // hook failed login

function woof_user_login_failed( $user ) {
    // check what page the login attempt is coming from
    $referrer = $_SERVER['HTTP_REFERER'];

    // check that were not on the default login page
    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $user!=null ) {
        // make sure we don't already have a failed login attempt
        if ( !strstr($referrer, '?login=failed' )) {
            // Redirect to the login page and append a querystring of login failed
            wp_redirect( $referrer . '?login=failed');
        } else {
            wp_redirect( $referrer );
        }
        exit;
    }
}

/*Login Empty Fields Error handling*/
add_action( 'authenticate', 'pu_blank_login');

function pu_blank_login( $user ){
    // check what page the login attempt is coming from
    $referrer = $_SERVER['HTTP_REFERER'];

    $error = false;

    if($_POST['log'] == '' || $_POST['pwd'] == '')
    {
        $error = true;
    }

    // check that were not on the default login page
    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $error ) {

        // make sure we don't already have a failed login attempt
        if ( !strstr($referrer, '?login=failed') ) {
            // Redirect to the login page and append a querystring of login failed
            wp_redirect( $referrer . '?login=failed' );
        } else {
            wp_redirect( $referrer );
        }
    exit;

    }
}

/////////////////////////////////////////////////////////
// Follow/unfollow another user, adds/remove user ID
// to following meta.
// AJAX calls
/////////////////////////////////////////////////////////

add_action('wp_ajax_woof_ajax_follow', 'woof_ajax_follow');
add_action('wp_ajax_nopriv_woof_ajax_follow', 'woof_ajax_follow');

function woof_ajax_follow(){

	$postuserid = $_GET["auth"];
	$current_user = wp_get_current_user();
	$currID = $current_user->ID;

	$following = get_user_meta($currID,'following',true);
	if(!is_array($following)) $following = array();
	array_push($following,$postuserid);
	update_user_meta($currID,'following',$following);

}

add_action('wp_ajax_woof_ajax_unfollow', 'woof_ajax_unfollow');
add_action('wp_ajax_nopriv_woof_ajax_unfollow', 'woof_ajax_unfollow');

function woof_ajax_unfollow(){

	$postuserid = $_GET["auth"];
	$current_user = wp_get_current_user();
	$currID = $current_user->ID;

	$following = get_user_meta($currID,'following',true);
	$key = array_search($postuserid,$following);
	unset($following[$key]);
	delete_user_meta($currID,'following');
	update_user_meta($currID,'following',$following);

}

/////////////////////////////////////////////////////////
// Like a post, add to post meta
// AJAX calls
/////////////////////////////////////////////////////////


add_action('wp_ajax_woof_ajax_like', 'woof_ajax_like');
add_action('wp_ajax_nopriv_woof_ajax_like', 'woof_ajax_like');

function woof_ajax_like(){

	$postid = $_GET[""postid""];
	$current_user = wp_get_current_user();
	$currID = $current_user->ID;

	$liked = get_user_meta($currID,'likedposts',true);
	if(!is_array($liked)) $liked = array();
	array_push($liked,$postid);
	update_user_meta($currID,'likedposts',$liked);

}

add_action('wp_ajax_woof_ajax_unlike', 'woof_ajax_unlike');
add_action('wp_ajax_nopriv_woof_ajax_unlike', 'woof_ajax_unlike');

function woof_ajax_unlike(){

	$postid = $_GET[""postid""];
	$current_user = wp_get_current_user();
	$currID = $current_user->ID;

	$liked = get_user_meta($currID,'likedposts',true);
	$key = array_search($postid,$liked);
	unset($liked[$key]);
	delete_user_meta($currID,'likedposts');
	update_user_meta($currID,'likedposts',$liked);

}


