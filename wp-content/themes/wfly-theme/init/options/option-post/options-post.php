<?php
function be_metaboxes_optionpost( $meta_boxes ) {
  $prefix = '_cmb_'; // Prefix for all fields
  $meta_boxes['page_option'] = array(
    'id' => 'page_option',
    'title' => 'Page Options',
    'pages' => array('user_subscribe'), // post type
    'context' => 'normal',
    'priority' => 'high',
    'show_names' => true, // Show field names on the left
    'fields' => array(
      array(
        'name' => 'Post code',
        'desc' => '',
        'id' => $prefix . 'post_code',
        'type'    => 'text'
      ),
    ),
  );

  return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'be_metaboxes_optionpost' );

function framework_post($name = '') {
  global $post;
  $value = get_post_meta( $post->ID, '_cmb_' . $name, true );
  return $value;
}