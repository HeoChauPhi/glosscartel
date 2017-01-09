<?php
// load the theme's framework

function asc_get_page_id($pageSlug) {
  $page = get_page_by_path($pageSlug);
  if ($page) {
    return $page->ID;
  } else {
    return null;
  }
}

// PostCode Page
function asc_add_form_page() {

  global $wpdb;

  $page_asc_register_title = 'Appointment Scheduling Registor';
  $page_asc_register_name  = 'appointment-scheduling-registor';

  $page_asc_signin_title   = 'Appointment Scheduling Signin';
  $page_asc_signin_name    = 'appointment-scheduling-signin';

  $page_asc_choose_title   = 'Appointment Scheduling Client Choose';
  $page_asc_choose_name    = 'appointment-scheduling-client-choose';

  $page_asc_confirm_title  = 'Appointment Scheduling Confirm';
  $page_asc_confirm_name   = 'appointment-scheduling-confirm';

  $page_asc_success_title  = 'Appointment Scheduling Success';
  $page_asc_success_name   = 'appointment-scheduling-success';

  $page_asc_forgot_title   = 'Appointment Scheduling Forgot Password';
  $page_asc_forgot_name    = 'appointment-scheduling-forgot-password';

  // the menu entry...
  delete_option("asc_plugin_page_title");
  add_option("asc_plugin_page_title", array(
    $page_asc_register_title,
    $page_asc_register_name,
    $page_asc_choose_title,
    $page_asc_confirm_title,
    $page_asc_success_title,
    $page_asc_forgot_title), '', 'yes');
  // the slug...
  delete_option("asc_plugin_page_name");
  add_option("asc_plugin_page_name", array(
    $page_asc_register_name,
    $page_asc_signin_name,
    $page_asc_choose_name,
    $page_asc_confirm_name,
    $page_asc_success_name,
    $page_asc_forgot_name), '', 'yes');
  // the id...
  delete_option("asc_plugin_page_id");
  add_option("asc_plugin_page_id", '0', '', 'yes');

  $page_asc_register = get_page_by_title( $page_asc_register_title );
  $page_asc_signin   = get_page_by_title( $page_asc_signin_title );
  $page_asc_choose   = get_page_by_title( $page_asc_choose_title );
  $page_asc_confirm  = get_page_by_title( $page_asc_confirm_title );
  $page_asc_success  = get_page_by_title( $page_asc_success_title );
  $page_asc_forgot  = get_page_by_title( $page_asc_forgot_title );

  // Page Register
  if ( ! $page_asc_register ) {

    // Create post object
    $_p = array();
    $_p['post_title'] = $page_asc_register_title;
    $_p['post_slug'] = $page_asc_register_name;
    $_p['post_content'] = '[asc_signup]';
    $_p['post_status'] = 'publish';
    $_p['post_type'] = 'page';
    $_p['comment_status'] = 'closed';
    $_p['ping_status'] = 'closed';
    $_p['post_category'] = array(1); // the default 'Uncatrgorised'

    // Insert the post into the database
    $page_asc_register_id = wp_insert_post( $_p );

  }
  else {
    // the plugin may have been previously active and the page may just be trashed...

    $page_asc_register_id = $page_asc_register->ID;

    //make sure the page is not trashed...
    $page_asc_register->post_status = 'publish';
    $page_asc_register_id = wp_update_post( $page_asc_register );

  }

  // Page Signin
  if ( ! $page_asc_signin ) {

    // Create post object
    $_p = array();
    $_p['post_title'] = $page_asc_signin_title;
    $_p['post_slug'] = $page_asc_signin_name;
    $_p['post_content'] = '[asc_signin]';
    $_p['post_status'] = 'publish';
    $_p['post_type'] = 'page';
    $_p['comment_status'] = 'closed';
    $_p['ping_status'] = 'closed';
    $_p['post_category'] = array(1); // the default 'Uncatrgorised'

    // Insert the post into the database
    $page_asc_signin_id = wp_insert_post( $_p );

  }
  else {
    // the plugin may have been previously active and the page may just be trashed...

    $page_asc_signin_id = $page_asc_signin->ID;

    //make sure the page is not trashed...
    $page_asc_signin->post_status = 'publish';
    $page_asc_signin_id = wp_update_post( $page_asc_signin );

  }

  // Page Client Choose
  if ( ! $page_asc_choose ) {

    // Create post object
    $_p = array();
    $_p['post_title'] = $page_asc_choose_title;
    $_p['post_slug'] = $page_asc_choose_name;
    $_p['post_content'] = '[asc_client_choose]';
    $_p['post_status'] = 'publish';
    $_p['post_type'] = 'page';
    $_p['comment_status'] = 'closed';
    $_p['ping_status'] = 'closed';
    $_p['post_category'] = array(1); // the default 'Uncatrgorised'

    // Insert the post into the database
    $page_asc_choose_id = wp_insert_post( $_p );

  }
  else {
    // the plugin may have been previously active and the page may just be trashed...

    $page_asc_choose_id = $page_asc_choose->ID;

    //make sure the page is not trashed...
    $page_asc_choose->post_status = 'publish';
    $page_asc_choose_id = wp_update_post( $page_asc_choose );

  }

  // Page Confirm
  if ( ! $page_asc_confirm ) {

    // Create post object
    $_p = array();
    $_p['post_title'] = $page_asc_confirm_title;
    $_p['post_slug'] = $page_asc_confirm_name;
    $_p['post_content'] = '[asc_confirm]';
    $_p['post_status'] = 'publish';
    $_p['post_type'] = 'page';
    $_p['comment_status'] = 'closed';
    $_p['ping_status'] = 'closed';
    $_p['post_category'] = array(1); // the default 'Uncatrgorised'

    // Insert the post into the database
    $page_asc_confirm_id = wp_insert_post( $_p );

  }
  else {
    // the plugin may have been previously active and the page may just be trashed...

    $page_asc_confirm_id = $page_asc_confirm->ID;

    //make sure the page is not trashed...
    $page_asc_confirm->post_status = 'publish';
    $page_asc_confirm_id = wp_update_post( $page_asc_confirm );

  }

  // Page Success
  if ( ! $page_asc_success ) {

    // Create post object
    $_p = array();
    $_p['post_title'] = $page_asc_success_title;
    $_p['post_slug'] = $page_asc_success_name;
    $_p['post_content'] = '[asc_success]';
    $_p['post_status'] = 'publish';
    $_p['post_type'] = 'page';
    $_p['comment_status'] = 'closed';
    $_p['ping_status'] = 'closed';
    $_p['post_category'] = array(1); // the default 'Uncatrgorised'

    // Insert the post into the database
    $page_asc_success_id = wp_insert_post( $_p );

  }
  else {
    // the plugin may have been previously active and the page may just be trashed...

    $page_asc_success_id = $page_asc_success->ID;

    //make sure the page is not trashed...
    $page_asc_success->post_status = 'publish';
    $page_asc_success_id = wp_update_post( $page_asc_success );

  }

  // Page Forgot Password
  if ( ! $page_asc_forgot ) {

    // Create post object
    $_p = array();
    $_p['post_title'] = $page_asc_forgot_title;
    $_p['post_slug'] = $page_asc_forgot_name;
    $_p['post_content'] = '[asc_forgot_password]';
    $_p['post_status'] = 'publish';
    $_p['post_type'] = 'page';
    $_p['comment_status'] = 'closed';
    $_p['ping_status'] = 'closed';
    $_p['post_category'] = array(1); // the default 'Uncatrgorised'

    // Insert the post into the database
    $page_asc_forgot_id = wp_insert_post( $_p );

  }
  else {
    // the plugin may have been previously active and the page may just be trashed...

    $page_asc_forgot_id = $page_asc_forgot->ID;

    //make sure the page is not trashed...
    $page_asc_forgot->post_status = 'publish';
    $page_asc_forgot_id = wp_update_post( $page_asc_forgot );

  }

  delete_option( 'asc_plugin_page_id' );
  add_option( 'asc_plugin_page_id', array(
    $page_asc_register_id,
    $page_asc_signin_id,
    $page_asc_choose_id,
    $page_asc_confirm_id,
    $page_asc_success_id,
    $page_asc_forgot_id) );

}

function asc_remove_form_page() {
  global $wpdb;

  $page_asc_register_id = asc_get_page_id('appointment-scheduling-registor');
  $page_asc_signin_id = asc_get_page_id('appointment-scheduling-signin');
  $page_asc_choose_id = asc_get_page_id('appointment-scheduling-client-choose');
  $page_asc_confirm_id = asc_get_page_id('appointment-scheduling-confirm');
  $page_asc_success_id = asc_get_page_id('appointment-scheduling-success');
  $page_asc_forgot_id = asc_get_page_id('appointment-scheduling-forgot-password');

  wp_delete_post($page_asc_register_id);
  wp_delete_post($page_asc_signin_id);
  wp_delete_post($page_asc_choose_id);
  wp_delete_post($page_asc_confirm_id);
  wp_delete_post($page_asc_success_id);
  wp_delete_post($page_asc_forgot_id);
}
