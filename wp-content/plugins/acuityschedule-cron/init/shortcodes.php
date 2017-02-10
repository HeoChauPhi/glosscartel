<?php
session_start();
// Example Shortcode
/**
add_shortcode( 'asc_scheduling', 'asc_acuityscheduling' );
function asc_acuityscheduling( $atts ) {
  extract( shortcode_atts( array(
  ), $atts ) );
  ob_start();

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}
**/

add_action( 'wp_ajax_datetime', 'datetime_callback' );
add_action( 'wp_ajax_nopriv_datetime', 'datetime_callback' );
function datetime_callback() {
  $options  = get_option('asc_board_settings');
  $values   = $_REQUEST;

  $userID = $options['asc_user_id'];
  $key    = $options['asc_user_key'];
  $url    = 'https://acuityscheduling.com/api/v1/availability/times?appointmentTypeID='.$values['app_id'].'&date='.$values['datetime'];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERPWD, "$userID:$key");
  $result = curl_exec($ch);
  curl_close($ch);

  $data_app = json_decode($result, true);

  $content = '<select name="client_time" class="select-two"> <option selected="" disabled="" value="">Find A Time</option>';
  foreach ($data_app as $value) {
    $dt_str   = $value['time'];
    $dt_arr   = explode('T', $dt_str);
    $dt_d     = $dt_arr[0];
    $dt_t_str = $dt_arr[1];
    $dt_t_arr = explode(':', $dt_t_str);
    $dt_t     = $dt_t_arr[0].':'.$dt_t_arr[1];

    $content .= '<option value='.$value['time'].'>'.$dt_t.'</option>';
  }
  $content .= '</select>';

  $data = json_encode(array('markup' => $content));
  echo $data;
  wp_die();
}

// Feature choose
add_shortcode( 'asc_scheduling', 'ASC_acuityscheduling' );
function ASC_acuityscheduling( $atts ) {
  extract( shortcode_atts( array(
  ), $atts ) );
  ob_start();
    $link_signup = home_url('appointment-scheduling-signin');
    $link_paynow = home_url('appointment-scheduling-client-choose');

    $args_app = array(
      'parent' => 0
    );

    $context = Timber::get_context();
    $context['cat_app'] = Timber::get_terms('service_product', $args_app);

    if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'Book Now' ) {
      if( isset($_POST['client_area'], $_POST['client_service'], $_POST['client_date'], $_POST['client_time']) ) {
        $client_area        = $_POST['client_area'];
        $client_service     = $_POST['client_service'];
        $client_date        = $_POST['client_date'];
        $client_time        = $_POST['client_time'];
      }

      //echo $client_service;

      if( empty($client_area) || empty($client_service) || empty($client_date) || empty($client_time) ) {
        $context['area_message']    = __('Please enter Your Area', 'asc');
        $context['service_message'] = __('Please choose Service', 'asc');
        $context['date_message']    = __('Please choose Date', 'asc');
        $context['time_message']    = __('Please choose Time', 'asc');
        wp_redirect('');
      } else if( !empty($client_area) && !empty($client_service) && !empty($client_date) && !empty($client_time) ) {
        $_SESSION['client_area']    = $client_area;
        $_SESSION['client_service'] = $client_service;
        $_SESSION['client_date']    = $client_date;
        $_SESSION['client_time']    = $client_time;

        if(isset($_SESSION['signin_email'])) {
          wp_redirect($link_paynow);
        } else {
          wp_redirect($link_signup);
        }
      }
    }

    Timber::render('templates/form-book-feature.twig', $context);

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}

// Client Choose
add_shortcode( 'asc_client_choose', 'ASC_client_choose' );
function ASC_client_choose( $atts ) {
  extract( shortcode_atts( array(
    'tax_name'      => 'service_product'
  ), $atts ) );

  ob_start();
    if( isset($_SESSION['client_service']) ) {
      $confirm_end_service = $_SESSION['client_service'];
    }

    if( empty($confirm_end_service) ) {
      session_unset();
      session_destroy();

      wp_redirect(home_url());
    }

    /*$ascb_arr = array();
    $data = asbc_get_apoiment($user_id, $user_key, $url_api);
    $product = asbc_get_apoiment($user_id, $user_key, $product_url);

    if (is_array($product) || is_object($product)) {
      foreach ($product as $value) {
        array_push($data, $value);
      }
    }

    if(isset($_COOKIE['Client']['Service'])) {
      foreach ($data as $value) {
        if($value['id'] == $_COOKIE['Client']['Service'] ) {
          $data = $value;
        }
      }
    }*/

    $link = home_url('appointment-scheduling-confirm');

    $args = array(
      'parent' => 0,
      'hide_empty' => false
    );

    $context = Timber::get_context();
    $context['categories'] = Timber::get_terms($tax_name, $args);
    //$context['data'] = $data;

    /*if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'Confirm') {
      if( isset($_POST['confirm_category'], $_POST['confirm_name'], $_POST['confirm_price'], $_POST['confirm_duration'], $_POST['confirm_image']) ) {
        $confirm_catimg   = $_POST['confirm_catimg'];
        $confirm_category   = $_POST['confirm_category'];
        $confirm_name       = $_POST['confirm_name'];
        $confirm_price      = $_POST['confirm_price'];
        $confirm_duration   = $_POST['confirm_duration'];
        $confirm_image      = $_POST['confirm_image'];
      }

      if( isset($_COOKIE['signin']['email'], $_COOKIE['Client']['Service'], $_COOKIE['Client']['Date'], $_COOKIE['Client']['Time']) ) {
        $confirm_end_email    = $_COOKIE['signin']['email'];
        $confirm_end_service  = $_COOKIE['Client']['Service'];
        $confirm_end_date     = $_COOKIE['Client']['Date'];
        $confirm_end_time     = $_COOKIE['Client']['Time'];
      }

      if( empty($confirm_end_email) || empty($confirm_end_service) || empty($confirm_end_date) || empty($confirm_end_time) ) {
        setcookie("returnchoose", __('Please Choose Again!', 'ascb'), time() + 5, '/'); // 86400 = 1 day

        foreach ($_COOKIE['Client'] as $key => $value) {
          unset($_COOKIE['Client['. $key . ']']);
          setcookie('Client['. $key . ']', null, -1, '/');
        }
        foreach ($_COOKIE['confirm'] as $key => $value) {
          unset($_COOKIE['confirm['. $key . ']']);
          setcookie('confirm['. $key . ']', null, -1, '/');
        }

        wp_redirect(home_url());
      } else {
        setcookie("confirm", "", -1, '/'); // 86400 = 1 day
        setcookie("confirm[Catimg]", $confirm_catimg, time() + 3600, '/'); // 86400 = 1 day
        setcookie("confirm[Category]", $confirm_category, time() + 3600, '/'); // 86400 = 1 day
        setcookie("confirm[Name]", $confirm_name, time() + 3600, '/'); // 86400 = 1 day
        setcookie("confirm[Price]", $confirm_price, time() + 3600, '/'); // 86400 = 1 day
        setcookie("confirm[Duration]", $confirm_duration, time() + 3600, '/'); // 86400 = 1 day
        setcookie("confirm[Image]", $confirm_image, time() + 3600, '/'); // 86400 = 1 day

        wp_redirect($link);
      }
    }*/

    if( isset($_SESSION['signin_email'])) {
      $context['user_email'] = $_SESSION['signin_email'];
    } else {
      $context['user_email'] = "";
    }

    Timber::render('templates/appointment-scheduling-client-choose.twig', $context);

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}

// asc from Signup
add_shortcode( 'asc_signup', 'ASC_signup' );
function ASC_signup( $atts ) {
  $title = array();
  $args = array(
    'post_type' => 'asc_user',
    'post_status' => 'any',
    'posts_per_page'  => -1
  );
  $asc_user = new WP_Query($args);

  extract( shortcode_atts( array(
  ), $atts ) );

  ob_start();
    if( $asc_user->have_posts() ) {
      while ( $asc_user->have_posts() ) {
        $asc_user->the_post();
        $post_title = get_the_title();
        $title[] = $post_title;
      }
    }

    $link_paynow = home_url('appointment-scheduling-client-choose');
    $link_signin = home_url('appointment-scheduling-signin');

    $context = Timber::get_context();

    if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'Sign me up') {
      if( isset($_POST['signup_first_name'], $_POST['signup_last_name'], $_POST['signup_email'], $_POST['signup_password'], $_POST['signup_re_password']) ) {
        $signup_first_name  = $_POST['signup_first_name'];
        $signup_last_name   = $_POST['signup_last_name'];
        $signup_email       = $_POST['signup_email'];
        $signup_password    = $_POST['signup_password'];
        $signup_re_password = $_POST['signup_re_password'];
        $signup_recieve     = $_POST['signup_recieve'];
      }

      if( empty($signup_first_name) || empty($signup_last_name) || empty($signup_email) || empty($signup_password) || empty($signup_re_password) ) {
        $context['signup_first_name']   = __('First Name is Require!', 'asc');
        $context['signup_last_name']    = __('Last Name is Require!', 'asc');
        $context['signup_email']        = __('Email is Require!', 'asc');
        $context['signup_password']     = __('Password is Require!', 'asc');
        $context['signup_re_password']  = __('Password is not correct!', 'asc');
      } else {

        if( in_array($signup_email, $title) ) {
          $context['message_signup'] = __('Email ' . $signup_email . ' is existed!', 'asc');
        } elseif( $signup_password != $signup_re_password ) {
          $context['signup_re_password']  = __('Re-Password is not correct!', 'asc');
        } else {
          $_SESSION['signin_email'] = $signup_email;

          $new_post = array(
            'post_title'  =>   $signup_email,
            'post_status' =>   'pending',
            'post_type'   =>   'asc_user'
          );

          // SAVE THE POST
          $pid = wp_insert_post($new_post);
          update_post_meta($pid, '_cmb2_asc_first_name', $signup_first_name);
          update_post_meta($pid, '_cmb2_asc_last_name', $signup_last_name);
          update_post_meta($pid, '_cmb2_asc_email', $signup_email);
          update_post_meta($pid, '_cmb2_asc_pass', $signup_password);

          if(isset($_SESSION['client_service'])) {
            wp_redirect($link_paynow);
          } else {
            wp_redirect($link_signin);
          }
        }
      }
    }

    if( isset($_SESSION['signin_email']) ) {
      $context['user_email'] = $_SESSION['signin_email'];
    } else {
      $context['user_email'] = "";
    }

    Timber::render('templates/form-signup.twig', $context);

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}

// asc from Signin
add_shortcode( 'asc_signin', 'ASC_signin' );
function ASC_signin( $atts ) {
  $title = array();
  $args = array(
    'post_type'   => 'asc_user',
    'post_status' => 'any',
    'posts_per_page'  => -1
  );
  $asc_user = new WP_Query($args);

  extract( shortcode_atts( array(
  ), $atts ) );

  ob_start();
    if( $asc_user->have_posts() ) {
      while ( $asc_user->have_posts() ) {
        $asc_user->the_post();
        $post_title = get_the_title();
        $post_password = asc_post_meta('asc_pass');

        $title_float = str_replace(array('@', '.', ' '),"",$post_title);
        $title_convert = hash('adler32', $title_float);
        $title[$title_convert] = $post_password;
      }
    }


    $link_paynow = home_url('appointment-scheduling-client-choose');
    $link_signin = home_url('appointment-scheduling-signin');

    $context = Timber::get_context();

    if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'Sign In') {
      if( isset($_POST['signin_email'], $_POST['signin_password']) ) {
        $signin_email     = $_POST['signin_email'];
        $signin_password  = $_POST['signin_password'];
      }

      if ( empty($signin_email) || empty($signin_password) ) {
        $context['message_email'] = __('Email Require', 'asc');
        $context['message_pass']  = __('Password Require', 'asc');
      } else {
        $email_float = str_replace(array('@', '.', ' '), "", $signin_email);
        $email_convert = hash('adler32', $email_float);

        if( array_key_exists($email_convert, $title) && $signin_password == $title[$email_convert] ) {
          $_SESSION['signin_email'] = $signin_email;

          if(isset($_SESSION['client_service'])) {
            wp_redirect($link_paynow);
          } else {
            wp_redirect($link_signin);
          }
        } else {
          $context['message_signin'] = __('Email or Password for ' . $signin_email . ' does not exist!', 'asc');
        }
      }
    }

    if( isset($_SESSION['signin_email']) ) {
      $context['user_email'] = $_SESSION['signin_email'];
    } else {
      $context['user_email'] = "";
    }

    Timber::render('templates/form-signin.twig', $context);

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}

// Block User
add_shortcode( 'asc_block_user', 'ASC_block_user' );
function ASC_block_user( $atts ) {
  add_filter( 'body_class', function( $classes ) {
    $classes['appointment_user'];
    return $classes;
  });
  extract( shortcode_atts( array(
  ), $atts ) );

  ob_start();

    $context = Timber::get_context();

    if( isset($_SESSION['signin_email']) ) {
      $context['user_login'] = $_SESSION['signin_email'];
    } else {
      $context['user_login'] = "";
    }

    Timber::render('templates/block-user.twig', $context);

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}

// Forgot Password
add_shortcode( 'asc_forgot_password', 'ASC_forgot_password' );
function ASC_forgot_password( $atts ) {
  $options = get_option('asc_board_settings');

  extract( shortcode_atts( array(
    'email_subject'      => $options['asc_email_subject'],
    'email_message'      => $options['asc_email_message']
  ), $atts ) );

  $title = array();
  $args = array(
    'post_type' => 'asc_user',
    'post_status' => 'any',
    'posts_per_page'  => -1
  );
  $asc_user = new WP_Query($args);

  ob_start();
    if( $asc_user->have_posts() ) {
      while ( $asc_user->have_posts() ) {
        $asc_user->the_post();
        $post_title = get_the_title();
        $post_password = asc_post_meta('asc_pass');

        $title_float = str_replace(array('@', '.', ' '),"",$post_title);
        $title_convert = hash('adler32', $title_float);
        $title[$title_convert] = $post_password;
      }
    }

    $link_forgot = home_url('appointment-scheduling-forgot-password');

    $context = Timber::get_context();

    if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'Send your email') {
      if( isset($_POST['forgot_email']) ) {
        $forgot_email = $_POST['forgot_email'];
      }

      if ( empty($forgot_email) ) {
        $context['message_email'] = __('Email Require', 'asc');
      } else {
        $email_float = str_replace(array('@', '.', ' '),"",$forgot_email);
        $email_convert = hash('adler32', $email_float);

        if ( array_key_exists($email_convert, $title) ) {
          $to = $forgot_email;
          $subject = $email_subject;
          $message = $email_message . $title[$email_convert];
          $header = "";
          $retval = mail ($to,$subject,$message,$header);

          if( $retval == true ) {
            setcookie("forgot_message", $forgot_email, time() + 5, '/');
            wp_redirect($link_forgot);
          } else {
            $context['message_email'] = __('Email is dosen\'t send!', 'asc');
          }
        } else {
          $context['message_email'] = __('Email is not exit!', 'asc');
        }
      }
    }

    if( isset($_COOKIE['forgot_message']) ) {
      $context['forgot_message'] = __('Please check ', 'asc') . '<a href="//mail.google.com" target="_blank">' . __('your Email ', 'asc') . $_COOKIE['forgot_message'] . '</a>' . __(', maybe email will be send to spam box', 'asc');
    } else {
      $context['forgot_message'] = "";
    }

    Timber::render('templates/appointment-scheduling-forgotpass.twig', $context);

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}

// Other Area
add_shortcode( 'asc_otherarea', 'ASC_otherarea' );
function ASC_otherarea( $atts ) {
  $title = array();
  $args = array(
    'post_type' => 'asc_registered',
    'post_status' => 'any',
    'posts_per_page'  => -1
  );
  $asc_registered = new WP_Query($args);

  extract( shortcode_atts( array(
  ), $atts ) );
  ob_start();
    if( $asc_registered->have_posts() ) {
      while ( $asc_registered->have_posts() ) {
        $asc_registered->the_post();
        $post_title = get_the_title();
        $title[] = $post_title;
      }
    }

    $context = Timber::get_context();

    if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'ASC Other Submit') {
      if( isset($_POST['other_email'], $_POST['other_postcode']) ) {
        $other_email      = $_POST['other_email'];
        $other_postcode   = $_POST['other_postcode'];
      }

      if( empty($other_email) || empty($other_postcode) ) {
        $context['other_email']     = __('Email is Require!', 'asc');
        $context['other_postcode']  = __('PostCode is Require!', 'asc');
      } elseif( in_array($other_email, $title) ) {
        $context['message_other'] = __('Email ' . $other_email . ' is existed!', 'asc');
      } else {
        setcookie("other_postcode", 1, time() + 5, '/'); // 86400 = 1 day

        $new_post = array(
          'post_title'  =>   $other_email,
          'post_status' =>   'pending',
          'post_type'   =>   'asc_registered'
        );

        // SAVE THE POST
        $pid = wp_insert_post($new_post);
        update_post_meta($pid, '_cmb2_asc_postcode', $other_postcode);
      }

      ?>
      <script type="text/javascript">
        (function($) {
          $(document).ready(function() {
            if( !!$.cookie("other_postcode") ){
              $('.form-schedule-feature .client_area').val('Other').change();
              $('.block-colorbox').hide();
            } else {
              $('.form-schedule-feature .client_area').val('Other').change();
            }
          });
        })(jQuery);
      </script>
      <?php

      //wp_redirect('');
    }

    Timber::render('templates/block-otherarea.twig', $context);
    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}
