<?php
defined('ABSPATH') || exit;
?>
<table class="widefat <?php echo $has_discount ? 'dcs_has_discount' : '';?>" id="dcs-course-data-table">
    <tr>
        <td style="width: 150px;">
            <label for="dcs_course_demo">
                <?php esc_html_e( 'Demo', 'daneshjooyar-course-shop' );?>
            </label>
        </td>
        <td>
            <input type="url" id="dcs_course_demo" name="dcs_course[demo]" value="<?php echo esc_url( $demo );?>" placeholder="<?php esc_attr_e( 'demo url here', 'daneshjooyar-course-shop' );?>">
            <button type="button" class="button button-secondary" id="dcs_demo_uploader">
                <?php esc_html_e( 'Select video', 'daneshjooyar-course-shop' );?>
            </button>
        </td>
    </tr>
    <tr>
        <td>
            <label for="dcs_course_teacher">
                <?php esc_html_e( 'Teacher', 'daneshjooyar-course-shop' );?>
            </label>
        </td>
        <td>
            <select id="dcs_course_teacher" name="dcs_course[teacher]">
                <option value="">
                    <?php esc_html_e( 'Select Teacher', 'daneshjooyar-course-shop' );?>
                </option>
                <?php foreach( get_users( ['fields' => ['id', 'display_name']] ) as $user ):?>
                    <option value="<?php echo $user->ID;?>" <?php selected( $user->ID, $teacher_id );?>>
                        <?php echo $user->display_name;?>
                    </option>
                <?php endforeach;?>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            <label for="dcs_course_price">
                <?php esc_html_e( 'Price', 'daneshjooyar-course-shop' );?>
            </label>
        </td>
        <td>
            <input type="number" min="0" id="dcs_course_price" name="dcs_course[price]" value="<?php echo esc_attr( $price );?>" placeholder="<?php esc_attr_e( 'price here', 'daneshjooyar-course-shop' );?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for="dcs_course_has_discount">
                <?php esc_html_e( 'has discount?', 'daneshjooyar-course-shop' );?>
            </label>
        </td>
        <td>
            <input type="checkbox" <?php checked( $has_discount );?> id="dcs_course_has_discount" name="dcs_course[has_discount]" value="1">
        </td>
    </tr>
    <tr class="dcs_discoubt_base">
        <td>
            <label for="dcs_course_sale_price">
                <?php esc_html_e( 'sale Price', 'daneshjooyar-course-shop' );?>
            </label>
        </td>
        <td>
            <input type="number" min="0" id="dcs_course_sale_price" name="dcs_course[sale_price]" value="<?php echo esc_attr( $sale_price );?>" placeholder="<?php esc_attr_e( 'sale price here', 'daneshjooyar-course-shop' );?>">
        </td>
    </tr>
    <tr class="dcs_discoubt_base">
        <td>
            <label for="dcs_course_discount_expire">
                <?php esc_html_e( 'Discount Expire', 'daneshjooyar-course-shop' );?>
            </label>
        </td>
        <td>
            <input type="text" class="ltr dcs_course_discount_expire_jalali" data-jdp readonly>
            <input type="hidden" id="dcs_course_discount_expire" name="dcs_course[discount_expire]" value="<?php echo esc_attr( $expire );?>">
        </td>
    </tr>
</table>