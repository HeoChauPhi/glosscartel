<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/template/pages/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

/*$app_get_id = [];
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
print_r($app_get_id);
print_r($app_get_cat);*/

$context = Timber::get_context();
$context['title_option'] = framework_page('title');
$context['page_layout'] = framework_page('layout_page');
$post = new TimberPost();
$context['post'] = $post;

Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig'), $context );
