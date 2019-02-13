<?php
function register_woof() {
  $labels = array(
    'name'               => _x( 'Woof', 'post type general name' ),
    'singular_name'      => _x( 'Woof', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'Woof' ),
    'add_new_item'       => __( 'Add New Woof' ),
    'edit_item'          => __( 'Edit Woof' ),
    'new_item'           => __( 'New Woof' ),
    'all_items'          => __( 'All Woofs' ),
    'view_item'          => __( 'View Woof' ),
    'search_items'       => __( 'Search Woofs' ),
    'not_found'          => __( 'No Woofs found' ),
    'not_found_in_trash' => __( 'No Woofs found in the Trash' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Woofs'
);

$args = array(
    'labels'        => $labels,
    'description'   => 'Events',
    'public'        => true,
    'show_ui'        => true,
    'capability_type'  => 'post',
    'menu_position' => 5,
    'supports'      => array( 'title' , 'editor', 'page-attributes'),
    'has_archive'   => true,
);

register_post_type( 'woof', $args );
}
add_action( 'init', 'register_woof' );
