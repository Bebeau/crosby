<?php

// create custom post type for recipes
add_action( 'init', 'init_artistManagement' );
function init_artistManagement() {

    $artist_type_labels = array(
        'name' => _x('Artists', 'post type general name'),
        'singular_name' => _x('Artist', 'post type singular name'),
        'add_new' => _x('Add New Artist', 'video'),
        'add_new_item' => __('Add New Artist'),
        'edit_item' => __('Edit Artist'),
        'new_item' => __('Add New Artist'),
        'all_items' => __('View Artists'),
        'view_item' => __('View Artist'),
        'search_items' => __('Search Artists'),
        'not_found' =>  __('No Artists found'),
        'not_found_in_trash' => __('No Artists found in Trash'), 
        'parent_item_colon' => '',
        'menu_name' => 'Artists'
    );
    $artist_type_args = array(
        'labels' => $artist_type_labels,
        'public' => true,
        'publicly_queryable' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'artists' ),
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true, 
        'hierarchical' => true,
        'map_meta_cap' => true,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array('category')
    );
    register_post_type('artists', $artist_type_args);
}

?>