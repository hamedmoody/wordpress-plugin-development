<?php
$redirect = isset( $_GET['redirect'] ) ? $_GET['redirect']  : '';
?>
<?php daneshjooyar_panel_header();?>
    <div class="login-container">
        <div class="login-right">
            <a href="#" class="site-logo">
                <img src="<?php echo daneshjooyar_panel_images( 'logo.svg') ;?>" alt="لوگو" width="217" height="77">
            </a>
            <h1>ورود به سایت</h1>
            <form action="" id="login-form">
                <div class="form-group">
                    <label for="user_login">نام کاربری یا ایمیل:</label>
                    <input type="text" class="form-control" placeholder="نام کاربری یا ایمیل" id="user_login" name="user_login" required>
                </div>
                <div class="form-group">
                    <div class="panel-password-label">
                        <label for="user_password">گذرواژه:</label>
                        <a href="#">رمزم یادم نیست!</a>
                    </div>
                    <input type="password" class="form-control" id="user_password" name="user_password" required>
                </div>
                <div class="form-group remember-group">
                    <input type="checkbox" class="form-control" id="rememeber" name="rememeber">
                    <label for="rememeber">مرا به خاطر بسپر</label>
                </div>
                <div class="form-group">
                    <input type="hidden" name="action" value="daneshjooyar_panel_login">
                    <input type="hidden" name="redirect" value="<?php echo esc_url( $redirect );?>">
                    <?php wp_nonce_field( 'daneshjooyar_panel_login' );?>
                    <button class="btn btn-block btn-primary">
                        ورود به سایت
                    </button>
                </div>
            </form>
            <div class="panel-offer-signup">
                تا کنون ثبت نام نکرده اید؟
                <a href="<?php echo daneshjooyar_panel_url( 'signup' );?>">ثبت نام کنید</a>
            </div>
        </div>
        <img src="<?php echo daneshjooyar_panel_images( 'login-banner.png' );?>" alt="پنل کاربری" width="566" height="566">
    </div>
<?php daneshjooyar_panel_footer();?>