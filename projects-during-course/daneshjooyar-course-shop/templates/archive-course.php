<?php
defined('ABSPATH') || exit;
$page = get_query_var('paged') ? get_query_var( 'paged' ) : 1;
$queried_object = get_queried_object();
get_header();
?>
    <div class="dcs-container">
        <h1>
            <?php
                if( is_post_type_archive( 'course' ) ){
                    esc_html_e( 'Courses', 'daneshjooyar-course-shop' );
                }elseif( is_tax( 'course_cat' ) ){
                    esc_html_e( 'Course category ', 'daneshjooyar-course-shop' );
                    echo ' ' . $queried_object->name;
                }elseif( is_tax( 'course_tag' ) ){
                    esc_html_e( 'Course Tag ', 'daneshjooyar-course-shop' );
                    echo ' ' . $queried_object->name;
                }
            ?>
            <?php if( $page > 1 ):?>
                ( <?php esc_html_e( 'Page', 'daneshjooyar-course-shop' );?> <?php echo $page;?> ) 
            <?php endif;?>
        </h1>
        <?php if( have_posts() ):?>
            <div class="dcs-cards">
            <?php while( have_posts() ):the_post();?>
                <?php

                global $post;

                $price          = get_post_meta( $post->ID, '_dcs_price', true );
                $sale_price     = get_post_meta( $post->ID, '_dcs_sale_price', true );
                $demo           = get_post_meta( $post->ID, '_dcs_demo', true );
                $expire         = get_post_meta( $post->ID, '_dcs_expire', true );
                $duration       = get_post_meta( $post->ID, '_dcs_duration', true );
                $student_count  = intval( get_post_meta( $post->ID, '_dcs_student_count', true ) );
                $has_discount   = get_post_meta( $post->ID, '_dcs_has_discount', true );
                $teacher_id     = get_post_meta( $post->ID, '_dcs_teacher', true );
                $teacher        = get_user_by( 'ID', $teacher_id );

                $is_student     = true;

                $final_price    = $price;
                if(
                    $has_discount
                    &&
                    $sale_price
                    &&
                    $sale_price < $price
                ){
                    
                    if( $expire ){
                        if( strtotime( $expire ) > current_time('timestamp') ){
                            $final_price = $sale_price;
                        }
                    }else{
                        $final_price = $sale_price;
                    }

                }

                $percent        = 100 - round( $final_price / $price * 100 );

                ?>
                <a <?php post_class( 'dcs-card' );?> href="<?php the_permalink();?>">
                    <picture>
                        <?php the_post_thumbnail();?>
                    </picture>

                    <h3><?php the_title();?></h3>
                    <div class="dcs-card-row">
                        <p class="card-blog-author">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19.67" height="21.58" viewBox="0 0 19.67 21.58">
                                <g id="user-octagon" transform="translate(-2.16 -1.21)">
                                    <path id="Path_2" data-name="Path 2" d="M21.08,8.58v6.84a3.174,3.174,0,0,1-1.57,2.73l-5.94,3.43a3.163,3.163,0,0,1-3.15,0L4.48,18.15a3.149,3.149,0,0,1-1.57-2.73V8.58A3.174,3.174,0,0,1,4.48,5.85l5.94-3.43a3.163,3.163,0,0,1,3.15,0l5.94,3.43A3.162,3.162,0,0,1,21.08,8.58Z" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                                    <path id="Path_3" data-name="Path 3" d="M12,11A2.33,2.33,0,1,0,9.67,8.67,2.33,2.33,0,0,0,12,11Z" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                                    <path id="Path_4" data-name="Path 4" d="M16,16.66c0-1.8-1.79-3.26-4-3.26s-4,1.46-4,3.26" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                                </g>
                            </svg>
                            <?php echo $teacher->display_name;?>
                        </p>
                        <?php if( $percent ):?>
                        <p class="card-course-old-price">
                            <del><?php echo number_format( $price / 10 );?></del>
                            <span><?php echo $percent;?>%</span>
                        </p>
                        <?php endif;?>
                    </div>
                    <div class="dcs-card-row">
                        <p class="card-course-duration">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="21.5" viewBox="0 0 19 21.5">
                                <g id="timer-start" transform="translate(-2.5 -1.25)">
                                    <path id="Path_5" data-name="Path 5" d="M12,8v5" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                                    <path id="Path_6" data-name="Path 6" d="M12,22a8.75,8.75,0,1,1,8.75-8.75" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                                    <path id="Path_7" data-name="Path 7" d="M9,2h6" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5"></path>
                                    <path id="Path_8" data-name="Path 8" d="M14.9,18.5V17.34c0-1.43,1.02-2.02,2.26-1.3l1,.58,1,.58a1.381,1.381,0,0,1,0,2.61l-1,.58-1,.58c-1.24.72-2.26.13-2.26-1.3Z" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5"></path>
                                </g>
                            </svg>
                            <?php echo dcs_second_to_time( $duration );?>
                        </p>
                        <p class="card-course-price">
                            <ins><?php echo number_format( $final_price / 10 );?></ins>
                            <span>
                                <?php esc_html_e( 'Tooman', 'daneshjooyar-course-shop' );?>
                            </span>
                        </p>
                    </div>
                    <footer>
                        <span class="dsc-btn dsc-btn-primary">
                            <?php esc_html_e( 'view course', 'daneshjooyar-course-shop' );?>
                        </span>
                    </footer>
                </a>

            <?php endwhile;?>
            </div><!--.dcs-cards-->
            <?php ?>
            <div class="dcs-pagination">
                <?php the_posts_pagination(  );?>
            </div>
        <?php else:?>
            <div>
                <?php esc_html_e( 'No course found', 'daneshjooyar-course-shop' );?>
            </div>
        <?php endif;?>
    </div>
<?php get_footer();
