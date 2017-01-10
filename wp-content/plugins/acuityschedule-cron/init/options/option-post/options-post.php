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

  $cmb->add_field( array(
    'name'    => __('Appointment Image', 'asc'),
    'desc'    => 'Upload an image or enter an URL.',
    'id'      => $prefix . 'asc_app_image',
    'type'    => 'file',
    // Optional:
    'options' => array(
      'url' => false, // Hide the text input for the url
    ),
    'text'    => array(
      'add_upload_file_text' => 'Add Image' // Change upload button text. Default: "Add or Upload File"
    ),
  ) );

  $cmb->add_field( array(
    'name' => __('Appointment Price', 'cmb2'),
    'id'   => $prefix . 'asc_app_price',
    'type' => 'text',
  ) );

  $cmb->add_field( array(
    'name' => __('Appointment Duration', 'cmb2'),
    'id'   => $prefix . 'asc_app_duration',
    'type' => 'text',
  ) );

  $cmb->add_field( array(
    'name' => __('Appointment Also Block Off Before', 'cmb2'),
    'id'   => $prefix . 'asc_app_blockoffb',
    'type' => 'text_small',
  ) );

  $cmb->add_field( array(
    'name' => __('Appointment Also Block Off After', 'cmb2'),
    'id'   => $prefix . 'asc_app_blockoffa',
    'type' => 'text_small',
  ) );

  $cmb->add_field( array(
    'name' => __('Appointment Description', 'cmb2'),
    'id'   => $prefix . 'asc_app_description',
    'type' => 'textarea',
  ) );

  $cmb->add_field( array(
    'name' => __('Appointment Calendar ID', 'cmb2'),
    'id'   => $prefix . 'asc_app_calendar',
    'type' => 'text',
  ) );
}

function asc_post_meta($name = '') {
  global $post;
  $value = get_post_meta( $post->ID, '_cmb2_' . $name, true );
  return $value;
}
