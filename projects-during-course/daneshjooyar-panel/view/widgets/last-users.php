<?php 
defined('ABSPATH') || exit;
$users = get_users([
    'orderby'   => 'registered',
    'order'     => 'DESC',
    'number'    => 5,
]);
?>
<div class="widget user-latest">
    <header>
        <svg id="user-cirlce-a dd" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <g id="Group" transform="translate(6.27 8.11)">
                <path id="Vector" d="M5.62,2.81A2.81,2.81,0,1,1,2.81,0,2.81,2.81,0,0,1,5.62,2.81Z" transform="translate(2.38)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                <path id="Vector-2" data-name="Vector" d="M10.38,4.23C10.38,1.9,8.06,0,5.19,0S0,1.89,0,4.23" transform="translate(0 7.86)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
            </g>
            <path id="Vector-3" data-name="Vector" d="M9.5,0a9.509,9.509,0,0,1,3.7.74A4.054,4.054,0,0,0,13,2a3.921,3.921,0,0,0,.58,2.06,3.684,3.684,0,0,0,.76.91A3.921,3.921,0,0,0,17,6a3.686,3.686,0,0,0,1.25-.21A9.5,9.5,0,1,1,5.56.85" transform="translate(2 3)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
            <path id="Vector-4" data-name="Vector" d="M8,4a3.594,3.594,0,0,1-.12.93,3.734,3.734,0,0,1-.46,1.13A3.9,3.9,0,0,1,5.25,7.79,3.686,3.686,0,0,1,4,8,3.921,3.921,0,0,1,1.34,6.97a3.684,3.684,0,0,1-.76-.91A3.921,3.921,0,0,1,0,4,4.054,4.054,0,0,1,.2,2.74a3.945,3.945,0,0,1,.93-1.53A4,4,0,0,1,4,0,3.944,3.944,0,0,1,6.97,1.33,3.984,3.984,0,0,1,8,4Z" transform="translate(15 1)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
            <g id="Group-2" data-name="Group" transform="translate(17.51 3.52)">
                <path id="Vector-5" data-name="Vector" d="M2.98,0H0" transform="translate(0 1.46)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                <path id="Vector-6" data-name="Vector" d="M0,0V2.99" transform="translate(1.49)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
            </g>
            <path id="Vector-7" data-name="Vector" d="M0,0H24V24H0Z" fill="none" opacity="0"/>
        </svg>
        <h2>آخرین کاربران ثبت نامی</h2>
    </header>
    <div class="widget-body">
        <?php foreach( $users as $user ):?>
            <?php
            $name = trim( $user->first_name . ' ' . $user->last_name );
            if( ! $name ){
                $name = $user->display_name;
            }
            $timesatmp  = strtotime( $user->user_registered );
            $time       = date_i18n( 'd F Y', $timesatmp );
            $diff       = time() - $timesatmp;
            if( $diff < 60 ){
                $time = $diff . ' ثانیه پیش';
            }elseif( $diff < 3600 ){
                $time = round( $diff / 60 ) . ' دقیقه پیش';
            }elseif( $diff < 86400 ){
                $time = round( $diff / 3600 ) . ' ساعت پیش';
            }
            ?>
            <div class="registered-user">
                <?php echo get_avatar( $user->user_email, 48, $name );?>
                <strong><?php echo $name;?></strong>
                <span><?php echo $time;?></span>
            </div>
        <?php endforeach;?>
    </div>
</div><!--.user-latest-->