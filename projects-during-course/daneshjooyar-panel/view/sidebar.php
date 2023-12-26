<?php
defined('ABSPATH') || exit;
$user           = wp_get_current_user();
$full_name      = trim( $user->first_name . ' ' . $user->last_name );
if( ! $full_name ){
    $full_name = $user->display_name;
}
$panel_page     = get_query_var( 'panel_page' );
$panel_action   = get_query_var( 'panel_action' );
$is_operator    = daneshjooyar_user_can_manage_tickets();
$unread_message = 0;
if( $is_operator ){
    $unread_message = daneshjooyar_panel_get_pending_ticket_count();
}
?>
<aside>
    <a href="<?php echo home_url();?>" class="site-logo">
        <img src="<?php echo daneshjooyar_panel_images( 'logo.svg' );?>" alt="لوگو" width="217" height="77">
    </a>
    <div class="user-info">
        <!-- <img src="<?php echo daneshjooyar_panel_images( 'hamedmoody.jpg' );?>" alt="حامد مودی" width="64" height="64"> -->
        <?php echo get_avatar( $user->user_email, 64, '', $full_name );?>
        <div>
            <strong><?php echo $full_name;?></strong>
            <span>@<?php echo $user->user_login;?></span>
        </div>
        <a href="<?php echo daneshjooyar_panel_url( 'profile' );?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16.834 16.834">
                <g id="user-edit" transform="translate(-492 -188)">
                    <path id="Vector" d="M4.033,8.066A4.033,4.033,0,1,1,8.066,4.033,4.038,4.038,0,0,1,4.033,8.066Zm0-7.014A2.981,2.981,0,1,0,7.014,4.033,2.988,2.988,0,0,0,4.033,1.052Z" transform="translate(496.384 188.877)" fill="#292d32"/>
                    <path id="Vector-2" data-name="Vector" d="M.98,5.842a.986.986,0,0,1-.7-.281A1,1,0,0,1,.012,4.7l.133-.947A1.416,1.416,0,0,1,.5,3.037L2.986.554a1.479,1.479,0,0,1,2.3,0A1.65,1.65,0,0,1,5.841,1.7a1.607,1.607,0,0,1-.554,1.143L2.8,5.33a1.389,1.389,0,0,1-.715.358l-.947.133A.637.637,0,0,1,.98,5.842ZM4.13,1.052c-.126,0-.238.084-.4.246L1.247,3.78a.267.267,0,0,0-.056.119l-.126.877.877-.126a.39.39,0,0,0,.119-.063L4.544,2.1a.728.728,0,0,0,.245-.4A.664.664,0,0,0,4.544,1.3C4.375,1.129,4.249,1.052,4.13,1.052Z" transform="translate(502.116 198.115)" fill="#292d32"/>
                    <path id="Vector-3" data-name="Vector" d="M2.081,2.607a.452.452,0,0,1-.14-.021A2.79,2.79,0,0,1,.019.665.524.524,0,0,1,1.029.384,1.729,1.729,0,0,0,2.222,1.576a.525.525,0,0,1-.14,1.031Z" transform="translate(504.592 198.874)" fill="#292d32"/>
                    <path id="Vector-4" data-name="Vector" d="M.526,5.962A.53.53,0,0,1,0,5.436C0,2.441,2.939,0,6.551,0A7.868,7.868,0,0,1,8.782.323a.524.524,0,1,1-.3,1,6.788,6.788,0,0,0-1.929-.281c-3.03,0-5.5,1.964-5.5,4.384A.531.531,0,0,1,.526,5.962Z" transform="translate(493.866 197.995)" fill="#292d32"/>
                    <path id="Vector-5" data-name="Vector" d="M0,0H16.834V16.834H0Z" transform="translate(492 188)" fill="none" opacity="0"/>
                </g>
            </svg>
        </a>
    </div><!--.user-info-->
    <p>منوی اصلی</p>
    <ul class="main-menu">
        <li <?php echo $panel_page == 'dashboard' ? 'class="open"' : '';?>>
            <a href="<?php echo daneshjooyar_panel_url( 'dashboard' );?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g id="element-4" transform="translate(-684 -252)">
                        <path id="Vector" d="M7.02,12.5H2.98C.97,12.5,0,11.57,0,9.65V2.85C0,.93.98,0,2.98,0H7.02C9.03,0,10,.93,10,2.85v6.8C10,11.57,9.02,12.5,7.02,12.5ZM2.98,1.5c-1.27,0-1.48.34-1.48,1.35v6.8c0,1.01.21,1.35,1.48,1.35H7.02c1.27,0,1.48-.34,1.48-1.35V2.85c0-1.01-.21-1.35-1.48-1.35Z" transform="translate(696.75 253.25)" fill="#484848"/>
                        <path id="Vector-2" data-name="Vector" d="M7.02,7.5H2.98C.97,7.5,0,6.57,0,4.65V2.85C0,.93.98,0,2.98,0H7.02C9.03,0,10,.93,10,2.85v1.8C10,6.57,9.02,7.5,7.02,7.5Zm-4.04-6c-1.27,0-1.48.34-1.48,1.35v1.8C1.5,5.66,1.71,6,2.98,6H7.02C8.29,6,8.5,5.66,8.5,4.65V2.85c0-1.01-.21-1.35-1.48-1.35Z" transform="translate(696.75 267.25)" fill="#484848"/>
                        <path id="Vector-3" data-name="Vector" d="M7.02,12.5H2.98C.97,12.5,0,11.57,0,9.65V2.85C0,.93.98,0,2.98,0H7.02C9.03,0,10,.93,10,2.85v6.8C10,11.57,9.02,12.5,7.02,12.5ZM2.98,1.5c-1.27,0-1.48.34-1.48,1.35v6.8c0,1.01.21,1.35,1.48,1.35H7.02c1.27,0,1.48-.34,1.48-1.35V2.85c0-1.01-.21-1.35-1.48-1.35Z" transform="translate(685.25 262.25)" fill="#484848"/>
                        <path id="Vector-4" data-name="Vector" d="M7.02,7.5H2.98C.97,7.5,0,6.57,0,4.65V2.85C0,.93.98,0,2.98,0H7.02C9.03,0,10,.93,10,2.85v1.8C10,6.57,9.02,7.5,7.02,7.5Zm-4.04-6c-1.27,0-1.48.34-1.48,1.35v1.8C1.5,5.66,1.71,6,2.98,6H7.02C8.29,6,8.5,5.66,8.5,4.65V2.85c0-1.01-.21-1.35-1.48-1.35Z" transform="translate(685.25 253.25)" fill="#484848"/>
                        <path id="Vector-5" data-name="Vector" d="M0,0H24V24H0Z" transform="translate(684 252)" fill="none" opacity="0"/>
                    </g>
                </svg>
                <span>پیشخان</span>
            </a>
        </li>
        <li <?php echo $panel_page == 'profile' ? 'class="open"' : '';?>>
            <a href="<?php echo daneshjooyar_panel_url( 'profile' );?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g id="user" transform="translate(-108 -188)">
                        <path id="Vector" d="M5.75,11.5A5.75,5.75,0,1,1,11.5,5.75,5.757,5.757,0,0,1,5.75,11.5Zm0-10A4.25,4.25,0,1,0,10,5.75,4.259,4.259,0,0,0,5.75,1.5Z" transform="translate(114.25 189.25)" fill="#474747"/>
                        <path id="Vector-2" data-name="Vector" d="M17.93,8.5a.755.755,0,0,1-.75-.75c0-3.45-3.52-6.25-7.84-6.25S1.5,4.3,1.5,7.75a.755.755,0,0,1-.75.75A.755.755,0,0,1,0,7.75C0,3.48,4.19,0,9.34,0s9.34,3.48,9.34,7.75A.755.755,0,0,1,17.93,8.5Z" transform="translate(110.66 202.25)" fill="#474747"/>
                        <path id="Vector-3" data-name="Vector" d="M0,0H24V24H0Z" transform="translate(108 188)" fill="none" opacity="0"/>
                    </g>
                </svg>
                <span>پروفایل کاربری</span>
            </a>
        </li>
        <li <?php echo $panel_page == 'ticket' ? 'class="open"' : '';?>>
            <a href="<?php echo daneshjooyar_panel_url( 'ticket' );?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g id="headphone" transform="translate(-620 -188)">
                        <path id="Vector" d="M4.322,21.4A4.38,4.38,0,0,1,0,17.08V10.92A10.657,10.657,0,0,1,10.7,0,10.812,10.812,0,0,1,21.5,10.8v6.16a4.328,4.328,0,0,1-4.32,4.32,4.38,4.38,0,0,1-4.32-4.32V14.15a2.59,2.59,0,0,1,5.18,0v3.03a.75.75,0,0,1-1.5,0V14.15a1.09,1.09,0,0,0-2.18,0v2.81a2.82,2.82,0,0,0,5.64,0V10.8a9.308,9.308,0,0,0-9.3-9.3,9.155,9.155,0,0,0-9.2,9.4v6.18a2.856,2.856,0,0,0,2.82,2.82,2.856,2.856,0,0,0,2.82-2.82V14.27a1.09,1.09,0,1,0-2.18,0v2.92a.75.75,0,1,1-1.5,0V14.27a2.59,2.59,0,1,1,5.18,0v2.81A4.38,4.38,0,0,1,4.322,21.4Z" transform="translate(621.248 189.3)" fill="#292d32"/>
                        <path id="Vector-2" data-name="Vector" d="M0,0H24V24H0Z" transform="translate(620 188)" fill="none" opacity="0"/>
                    </g>
                </svg>
                <span>پشتیبانی</span>
                <?php if( $unread_message ):?>
                <span class="badge badge-danger"><?php echo $unread_message;?></span>
                <?php endif;?>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g id="arrow-down" transform="translate(-236 -252)">
                        <path id="Vector" d="M8.668,8.6a2.726,2.726,0,0,1-1.93-.8L.218,1.277A.75.75,0,0,1,1.278.218L7.8,6.737a1.231,1.231,0,0,0,1.74,0l6.52-6.52a.75.75,0,0,1,1.06,1.06L10.6,7.8A2.726,2.726,0,0,1,8.668,8.6Z" transform="translate(239.333 260.203)" fill="#292d32"/>
                        <path id="Vector-2" data-name="Vector" d="M0,0H24V24H0Z" transform="translate(236 252)" fill="none" opacity="0"/>
                    </g>
                </svg>
            </a>
            <ul <?php echo $panel_page != 'ticket' ? 'style="display: none;"' : '';?>>
                <li>
                    <a href="<?php echo daneshjooyar_panel_url( 'ticket' );?>" <?php echo $panel_page == 'ticket' && ! $panel_action ? 'class="current"' : '';?>>لیست تیکت ها</a>
                </li>
                <li>
                    <a href="<?php echo daneshjooyar_panel_url( 'ticket/new' );?>" <?php echo $panel_page == 'ticket' && $panel_action == 'new' ? 'class="current"' : '';?>>+ تیکت جدید</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="<?php echo wp_nonce_url( daneshjooyar_panel_url( 'login?logout=true' ), 'daneshjooyar-logout' );?>" onclick="return confirm('برای خروج از پنل مطمئنید؟');">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g id="logout" transform="translate(-364 -444)">
                        <path id="Vector" d="M.748,6.617a.742.742,0,0,1-.53-.22.754.754,0,0,1,0-1.06l2.03-2.03L.218,1.277A.75.75,0,0,1,1.277.218l2.56,2.56a.754.754,0,0,1,0,1.06L1.277,6.4A.742.742,0,0,1,.748,6.617Z" transform="translate(380.693 452.753)" fill="#292d32"/>
                        <path id="Vector-2" data-name="Vector" d="M10.92,1.5H.75A.755.755,0,0,1,0,.75.755.755,0,0,1,.75,0H10.92a.755.755,0,0,1,.75.75A.755.755,0,0,1,10.92,1.5Z" transform="translate(373.01 455.31)" fill="#292d32"/>
                        <path id="Vector-3" data-name="Vector" d="M8.75,17.5A8.374,8.374,0,0,1,0,8.75,8.374,8.374,0,0,1,8.75,0,.755.755,0,0,1,9.5.75a.755.755,0,0,1-.75.75A6.935,6.935,0,0,0,1.5,8.75,6.935,6.935,0,0,0,8.75,16a.75.75,0,0,1,0,1.5Z" transform="translate(367.01 447.25)" fill="#292d32"/>
                        <path id="Vector-4" data-name="Vector" d="M0,0H24V24H0Z" transform="translate(364 444)" fill="none" opacity="0"/>
                    </g>
                </svg>
                <span>خروج از پنل</span>
            </a>
        </li>
    </ul>
</aside>