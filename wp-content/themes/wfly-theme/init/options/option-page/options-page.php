<?php
function be_sample_metaboxes( $meta_boxes ) {
  $prefix = '_cmb_'; // Prefix for all fields

  $cmb = new_cmb2_box( array(
    'id'            => 'page_option',
    'title'         => __( 'Page Options', 'cmb2' ),
    'object_types'  => array( 'page', ), // Post type or any post type use: ct_list_posttype()
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
  ) );

  // Disable title
  $cmb->add_field( array(
    'name'       => __( 'Disable title', 'cmb2' ),
    'desc'       => __( 'Check it if you want disable this page title', 'cmb2' ),
    'id'         => $prefix . 'title',
    'type'       => 'checkbox'
  ) );

  // Layout Option
  $cmb->add_field( array(
    'name'              => __('Layout page option', 'cmb2'),
    'desc'              => __('Check to setting this page layout', 'cmb2'),
    'id'                => $prefix . 'layout_page',
    'type'              => 'radio',
    'show_option_none'  => false,
    'options'           => array(
      'full'            => __( 'Page full layout', 'cmb2' ),
      'container'       => __( 'Page container layout', 'cmb2' ),
    ),
    'default'           => 'container'
  ) );
}
add_action( 'cmb2_admin_init', 'be_sample_metaboxes' );

function framework_page($name = '') {
  global $post;
  $value = get_post_meta( $post->ID, '_cmb_' . $name, true );
  return $value;
}
