<?php
add_action( 'cmb2_admin_init', 'asc_option_metaboxes' );
function asc_option_metaboxes() {
  $prefix = '_cmb2_';

  /**
  * Options for ASC Appointment Post
  */
  $cmb = new_cmb2_box( array(
    'id'            => 'asc_appointment_option',
    'title'         => __( 'Acuity Scheduling Appointment Options', 'cmb2' ),
    'object_types'  => array( 'asc_appointment', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
  ) );

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
    'options' => array(
      'url' => false,
    ),
    'text'    => array(
      'add_upload_file_text' => 'Add Image'
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

  /**
  * Options for ASC User Post
  */
  $cmb = new_cmb2_box( array(
    'id'            => 'asc_user_option',
    'title'         => __( 'Acuity Scheduling User Options', 'cmb2' ),
    'object_types'  => array( 'asc_user', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
  ) );

  $cmb->add_field( array(
    'name' => __('First Name', 'cmb2'),
    'id'   => $prefix . 'asc_first_name',
    'type' => 'text',
  ) );

  $cmb->add_field( array(
    'name' => __('Last Name', 'cmb2'),
    'id'   => $prefix . 'asc_last_name',
    'type' => 'text',
  ) );

  $cmb->add_field( array(
    'name' => __('Email', 'cmb2'),
    'id'   => $prefix . 'asc_email',
    'type' => 'text',
  ) );

  $cmb->add_field( array(
    'name' => __('Password', 'cmb2'),
    'id'   => $prefix . 'asc_pass',
    'type' => 'text',
  ) );
}

function asc_post_meta($name = '') {
  global $post;
  $value = get_post_meta( $post->ID, '_cmb2_' . $name, true );
  return $value;
}
