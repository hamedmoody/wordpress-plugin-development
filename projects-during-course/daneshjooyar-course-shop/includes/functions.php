<?php
defined('ABSPATH') || exit;

function dcs_second_to_time( $seconds ){
    $seconds = round( $seconds );
    return sprintf(
        '%02d:%02d:%02d',
        ( $seconds / 3600 ),
        ( $seconds / 60 % 60 ),
        $seconds % 60
    );
}

function dcs_ormat_bytes($size, $precision = 2) { 
    $base = log($size, 1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');   

    return round(pow(1024, $base - floor($base)), $precision) .''. $suffixes[floor($base)];
} 

function dcs_retrieve_remote_file_size($url){
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_NOBODY, TRUE);

    $data = curl_exec($ch);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

    curl_close($ch);
    return $size;
}

function dcs_get_extension( $url ){
    $info = pathinfo( $url );
    return isset( $info['extension'] ) ? $info['extension'] : '';
}