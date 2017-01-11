<?php
$options_asc = get_option('asc_board_settings');

function asc_get_apoiment($url_api) {
  $options_asc = get_option('asc_board_settings');

  /*$userID = '12648352';
  $key = '7a929774222d17671054d7cc5199f029';*/

  $userID = $options_asc['asc_user_id'];
  $key = $options_asc['asc_user_key'];
  $url = $url_api;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERPWD, "$userID:$key");
  $result = curl_exec($ch);
  curl_close($ch);

  $data = json_decode($result, true);

  return $data;
}

/* Update Appointment */
if (is_admin()) {
  if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'Update Appointment' ) {
    // Remove Posts Appointment
    add_action('init', 'asc_update_remove_apponment');
    function asc_update_remove_apponment() {
      $app_get_id = [];
      $app_get_cat = [];
      $data = asc_get_apoiment('https://acuityscheduling.com/api/v1/appointment-types');
      $product = asc_get_apoiment('https://acuityscheduling.com/api/v1/products');
      foreach ($product as $product_type) {
        $data[] = $product_type;
      }
      foreach ($data as $appid) {
        $app_get_id[] = $appid['id'];
        if (isset($appid['category'])){
          $app_get_cat[] = $appid['category'];
        }
      }
      $app_get_id = array_keys(array_flip($app_get_id));
      $app_get_cat = array_keys(array_flip($app_get_cat));

      // Remove Posts app
      $args = array(
        'post_type' => 'asc_appointment',
        'posts_per_page'  => -1
      );
      $asc_app = new WP_Query($args);
      if( $asc_app->have_posts() ) {
        while ( $asc_app->have_posts() ) {
          $asc_app->the_post();
          $post_appid = asc_post_meta('asc_app_id');

          $post_id = get_the_ID();
          if ( !in_array($post_appid, $app_get_id) ) {
            wp_delete_post($post_id);
          }
        }
        wp_reset_postdata();
      }

      // Remove terms
      $args_tax = array(
        'parent' => 0,
        'orderby' => 'slug',
        'hide_empty' => false
      );
      $terms = get_terms( 'service_product', $args_tax);
      foreach ($terms as $value) {
        $term_id = $value->term_id;
        $term_name = $value->name;
        if ( !in_array($term_name, $app_get_cat) ) {
          wp_delete_term( $term_id, 'service_product' );
        }
      }
    }

    // Add Posts Appointment
    add_action('init', 'asc_update_add_apponment');
    function asc_update_add_apponment() {
      $options_asc = get_option('asc_board_settings');
      if ( !empty($options_asc['asc_user_id']) && !empty($options_asc['asc_user_key']) ) {
        $data = asc_get_apoiment('https://acuityscheduling.com/api/v1/appointment-types');
        $product = asc_get_apoiment('https://acuityscheduling.com/api/v1/products');
        foreach ($product as $product_type) {
          $data[] = $product_type;
        }

        //print_r($data);

        $asc_appid = array();
        $args = array(
          'post_type' => 'asc_appointment',
          'posts_per_page'  => -1
        );
        $asc_app = new WP_Query($args);
        if( $asc_app->have_posts() ) {
          while ( $asc_app->have_posts() ) {
            $asc_app->the_post();
            $post_app_id = asc_post_meta('asc_app_id');
            $post_id = get_the_ID();
            $asc_appid[$post_id] = $post_app_id;
          }
          wp_reset_postdata();
        }

        foreach ($data as $value) {
          $term_name = '';
          if ( isset($value['category']) ) {
            $term_name      = $value['category'];
          } else {
            $term_name      = 'Packages And Gift Certificates';
          }

          if ( isset($value['calendarIDs']) ){
            $calendar = '';
            foreach ($value['calendarIDs'] as $calendarid) {
              if ( sizeof($value['calendarIDs']) > 1 ) {
                $calendar = $calendar . ' ' . $calendarid;
              } else {
                $calendar = $calendarid;
              }
            }
            $calendar = ltrim($calendar);
          }
          $prefix = '_cmb2_';

          $new_post = array(
            'post_title'  =>   $value['name'],
            'post_status' =>   'publish',
            'post_type'   =>   'asc_appointment',
          );

          $update_post = array(
            'ID' => array_search($value['id'], $asc_appid),
            'post_title'  =>   $value['name']
          );

          // SAVE THE POST
          if ( !in_array($value['id'], $asc_appid) ) {
            $post   = wp_insert_post ($new_post);
            update_post_meta($post, $prefix . 'asc_app_id', $value['id']);
            update_post_meta($post, $prefix . 'asc_app_price', $value['price']);
            update_post_meta($post, $prefix . 'asc_app_duration', $value['duration']);
            update_post_meta($post, $prefix . 'asc_app_blockoffb', $value['paddingBefore']);
            update_post_meta($post, $prefix . 'asc_app_blockoffa', $value['paddingAfter']);
            update_post_meta($post, $prefix . 'asc_app_description', $value['description']);
            if (isset($calendar)){
              update_post_meta($post, $prefix . 'asc_app_calendar', $calendar);
            }
            wp_set_object_terms($post, $term_name, 'service_product');
          } else {
            $post_update = wp_update_post($update_post);
            update_post_meta($post_update, $prefix . 'asc_app_price', $value['price']);
            if (isset($value['duration'])){
              update_post_meta($post_update, $prefix . 'asc_app_duration', $value['duration']);
            }
            if (isset($value['paddingBefore'])){
              update_post_meta($post_update, $prefix . 'asc_app_blockoffb', $value['paddingBefore']);
            }
            if (isset($value['paddingAfter'])){
              update_post_meta($post_update, $prefix . 'asc_app_blockoffa', $value['paddingAfter']);
            }
            update_post_meta($post_update, $prefix . 'asc_app_description', $value['description']);
            if (isset($calendar)){
              update_post_meta($post_update, $prefix . 'asc_app_calendar', $calendar);
            }
            wp_set_object_terms($post_update, $term_name, 'service_product');
          }
        }
      }
    }

    setcookie("update_app", __('Appointment Updated', 'asc'), time() + 5, '/'); // 86400 = 1 day
    header("Refresh: 0;");
  }

  if ( isset($_COOKIE['update_app']) ) {
    ?>
      <style type="text/css">
        form#update_appointment:after {
          content: "<?php echo $_COOKIE['update_app'] ?>";
          display: block;
        }
      </style>
    <?php
  }
}

/* Main Plugin Active */
function asc_plugin_activate() {
  add_option( 'Activated_Plugin', 'Plugin-Slug' );
  /* activation code here */
}

function asc_add_apponment() {
  if ( get_option( 'Activated_Plugin' ) == 'Plugin-Slug' ) {
    delete_option( 'Activated_Plugin' );
    delete_option( 'Deactivated_Plugin' );

    $options_asc = get_option('asc_board_settings');
    if ( !empty($options_asc['asc_user_id']) && !empty($options_asc['asc_user_key']) ) {
      $data = asc_get_apoiment('https://acuityscheduling.com/api/v1/appointment-types');
      $product = asc_get_apoiment('https://acuityscheduling.com/api/v1/products');
      foreach ($product as $product_type) {
        $data[] = $product_type;
      }

      //print_r($data);

      $asc_appid = array();
      $args = array(
        'post_type' => 'asc_appointment',
        'posts_per_page'  => -1
      );
      $asc_app = new WP_Query($args);
      if( $asc_app->have_posts() ) {
        while ( $asc_app->have_posts() ) {
          $asc_app->the_post();
          $post_app_id = asc_post_meta('asc_app_id');
          $asc_appid[] = $post_app_id;
        }
        wp_reset_postdata();
      }

      foreach ($data as $value) {
        $term_name = '';
        if ( isset($value['category']) ) {
          $term_name      = $value['category'];
        } else {
          $term_name      = 'Packages And Gift Certificates';
        }

        if ( isset($value['calendarIDs']) ){
          $calendar = '';
          foreach ($value['calendarIDs'] as $calendarid) {
            if ( sizeof($value['calendarIDs']) > 1 ) {
              $calendar = $calendar . ' ' . $calendarid;
            } else {
              $calendar = $calendarid;
            }
          }
          $calendar = ltrim($calendar);
        }
        $prefix = '_cmb2_';

        $new_post = array(
          'post_title'  =>   $value['name'],
          'post_status' =>   'publish',
          'post_type'   =>   'asc_appointment',
        );

        // SAVE THE POST
        if ( !in_array($value['id'], $asc_appid) ) {
          $post   = wp_insert_post ($new_post);
          update_post_meta($post, $prefix . 'asc_app_id', $value['id']);
          update_post_meta($post, $prefix . 'asc_app_price', $value['price']);
          update_post_meta($post, $prefix . 'asc_app_duration', $value['duration']);
          update_post_meta($post, $prefix . 'asc_app_blockoffb', $value['paddingBefore']);
          update_post_meta($post, $prefix . 'asc_app_blockoffa', $value['paddingAfter']);
          update_post_meta($post, $prefix . 'asc_app_description', $value['description']);
          update_post_meta($post, $prefix . 'asc_app_calendar', $calendar);
          wp_set_object_terms($post, $term_name, 'service_product');
        }
      }
    }
  }
}
add_action('init', 'asc_add_apponment');

/* Main Plugin Decctive */
function asc_plugin_deactivate() {
  add_option( 'Deactivated_Plugin', 'Plugin-Slug' );
  /* activation code here */
}

function asc_remove_apponment() {
  if ( get_option( 'Deactivated_Plugin' ) == 'Plugin-Slug' ) {
    // Remove Posts app
    $args = array(
      'post_type' => 'asc_appointment',
      'posts_per_page'  => -1
    );
    $asc_app = new WP_Query($args);
    if( $asc_app->have_posts() ) {
      while ( $asc_app->have_posts() ) {
        $asc_app->the_post();

        $post_id = get_the_ID();
        wp_delete_post($post_id);
      }
      wp_reset_postdata();
    }

    // Remove terms
    $args_tax = array(
      'parent' => 0,
      'orderby' => 'slug',
      'hide_empty' => false
    );
    $terms = get_terms( 'service_product', $args_tax);
    foreach ($terms as $value) {
      $term_id = $value->term_id;
      wp_delete_term( $term_id, 'service_product' );
    }
  }
}
add_action('init', 'asc_remove_apponment');
