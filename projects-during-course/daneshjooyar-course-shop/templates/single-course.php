<?php
defined('ABSPATH') || exit;

global $post;

$price          = get_post_meta( $post->ID, '_dcs_price', true );
$sale_price     = get_post_meta( $post->ID, '_dcs_sale_price', true );
$demo           = get_post_meta( $post->ID, '_dcs_demo', true );
$expire         = get_post_meta( $post->ID, '_dcs_expire', true );
$duration_total = get_post_meta( $post->ID, '_dcs_duration', true );
$student_count  = dcs_get_student_count( $post->ID );
$has_discount   = get_post_meta( $post->ID, '_dcs_has_discount', true );
$teacher_id     = get_post_meta( $post->ID, '_dcs_teacher', true );
$teacher        = get_user_by( 'ID', $teacher_id );

$is_student     = is_user_logged_in() && dcs_is_student( $post->ID, get_current_user_id() );

$final_price    = dcs_get_course_final_price( $post->ID );

$percent        = dcs_get_final_discount_percent( $post->ID );

if( is_user_logged_in() ){
    $register_in_course_url = home_url(
        sprintf(
            '?gateway=bitpay&register_course_id=%d&action=dcs_payment'
            , $post->ID
        )
    );
}else{
    $register_in_course_url = wp_login_url( get_the_permalink( ) );
}

if( isset( $_GET['manual_regsiter'] ) ){
    var_dump( dcs_create_course_register( get_current_user_id(), get_the_ID(), 'zarinpal' ) );
    exit;
}

$playlist_query = new WP_Query( [
    'post_type'         => 'playlist_item',
    'posts_per_page'    => -1,
    'post_status'       => ['free', 'premium'],
    'orderby'           => 'menu_order',
    'order'             => 'asc',
    'post_parent'       => $post->ID
] );

get_header();
?>

<div class="dcs-container">
    <?php if( have_posts() ):?>
        <?php while( have_posts() ):the_post();?>
        <h1><?php the_title();?></h1>
        <div class="dcs-cols">
            <div class="dcs-main-col">
                <div class="dcs-player">
                    <a href="#" class="dcs-play">
                        <svg width="36px" height="36px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.4086 9.35258C23.5305 10.5065 23.5305 13.4935 21.4086 14.6474L8.59662 21.6145C6.53435 22.736 4 21.2763 4 18.9671L4 5.0329C4 2.72368 6.53435 1.26402 8.59661 2.38548L21.4086 9.35258Z" fill="#1C274C"/>
                        </svg>
                    </a>
                    <video src="<?php echo esc_attr( $demo );?>"></video>
                </div>

                <?php if( $playlist_query->have_posts() ):?>
                <div class="dcs-playlist">
                    <h2><?php esc_html_e( 'Playlist', 'daneshjooyar-course-shop' );?></h2>
                    <div class="dcs-playlist-items">
                        <?php while( $playlist_query->have_posts() ):?>
                            <?php
                            $playlist_query->the_post();
                            global $wp_query, $post;
                            $is_premium     = $post->post_status == 'premium';
                            
                            $width          = get_post_meta( get_the_ID(), '_dcs_width', true );
                            $height         = get_post_meta( get_the_ID(), '_dcs_height', true );
                            $duration       = get_post_meta( get_the_ID(), '_dcs_duration', true );
                            $size           = get_post_meta( get_the_ID(), '_dcs_size', true );
                            
                            $lock           = ! $is_student && $post->post_status == 'premium';
                            $is_playbale    = $post->post_mime_type == 'video/mp4';
                            $url            = '';
                            if( ! $is_premium || $is_student ){
                                $url = $post->guid;
                            }

                            $ext            = dcs_get_extension( $url );

                            ?>
                            <div class="dcs-item" data-url="<?php echo $url;?>">
                                <span><?php echo $post->menu_order + 1;?></span>
                                <?php if( ! $lock ):?>
                                    <?php if( $is_playbale ):?>
                                        <img src="<?php echo DANESHJOOYAR_COURSE_SHOP_IMAGE;?>play.svg" class="dcs-play" alt="play-icon">
                                        <img src="<?php echo DANESHJOOYAR_COURSE_SHOP_IMAGE;?>pause.svg" class="dcs-pause" alt="pause-icon">
                                    <?php else:?>
                                        <img src="<?php echo DANESHJOOYAR_COURSE_SHOP_IMAGE;?>download.svg" class="dcs-download" alt="download-icon">
                                    <?php endif;?>
                                <?php else:?>
                                    <img src="<?php echo DANESHJOOYAR_COURSE_SHOP_IMAGE;?>lock.svg" class="dcs-lock" alt="lock-icon">
                                <?php endif;?>
                                <p>
                                    <?php the_title();?>
                                    <?php if( $is_premium && ! $is_student ):?>
                                    <span class="dcs-badge">
                                        <?php esc_html_e( 'Premium', 'daneshjooyar-course-shop' );?>
                                    </span>
                                    <?php endif;?>
                                </p>
                                <span>
                                    <?php if( $is_playbale ):?>
                                        <?php echo dcs_second_to_time(  $duration );?>
                                    <?php elseif( $size ):?>
                                        <?php echo dcs_ormat_bytes( $size );?> | <?php echo $ext;?>
                                    <?php else:?>
                                        --
                                    <?php endif;?>
                                </span>
                            </div>
                        <?php endwhile;?>
                    </div>
                </div>
                <?php endif;wp_reset_postdata();?>
                <div class="dcs-content">
                    <?php the_content();?>
                </div>
                <div class="dcs-tax">
                    <div>
                        <?php esc_html_e( 'Categories', 'daneshjooyar-course-shop' );?>: 
                        <?php echo get_the_term_list( get_the_ID(), 'course_cat', '', ', ' );?>
                    </div>
                    <div>
                        <?php esc_html_e( 'Tags', 'daneshjooyar-course-shop' );?>: 
                        <?php echo get_the_term_list( get_the_ID(), 'course_tag', '', ', ' );?>
                    </div>
                </div>
            </div>
            <div class="dcs-side-col">
                <?php if( isset( $_GET['dcs_payment_error'] ) ):?>
                <div class="dcs-message dcs-erro">
                    <?php esc_html_e( 'An error occured during payment', 'daneshjooyar-course-shop' );?>
                </div>
                <?php endif;?>
                <div class="dcs-course-information">
                    <div class="dcs-course-teacher">
                        <?php echo get_avatar( $teacher->ID, 116 );?>
                        <p><?php echo $teacher->display_name;?></p>
                    </div>
                    <p>
                        <?php esc_html_e( 'Duration', 'daneshjooyar-course-shop' );?>
                        <span><?php echo dcs_second_to_time( $duration_total );?></span>
                    </p>
                    <p>
                        <?php esc_html_e( 'Students', 'daneshjooyar-course-shop' );?>
                        <span><?php echo $student_count;?></span>
                    </p>
                    <p>
                        <?php esc_html_e( 'Date', 'daneshjooyar-course-shop' );?>
                        <span>
                            <?php the_date( 'Y-m-d' );?>
                        </span>
                    </p>
                    <?php if( $is_student ):?>
                    <div class="dcs-is-student">
                        <?php esc_html_e( 'Your are student', 'daneshjooyar-course-shop' );?>
                    </div>
                    <?php else:?>
                    <div class="dcs-course-price <?php echo $percent ? 'dcs-has-discount' : '';?>">
                        <?php if( $percent ):?>
                        <span class="dcs-discount-percent">
                            <?php echo $percent;?>%
                            <?php esc_html_e( 'off', 'daneshjooyar-course-shop' );?>
                        </span>
                        <?php endif;?>
                        <div class="dcs-course-price-amount">
                            <?php if( $percent ):?>    
                                <del><?php echo number_format( $price / 10 );?></del>
                            <?php endif;?>
                            <ins><?php echo number_format( $final_price / 10 );?></ins>
                            <span><?php esc_html_e( 'Tooman', 'daneshjooyar-course-shop' );?></span>
                        </div>
                    </div>
                    <a href="<?php echo esc_attr( $register_in_course_url );?>" class="dcs-btn dcs-btn-primary">
                        <?php esc_html_e( 'Buy this course', 'daneshjooyar-course-shop' );?>
                    </a>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <div class="dcs-comments">
            <?php
            if( comments_open() || get_comments_number() ){
                comments_template();
            }
            ?>
        </div>
    <?php endwhile;?>
<?php endif;?>
</div>

<?php get_footer();
