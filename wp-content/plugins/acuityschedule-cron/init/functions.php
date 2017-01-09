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

function asc_get_appid() {

}

if ( empty($options_asc['asc_user_id']) || empty($options_asc['asc_user_key']) ){
  echo 'no function';
} else {
  function asc_add_apponment() {
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

      $new_post = array(
        'post_title'  =>   $value['name'],
        'post_status' =>   'publish',
        'post_type'   =>   'asc_appointment',
      );

      // SAVE THE POST
      if ( !in_array($value['id'], $asc_appid) ) {
        $post = wp_insert_post ($new_post);
        update_post_meta($post, '_cmb2_asc_app_id', $value['id']);
        wp_set_object_terms($post, $term_name, 'service_product');
      } else {

      }
    }
  }
  add_action('init', 'asc_add_apponment');
}

