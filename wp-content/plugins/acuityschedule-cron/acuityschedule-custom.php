<?php
/*
Plugin Name: Appointment Scheduling Cron (ASC)
Plugin URI: http://AcuityScheduling.com
Description:
Version: 1.0.0
Author: Acuity Scheduling
Author URI: http://AcuityScheduling.com
*/

$dir = plugin_dir_path( __FILE__ );
//require_once dirname( __FILE__ ) . '/init/acuityschedule/AcuitySchedulingOAuth.php';
include_once('init/asc.admin.php');
include_once('init/functions.php');
include_once('init/options/option.php');
include_once('init/shortcodes.php');
include_once('init/plugin-init.php');

// Admin settings.
if(is_admin()) {
  $settings = new ascSettingsPage();
}

/* Runs when plugin is activated */
register_activation_hook(__FILE__, 'asc_plugin_activate');
//register_activation_hook(__FILE__, 'asc_add_form_page');

/* Runs on plugin deactivation*/
register_deactivation_hook(__FILE__, 'asc_plugin_deactivate');
//register_deactivation_hook(__FILE__, 'asc_remove_form_page');

// Post Type Appointment
function asc_create_post_types() {
  register_post_type( 'asc_appointment',
    array(
      'labels' => array(
        'name' => __( 'Acuity Scheduling Appointment', 'asc' ),
        'singular_name' => __( 'Acuity Scheduling Appointment', 'asc' )
      ),
      'supports' => array(
        'title'
      ),
      'public' => true,
      'has_archive' => true,
    )
  );
}
add_action( 'init', 'asc_create_post_types' );

function asc_create_custom_taxonomy() {
  $labels_product = array(
    'name' => 'Service',
    'singular' => 'Service',
    'menu_name' => 'Service'
  );
  $args_product = array(
    'labels'                     => $labels_product,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
  );
  register_taxonomy('service_product', array('asc_appointment'), $args_product);
}
add_action( 'init', 'asc_create_custom_taxonomy', 0 );

// ASC Libs
add_action('init', 'asc_plugin_scripts');
function asc_plugin_scripts() {
  wp_register_script('asc-libs-jqueryui', plugin_dir_url( __FILE__ ) . 'dist/js/libs/jquery-ui.js', array('jquery'), FALSE, '1.12.1', TRUE);
  wp_enqueue_script('asc-libs-jqueryui'); // Enqueue it!

  wp_register_script('asc-libs-cookie', plugin_dir_url( __FILE__ ) . 'dist/js/libs/js.cookie.js', array('jquery'), FALSE, '2.1.3', TRUE);
  wp_enqueue_script('asc-libs-cookie'); // Enqueue it!

  wp_register_script('asc-script', plugin_dir_url( __FILE__ ) . 'dist/js/scripts.js', array('jquery'), FALSE, '1.0.0', TRUE);
  wp_localize_script( 'asc-script', 'ascAjax', array( 'ajaxurl' => admin_url('admin-ajax.php' )));
  wp_enqueue_script('asc-script'); // Enqueue it!
}

add_action('wp_enqueue_scripts', 'asc_styles');
function asc_styles() {
  wp_register_style('jqueryui-css', plugin_dir_url( __FILE__ ). 'dist/css/jquery-ui.css', array(), '1.12.1', 'all');
  wp_enqueue_style('jqueryui-css');
}
