<?php
add_action( 'cmb2_admin_init', 'asc_option_metaboxes' );
function asc_option_metaboxes() {

  $prefix = '_cmb2_';

  $cmb = new_cmb2_box( array(
    'id'            => 'asc_appointment_option',
    'title'         => __( 'Acuity Scheduling Appointment Options', 'cmb2' ),
    'object_types'  => array( 'asc_appointment', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
  ) );

  // Field First Name
  $cmb->add_field( array(
    'name' => __('Appointment ID', 'cmb2'),
    'id'   => $prefix . 'asc_app_id',
    'type' => 'text',
  ) );
}

function asc_post_meta($name = '') {
  global $post;
  $value = get_post_meta( $post->ID, '_cmb2_' . $name, true );
  return $value;
}
