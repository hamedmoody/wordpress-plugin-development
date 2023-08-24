<?php
/**
 * Plugin Name: Estimate study duration
 * Plugin URI: https://daneshjooyar.com/plugins/estimate-study-duration
 * Description: This plugin can estimate post study duration
 * Author: Hamed Moodi
 * Author URI: https://hamedmoody.ir
 * Version: 1.0.0
 * License: GPLv2 or later
 */

function echo_estimate_study_duration( $content ){
    $content_text           = strip_tags( $content );
    $content_words          = explode( ' ', $content_text );
    $word_count             = count( $content_words );
    $estimate_duration      = round( $word_count / 200 );
    $estimate_duration_html = '<p>';
        $estimate_duration_html.= 'مدت زمان برای مطالعه: ';
        $estimate_duration_html.= $estimate_duration . ' دقیقه';
    $estimate_duration_html.= '</p>';
    return $estimate_duration_html . $content;
}
add_filter( 'the_content', 'echo_estimate_study_duration' );