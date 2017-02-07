<?php
if ( file_exists(  dirname(__FILE__)  . '/cmb2/init.php' ) ) {
  require_once  dirname(__FILE__)  . '/cmb2/init.php';
} elseif ( file_exists(  dirname(__FILE__)  . '/CMB2/init.php' ) ) {
  require_once  dirname(__FILE__)  . '/CMB2/init.php';
}

/*add_action( 'init', 'wf_meta_boxes', 9999 );
function wf_meta_boxes() {
  if ( !class_exists( 'cmb_Meta_Box' ) ) {
    require_once( dirname(__FILE__) . '/metabox/init.php' );
  }
}*/
require_once dirname(__FILE__) . '/option-page/options-page.php';
require_once dirname(__FILE__) . '/option-post/options-post.php';
