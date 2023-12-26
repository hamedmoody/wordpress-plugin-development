<?php
$redirect = isset( $_GET['redirect'] ) ? $_GET['redirect']  : '';
?>
<?php daneshjooyar_panel_header();?>
    <div class="login-container">
        <div class="login-right">
            <a href="#" class="site-logo">
                <img src="<?php echo daneshjooyar_panel_images( 'logo.svg' );?>" alt="لوگو" width="217" height="77">
            </a>
            <h1>ثبت نام</h1>
            <form action="" id="signup-form">
                <div class="form-group">
                    <label for="user_login">نام کاربری:</label>
                    <input type="text" class="form-control" placeholder="نام کاربری" id="user_login" name="user_login" required>
                    <span class="error"></span>
                </div>
                <div class="form-group">
                    <label for="user_email">ایمیل:</label>
                    <input type="email" class="form-control" placeholder="ایمیل" id="user_email" name="user_email" required>
                    <span class="error"></span>
                </div>
                <div class="form-group">
                    <div class="panel-password-label">
                        <label for="user_password">گذرواژه:</label>
                        <div class="password-strong-meter">
                            قدرت:
                        </div>
                    </div>
                    <input type="text" class="form-control" id="user_password" name="user_password" required>
                </div>
                <div class="form-group">
                    <input type="hidden" name="action" value="daneshjooyar_panel_signup">
                    <input type="hidden" name="redirect" value="<?php echo esc_url( $redirect );?>">
                    <?php wp_nonce_field( 'daneshjooyar_panel_signup' );?>
                    <button class="btn btn-block btn-primary" id="btn-signup" disabled>
                        ثبت نام در سایت
                    </button>
                </div>
            </form>
            <div class="panel-offer-signup">
                حساب کاربری دارید؟
                <a href="<?php echo daneshjooyar_panel_url( 'login' );?>">وارد شوید</a>
            </div>
        </div>
    </div>
<?php daneshjooyar_panel_footer();?>