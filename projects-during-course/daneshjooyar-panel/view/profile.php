<?php
defined('ABSPATH') || exit;
if( isset( $_GET['test_action'] ) ){
  do_action( 'daneshjooyar_panel_ticket_sent', 258 );
}
$user       = wp_get_current_user();
$full_name  = $user->display_name;
?>
<?php daneshjooyar_panel_header();?>
<div class="panel-container">
<?php daneshjooyar_panel_sidebar();?>
  <main>
    <div class="panel-title">
      <h1>پروفایل کاربری</h1>
    </div>
    <div class="panel-content">
      <div class="widget personal-info">
        <header>
          <svg id="user" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path id="Vector" d="M5.75,11.5A5.75,5.75,0,1,1,11.5,5.75,5.757,5.757,0,0,1,5.75,11.5Zm0-10A4.25,4.25,0,1,0,10,5.75,4.259,4.259,0,0,0,5.75,1.5Z" transform="translate(6.25 1.25)" fill="#292d32"/>
            <path id="Vector-2" data-name="Vector" d="M17.93,8.5a.755.755,0,0,1-.75-.75c0-3.45-3.52-6.25-7.84-6.25S1.5,4.3,1.5,7.75a.755.755,0,0,1-.75.75A.755.755,0,0,1,0,7.75C0,3.48,4.19,0,9.34,0s9.34,3.48,9.34,7.75A.755.755,0,0,1,17.93,8.5Z" transform="translate(2.66 14.25)" fill="#292d32"/>
            <path id="Vector-3" data-name="Vector" d="M0,0H24V24H0Z" fill="none" opacity="0"/>
          </svg>
          <h2>اطلاعات کاربری</h2>
        </header>
        <div class="widget-body">
          <form action="" id="edit-profile">
            <div class="personal-info-field">
              <div class="form-group">
                <label for="username">نام کاربری:</label>
                <input type="text" name="username" id="username" value="<?php echo esc_attr( $user->user_login );?>" placeholder="yourusername" class="form-control ltr align-left" disabled>
              </div>
              <div class="form-group">
                <label for="email">ایمیل:</label>
                <input type="email" name="email" id="email" value="<?php echo esc_attr( $user->user_email );?>" placeholder="youremail@example.com" class="form-control ltr align-left" required>
              </div>
              <div class="form-group">
                <label for="first_name">نام:</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $user->first_name );?>" placeholder="نام شما" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="last_name">نام خانوادگی:</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $user->last_name );?>" placeholder="نام خانوادگی شما" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="phone">تلفن همراه:</label>
                <input type="text" inputmode="numeric" name="phone" id="phone" value="<?php echo esc_attr( $user->phone );?>" placeholder="شماره همراه" class="form-control ltr">
              </div>
              <div class="form-group">
                <label for="notificator_token">توکن <a href="https://notificator.ir">Notificator</a>:</label>
                <input type="text" name="notificator_token" id="notificator_token" value="<?php echo esc_attr( $user->notificator_token );?>" placeholder="توکن نوتیفیکیتور" class="form-control ltr">
              </div>
              <div class="form-group">
                <input type="hidden" name="action" value="daneshjooyar_panel_edit_profile">
                <?php wp_nonce_field( 'daneshjooyar_panel_edit_profile' );?>
                <button class="btn btn-primary">ویرایش پروفایل کاربری</button>
              </div>
            </div>
            <div class="personal-avatar">
              <div class="avatar-uploader">
                <span class="avatar-upload-progress">
                  <span class="current-progress"></span>
                </span>
                <label for="avatar">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="54" height="54" viewBox="0 0 54 54">
                    <defs>
                      <filter id="Rectangle_34" x="0" y="0" width="54" height="54" filterUnits="userSpaceOnUse">
                        <feOffset dy="3" input="SourceAlpha"/>
                        <feGaussianBlur stdDeviation="3" result="blur"/>
                        <feFlood flood-opacity="0.161"/>
                        <feComposite operator="in" in2="blur"/>
                        <feComposite in="SourceGraphic"/>
                      </filter>
                    </defs>
                    <g id="camera" transform="translate(-349 -304)">
                      <g transform="matrix(1, 0, 0, 1, 349, 304)" filter="url(#Rectangle_34)">
                        <rect id="Rectangle_34-2" data-name="Rectangle 34" width="36" height="36" rx="5" transform="translate(9 6)" fill="#fff"/>
                      </g>
                      <path id="Vector" d="M.528,16.25A3.631,3.631,0,0,0,4.518,20H15a3.636,3.636,0,0,0,3.99-3.75l.52-8.26A3.753,3.753,0,0,0,15.758,4a1.643,1.643,0,0,1-1.45-.89l-.72-1.45A3.3,3.3,0,0,0,10.908,0H8.618a3.3,3.3,0,0,0-2.69,1.66l-.72,1.45A1.643,1.643,0,0,1,3.758,4,3.753,3.753,0,0,0,.008,7.99l.26,4.07" transform="translate(366.242 318)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-2" data-name="Vector" d="M0,0H24V24H0Z" transform="translate(364 316)" fill="none" opacity="0"/>
                      <path id="Vector-3" data-name="Vector" d="M0,0H3" transform="translate(374.5 324)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-4" data-name="Vector" d="M3.25,6.5A3.25,3.25,0,1,0,0,3.25,3.256,3.256,0,0,0,3.25,6.5Z" transform="translate(372.75 327.5)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                    </g>
                  </svg>
                </label>
                <input type="file" name="avatar" id="avatar" accept="image/png, image/jpeg">
                <!-- <img src="assets/images/hamedmoody.jpg" alt="حامد مودی" width="220" height="220"> -->
                <?php echo get_avatar( $user->user_email, 220, '', $full_name );?>
                <p>512 x 512</p>
              </div>
            </div>

          </form>
        </div>
      </div><!--.personal-info-->
      <div class="widget change-password">
        <header>
          <svg id="lock" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path id="Vector" d="M0,8V6C0,2.69,1,0,6,0s6,2.69,6,6V8" transform="translate(6 2)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
            <path id="Vector-2" data-name="Vector" d="M0,0H24V24H0Z" fill="none" opacity="0"/>
            <path id="Vector-3" data-name="Vector" d="M20,7V5c0-4-1-5-5-5H5C1,0,0,1,0,5V7c0,4,1,5,5,5H15a6.372,6.372,0,0,0,3.71-.75" transform="translate(2 10)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
            <path id="Vector-4" data-name="Vector" d="M.495.5H.5" transform="translate(15.502 15.5)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            <path id="Vector-5" data-name="Vector" d="M.495.5H.5" transform="translate(11.501 15.5)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            <path id="Vector-6" data-name="Vector" d="M.495.5H.5" transform="translate(7.5 15.5)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
          </svg>
          <h2>تغییر گذرواژه</h2>
        </header>
        <div class="widget-body">
          <form action="" id="change-password">

              <div class="change-password-fields">
                <div class="form-group">
                  <label for="password">گذرواژه فعلی:</label>
                  <input type="password" name="password" id="password" placeholder="گذرواژه فعلی شما" class="form-control align-center" required>
                </div>
                <div class="form-group form-group-password">
                  <svg class="eye-slash" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g id="eye-slash" transform="translate(-172 -188)">
                      <path id="Vector" d="M6.11,1.05,1.05,6.11A3.578,3.578,0,1,1,6.11,1.05Z" transform="translate(180.42 196.42)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-2" data-name="Vector" d="M3.385,12.63A14.363,14.363,0,0,1,.675,9.46a5.326,5.326,0,0,1,0-5.19A13.174,13.174,0,0,1,4.9,0" transform="translate(174.215 193.13)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-3" data-name="Vector" d="M5.82,2.04A9.631,9.631,0,0,0,0,0" transform="translate(184 191.73)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-4" data-name="Vector" d="M0,11.6a9.215,9.215,0,0,0,3.58.74c3.53,0,6.82-2.08,9.11-5.68a5.326,5.326,0,0,0,0-5.19A16.222,16.222,0,0,0,11.63,0" transform="translate(180.42 195.93)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-5" data-name="Vector" d="M2.82,0A3.565,3.565,0,0,1,0,2.82" transform="translate(184.69 200.7)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-6" data-name="Vector" d="M7.47,0,0,7.47" transform="translate(174 202.53)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-7" data-name="Vector" d="M7.47,0,0,7.47" transform="translate(186.53 190)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-8" data-name="Vector" d="M0,0H24V24H0Z" transform="translate(172 188)" fill="none" opacity="0"/>
                    </g>
                  </svg>
                  <svg class="eye" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g id="eye" transform="translate(-108 -188)">
                      <path id="Vector" d="M.61,5.58a3.527,3.527,0,0,1-.61-2A3.58,3.58,0,1,1,3.58,7.16" transform="translate(116.42 196.42)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-2" data-name="Vector" d="M15.345,1.85A9.556,9.556,0,0,0,9.785,0C6.255,0,2.965,2.08.675,5.68a5.326,5.326,0,0,0,0,5.19c2.29,3.6,5.58,5.68,9.11,5.68s6.82-2.08,9.11-5.68a5.326,5.326,0,0,0,0-5.19" transform="translate(110.215 191.73)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                      <path id="Vector-3" data-name="Vector" d="M0,0H24V24H0Z" transform="translate(108 188)" fill="none" opacity="0"/>
                    </g>
                  </svg>
                  <label for="new_password">گذرواژه جدید:</label>
                  <input type="text" name="new_password" id="new_password" placeholder="گذرواژه جدید شما" class="form-control align-center" required>
                </div>
                <div class="form-group">
                  <label for="first_name">قدرت گذرواژه جدید:</label>
                  <div class="password-strong-meter">
                    قدرت:
                  </div>
                </div>
              </div>

              <div class="form-group">
                <input type="hidden" name="action" value="daneshjooyar_panel_change_password">
                <?php wp_nonce_field( 'daneshjooyar_panel_change_password' );?>
                <button class="btn btn-primary" disabled id="btn-change-pass">تغییر گذرواژه</button>
              </div>

          </form>
        </div>
      </div><!--.personal-info-->
    </div><!--.panel-content-->
    </div><!--.panel-content-->
  </main>
</div>
<?php daneshjooyar_panel_footer();?>