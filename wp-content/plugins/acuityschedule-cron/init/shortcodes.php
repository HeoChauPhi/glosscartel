<?php
// Feature choose
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
