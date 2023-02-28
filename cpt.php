<?php

add_action('init','create_rwshow_custom_post_type');

function create_rwshow_custom_post_type() {

    $labels = array(
        'name' => _x( 'RW-Shows', 'Post Type General Name', 'RW-Show' ),
        'singular_name' => _x( 'RW-Show', 'Post Type Singular Name', 'RW-Show' ),
        'menu_name' => _x( 'RW-Show', 'Admin Menu text', 'RW-Show' ),
        'name_admin_bar' => _x( 'RW-Show', 'Add New on Toolbar', 'RW-Show' ),
        'attributes' => __( 'Attributes of RW-Show', 'RW-Show' ),
        'parent_item_colon' => __( 'Genitori RW-Show:', 'RW-Show' ),
        'all_items' => __( 'View all shows', 'RW-Show' ),
        'add_new_item' => __( 'Add a new show', 'RW-Show' ),
        'add_new' => __( 'Add a new show', 'RW-Show' ),
        'new_item' => __( 'RW-Show redigere', 'RW-Show' ),
        'edit_item' => __( 'Edit RW-Show', 'RW-Show' ),
        'update_item' => __( 'Update RW-Show', 'RW-Show' ),
        'search_items' => __( 'Search for a show', 'RW-Show' ),
        'not_found' => __( 'Show not found.', 'RW-Show' ),
        'not_found_in_trash' => __( 'The RW-Show was not found in trash.', 'RW-Show' ),
        'featured_image' => __( 'Featured Image', 'RW-Show' ),
        'set_featured_image' => __( 'Set a featured image', 'RW-Show' ),
        'remove_featured_image' => __( 'Remove the featured image', 'RW-Show' ),
        'use_featured_image' => __( 'Use featured image', 'RW-Show' ),
        'insert_into_item' => __( 'Insert into RW-Show', 'RW-Show' ),
        'uploaded_to_this_item' => __( 'Uploaded into this RW-Show', 'RW-Show' ),
        'items_list' => __( 'List of RW-Shows', 'RW-Show' ),
        'items_list_navigation' => __( 'Navigation list of RW-Show', 'RW-Show' ),
        'filter_items_list' => __( 'Filter list of RW-Show', 'RW-Show' ),
    );
    $args = array(
        'label' => __( 'RW-Show', 'RW-Show' ),
        'description' => __( 'RW-Show', 'RW-Show' ),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => array( 'title', 'editor', 'thumbnail', 'revisions'),
        'taxonomies' => array(),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => false,
        'hierarchical' => false,
        'exclude_from_search' => false,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    );
    register_post_type( CUSTOM_POST_TYPE__RWSHOW, $args );

}
