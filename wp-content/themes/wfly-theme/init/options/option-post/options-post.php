<?php
function be_metaboxes_optionpost( $meta_boxes ) {
  $prefix = '_cmb_'; // Prefix for all fields

  $cmb = new_cmb2_box( array(
    'id'            => 'post_option',
    'title'         => __( 'Post Options', 'cmb2' ),
    'object_types'  => array( 'user_subscribe', ), // Post type or any post type use: ct_list_posttype()
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
  ) );

  // Post code
  $cmb->add_field( array(
    'name'       => __( 'Post code', 'cmb2' ),
    'desc'       => __( '', 'cmb2' ),
    'id'         => $prefix . 'post_code',
    'type'       => 'text'
  ) );
}
add_filter( 'cmb2_admin_init', 'be_metaboxes_optionpost' );

function framework_post($name = '') {
  global $post;
  $value = get_post_meta( $post->ID, '_cmb_' . $name, true );
  return $value;
}
