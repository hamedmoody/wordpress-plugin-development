<?php
defined('ABSPATH') || exit;
$price          = get_post_meta( $post->ID, '_price', true );
$percent        = get_post_meta( $post->ID, '_discount_percent', true );
$gateway        = get_post_meta( $post->ID, '_gateway', true );
$payment_id     = get_post_meta( $post->ID, '_bitpay_payment_id', true );
$trans_id       = get_post_meta( $post->ID, '_bitpay_trans_id', true );
$card_number    = get_post_meta( $post->ID, '_bitpay_card_number', true );
$error_message  = get_post_meta( $post->ID, '_error_message', true );
$key            = $post->post_excerpt;
$sale_price     = $post->menu_order;
$student        = get_user_by( 'ID', $post->post_author );
?>
<table class="striped widefat">
    <tr>
        <td style="width: 150px">
            <?php esc_html_e('Price', 'daneshjooyar-course-shop');?>
        </td>
        <td>
            <?php echo number_format( $price / 10 );?> <?php esc_attr_e('Tooman', 'daneshjooyar-course-shop');?>
        </td>
    </tr>
    <tr>
        <td>
            <?php esc_html_e('Sale Price', 'daneshjooyar-course-shop');?>
        </td>
        <td>
            <?php echo number_format( $sale_price / 10 );?> <?php esc_attr_e('Tooman', 'daneshjooyar-course-shop');?>
        </td>
    </tr>
    <tr>
        <td>
            <?php esc_html_e('Discount', 'daneshjooyar-course-shop');?>
        </td>
        <td>
            <?php echo esc_html( $percent );?>%
        </td>
    </tr>
    <tr>
        <td>
            <?php esc_html_e('Student', 'daneshjooyar-course-shop');?>
        </td>
        <td>
            <a href="<?php echo admin_url( 'edit.php?post_type=course_register&author=' . $student->ID );?>">
                <?php echo esc_html( $student->display_name );?>
            </a>
        </td>
    </tr>
    <tr>
        <td>
            <?php esc_html_e('Gateway', 'daneshjooyar-course-shop');?>
        </td>
        <td>
            <?php echo esc_html( $gateway );?>
        </td>
    </tr>
    <tr>
        <td>
            <?php esc_html_e('Bitpay Payment Id', 'daneshjooyar-course-shop');?>
        </td>
        <td>
            <?php echo esc_html( $payment_id );?>
        </td>
    </tr>
    <tr>
        <td>
            <?php esc_html_e('Bitpay Transaction ID', 'daneshjooyar-course-shop');?>
        </td>
        <td>
            <?php echo esc_html( $trans_id );?>
        </td>
    </tr>
    <tr>
        <td>
            <?php esc_html_e('Card Number', 'daneshjooyar-course-shop');?>
        </td>
        <td class="alignleft ltr">
            <?php echo esc_html( $card_number );?>
        </td>
    </tr>
    <tr>
        <td>
            <?php esc_html_e('Register Key', 'daneshjooyar-course-shop');?>
        </td>
        <td>
            <?php echo esc_html( $key );?>
        </td>
    </tr>
    <?php if( $error_message ):?>
        <tr>
        <td>
            <?php esc_html_e('Error', 'daneshjooyar-course-shop');?>
        </td>
        <td style="color: red;">
            <?php echo esc_html( $error_message );?>
        </td>
    </tr>
    <?php endif;?>
</table>