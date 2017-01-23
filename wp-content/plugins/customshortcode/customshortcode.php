<?php
/**
 * Plugin Name: Custom ShortCode
 * Plugin URI: http://heochaua.tk
 * Description: Custom ShortCode Require Timber and ACF Plugin
 * Version: 1.0
 * Author: HeoChauA
 * Author URI: http://heochaua.tk
 * License: GPLv2
 */

/* add_shortcode( 'custom_shortcode', 'create_custom_shortcode' );
function create_custom_shortcode($attrs) {
  extract(shortcode_atts (array(
    'per_page' => -1
  ), $attrs));

  ob_start();
    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
} */

$dir = plugin_dir_path( __FILE__ );

/*function app_output_buffer() {
  ob_start();
}
add_action('init', 'app_output_buffer');*/

function array_fill_keystest($target, $value = '') {
  if(is_array($target)) {
    foreach($target as $key => $val) {
      $filledArray[$val] = is_array($value) ? $value[$key] : $value;
    }
  }
  return $filledArray;
}

// Short code for cat
add_shortcode( 'cs_tax', 'cs_create_get_tax' );
function cs_create_get_tax($attrs) {
  extract(shortcode_atts (array(
    'tax_name'  => '',
    'template'  => ''
  ), $attrs));

  ob_start();
    $args = array(
      'parent' => 0,
      'hide_empty' => false
    );

    $context = Timber::get_context();
    $context['categories'] = Timber::get_terms($tax_name, $args);
    $context['template_class'] = $tax_name . '-' . $template;

    Timber::render( array( 'cat-' . $tax_name . '-' . $template . '.twig', 'cat-' . $tax_name . '.twig', 'cs-categories.twig'), $context );

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}

// Subscriber
add_shortcode( 'cs_subscriber', 'create_cs_subscriber' );
function create_cs_subscriber($attrs) {
  extract(shortcode_atts (array(
    'message' => ''
  ), $attrs));

  ob_start();

    $subscribe_email_arr = array();
    $args = array(
      'post_type' => 'user_subscribe',
      'post_status' => 'any',
      'posts_per_page'  => -1
    );
    $subscribe = new WP_Query($args);
    if( $subscribe->have_posts() ) {
      while ( $subscribe->have_posts() ) {
        $subscribe->the_post();
        $post_title = get_the_title();

        $title_float = str_replace(array('@', '.', ' '),"",$post_title);
        $title_convert = hash('adler32', $title_float);
        $subscribe_email_arr[] = $title_convert;
      }
      wp_reset_postdata();
    }

    $context = Timber::get_context();
    $context['message'] = $message;

    if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'cs-submit-subscribe' ) {
      //print_r($_POST);
      if( isset($_POST['email'], $_POST['post_code']) ) {
        $subs_email     = $_POST['email'];
        $subs_postcode  = $_POST['post_code'];
      }

      if ( empty($subs_email) || empty($subs_postcode) ) {
        setcookie("subs_false", 1, time() + 5, '/'); // 86400 = 1 day
        $context['message'] = __('Email or Post Code is not empty!', 'cs_plugin');
      } else {
        $email_float = str_replace(array('@', '.', ' '), "", $subs_email);
        $email_convert = hash('adler32', $email_float);

        if( in_array($email_convert, $subscribe_email_arr) ) {
          setcookie("subs_false", 1, time() + 5, '/'); // 86400 = 1 day
          $context['message_email'] = __('Email ' . $subs_email . ' is existed!', 'cs_plugin');
        } else {
          setcookie("subs_submited", 1, time() + 5, '/'); // 86400 = 1 day

          $new_post = array(
            'post_title'    =>   $subs_email,
            'post_status'   =>   'pending',
            'post_type'     =>   'user_subscribe'
          );

          // SAVE THE POST
          $pid = wp_insert_post($new_post);
          update_post_meta($pid, '_cmb_post_code', $subs_postcode);
        }
      }
    }

    Timber::render( 'subscriber.twig', $context );

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}