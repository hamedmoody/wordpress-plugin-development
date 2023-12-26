<?php
defined('ABSPATH') || exit;
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title><?php echo daneshjooyar_panel_title();?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo daneshjooyar_panel_css( 'fontiran.css' );?>">
    <link rel="stylesheet" href="<?php echo daneshjooyar_panel_css( 'panel.css' );?>">
    <link rel="stylesheet" href="<?php echo daneshjooyar_panel_css( 'toastr.min.css' );?>">
    <script>var panel = <?php echo daneshjooyar_panel_json();?></script>
</head>
<body class="<?php echo daneshjooyar_panel_class();?>">
    