<?php
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
  $values = $_REQUEST;

  $userID = '12648352';
  $key = '7a929774222d17671054d7cc5199f029';

  // $userID = $options_asc['asc_user_id'];
  // $key = $options_asc['asc_user_key'];
  $url = 'https://acuityscheduling.com/api/v1/availability/times?appointmentTypeID='.$values['app_id'].'&date='.$values['datetime'];

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
  $options = get_option('asc_board_settings');

  extract( shortcode_atts( array(
    /*'user_id'       => $options['asc_user_id'],
    'user_key'      => $options['asc_user_key'],
    'url_api'       => $options['asc_url_api'],
    'product_url'   => $options['asc_product_url']*/
  ), $atts ) );
  ob_start();
    $link_signup = home_url('appointment-scheduling-registor');
    $link_paynow = home_url('appointment-scheduling-client-choose');

    $args_app = array(
      'post_type'       => 'asc_appointment',
      'orderby'         => 'date',
      'order'           => 'ASC',
      'posts_per_page'  => -1
    );

    $context = Timber::get_context();
    $post_app = Timber::get_posts($args_app);
    $context['post_app'] = $post_app;
    $context['cat_app'] = Timber::get_terms('service_product');

    /*$userID = '12648352';
    $key = '7a929774222d17671054d7cc5199f029';

    // $userID = $options_asc['asc_user_id'];
    // $key = $options_asc['asc_user_key'];
    $url = 'https://acuityscheduling.com/api/v1/availability/times?appointmentTypeID=1743364&date=2017-01-22';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$userID:$key");
    $result = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($result, true);
    print_r($data);*/

    /*if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'Book Now' ) {
      if( isset($_POST['client_area'], $_POST['client_service'], $_POST['client_date'], $_POST['client_time']) ) {
        $client_area        = $_POST['client_area'];
        $client_service     = $_POST['client_service'];
        $client_time        = $_POST['client_time'];

        $client_date_input  = date_create($_POST['client_date']);
        $client_date        = date_format($client_date_input, 'Y-m-d');
      }

      if( empty($client_area) || empty($client_service) || empty($client_date) || empty($client_time) ) {
        $context['area_message']    = __('Please enter Your Area', 'asc');
        $context['service_message'] = __('Please choose Service', 'asc');
        $context['date_message']    = __('Please choose Date', 'asc');
        $context['time_message']    = __('Please choose Time', 'asc');
        wp_redirect('');
      } else if( !empty($client_area) || !empty($client_service) || !empty($client_date) || !empty($client_time) ) {
        unset($_COOKIE['returnchoose']);
        setcookie('returnchoose', null, -1, '/');

        setcookie("Client", "", -1, '/'); // 86400 = 1 day
        setcookie("Client[Area]", $client_area, time() + 3600, '/'); // 86400 = 1 day
        setcookie("Client[Service]", $client_service, time() + 3600, '/'); // 86400 = 1 day
        setcookie("Client[Date]", $client_date, time() + 3600, '/'); // 86400 = 1 day
        setcookie("Client[Time]", $client_time, time() + 3600, '/'); // 86400 = 1 day

        if(isset($_COOKIE['signin']['email'])) {
          wp_redirect($link_paynow);
        } else {
          wp_redirect($link_signup);
        }
      }
    }*/

    Timber::render('templates/form-book-feature.twig', $context);

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
        $post_password = asc_post_meta('asc_pass');

        $title_float = str_replace(array('@', '.', ' '),"",$post_title);
        $title_convert = hash('adler32', $title_float);
        $title[] = $title_convert;
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
      }

      if( empty($signup_first_name) || empty($signup_last_name) || empty($signup_email) || empty($signup_password) || empty($signup_re_password) ) {
        $context['signup_first_name']   = __('First Name is Require!', 'asc');
        $context['signup_last_name']    = __('Last Name is Require!', 'asc');
        $context['signup_email']        = __('Email is Require!', 'asc');
        $context['signup_password']     = __('Password is Require!', 'asc');
        $context['signup_re_password']  = __('Password is not correct!', 'asc');
      } else {
        $email_float = str_replace(array('@', '.', ' '), "", $signup_email);
        $email_convert = hash('adler32', $email_float);

        if( in_array($email_convert, $title) ) {
          $context['message_signup'] = __('Email ' . $signup_email . ' is existed!', 'asc');
        } elseif( $signup_password != $signup_re_password ) {
          $context['signup_re_password']  = __('Re-Password is not correct!', 'asc');
        } else {
          setcookie("signin", "", time() - 86400, '/'); // 86400 = 1 day
          setcookie("signin[email]", $signup_email, time() + 86400, '/'); // 86400 = 1 day

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

          if(isset($_COOKIE['Client']['Service'])) {
            wp_redirect($link_paynow);
          } else {
            wp_redirect($link_signin);
          }
        }
      }
    }

    if( isset($_COOKIE['signin']['email']) && !empty($_COOKIE['signin']['email'])) {
      $context['user_email'] = $_COOKIE['signin']['email'];
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
          setcookie("signin", "", time() - 86400, '/'); // 86400 = 1 day
          setcookie("signin[email]", $signin_email, time() + 86400, '/'); // 86400 = 1 day

          if(isset($_COOKIE['Client']['Service'])) {
            wp_redirect($link_paynow);
          } else {
            wp_redirect($link_signin);
          }
        } else {
          $context['message_signin'] = __('Email or Password for ' . $signin_email . ' does not exist!', 'asc');
        }
      }
    }

    if( isset($_COOKIE['signin']['email']) && !empty($_COOKIE['signin']['email'])) {
      $context['user_email'] = $_COOKIE['signin']['email'];
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
  extract( shortcode_atts( array(
  ), $atts ) );

  ob_start();
    $context = Timber::get_context();

    if( isset($_COOKIE['signin']['email']) && !empty($_COOKIE['signin']['email'])) {
      $context['user_login'] = $_COOKIE['signin']['email'];
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
