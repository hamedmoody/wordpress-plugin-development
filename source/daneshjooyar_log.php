<?php
function daneshjooyar_log( $log ){
    $log_file = fopen( WP_CONTENT_DIR . '/daneshjooyar_log.txt', 'a' );
    fwrite( $log_file, date( 'Y-m-d H:i:s' ) . ': ' . print_r( $log, true ) . PHP_EOL );
    fclose( $log_file );
}