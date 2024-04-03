<?php
$digits = 5;
?>
<div class="dsl-modal-container">
    <div class="dsl-modal">
        <button type="button" class="dsl-close">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15">
                <path id="Path_1" data-name="Path 1" d="M19.207,6.207a1,1,0,1,0-1.414-1.414L12,10.586,6.207,4.793A1,1,0,1,0,4.793,6.207L10.586,12,4.793,17.793a1,1,0,1,0,1.414,1.414L12,13.414l5.793,5.793a1,1,0,1,0,1.414-1.414L13.414,12Z" transform="translate(-4.5 -4.5)" fill="red" fill-rule="evenodd"/>
            </svg>
        </button>
        <form class="form" id="dsl-login">
            <h2>ورود/ثبت نام</h2>
            <p>
                برای راحتی شما ورود و ثبت نام را با شماره تلفن شما انجام دادیم
            </p>
            <div class="dsl-field">
                <label for="dsl-phone">شماره همراه خود را وارد کنید</label>
                <div class="dsl-cols">
                    <input type="text" inputmode="tel" placeholder="0 9 - - - - - - - - -" name="phone" required>
                    <span>+98</span>
                    <img src="<?php echo DANESHJOOYAR_SMS_LOGIN_IMAGES;?>iran.png" alt="iran" width="42" height="42">
                </div>
            </div>
            <p class="dsl-message">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                    <path id="Path_3" data-name="Path 3" d="M12,22A10,10,0,1,0,2,12,10,10,0,0,0,12,22Zm-1.5-5.009a1.5,1.5,0,0,1,3,0,1.5,1.5,0,1,1-3,0ZM11.172,6a.5.5,0,0,0-.5.522l.306,7a.5.5,0,0,0,.5.478h1.043a.5.5,0,0,0,.5-.478l.305-7a.5.5,0,0,0-.5-.522H11.172Z" transform="translate(-2 -2)" fill="#ff6363" fill-rule="evenodd"/>
                </svg>
                <span></span>
            </p>
            <input type="hidden" name="action" value="dsl_login" >
            <?php wp_nonce_field( 'dsl-login');?>
            <button>
                <svg width="36" height="36" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_d9Sa{transform-origin:center}.spinner_qQQY{animation:spinner_ZpfF 9s linear infinite}.spinner_pote{animation:spinner_ZpfF .75s linear infinite}@keyframes spinner_ZpfF{100%{transform:rotate(360deg)}}</style><path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,20a9,9,0,1,1,9-9A9,9,0,0,1,12,21Z"/><rect class="spinner_d9Sa spinner_qQQY" x="11" y="6" rx="1" width="2" height="7"/><rect class="spinner_d9Sa spinner_pote" x="11" y="11" rx="1" width="2" height="9"/></svg>
                ارسال کد یکبار مصرف
            </button>
        </form>

        <form class="form" id="dsl-verify">
            <h2>تأیید شماره همراه</h2>
            <p class="dsl-login-result">
                کد 5 رقمی ارسال شده به شماره 09156040160 را وارد کنید
            </p>
            <div class="dsl-codes">
                <?php foreach( range( 1, $digits ) as $index ):?>
                    <input type="text" maxlength="1">
                <?php endforeach;?>
            </div>
            <div class="dsl-no-receive">
                <p>کد را دریافت نکردید؟</p>
                <div>
                    <a href="#" class="dsl-resend">ارسال مجدد</a>
                    <span class="dsl-countdown">01:24</span>
                </div>
            </div>
            <p class="dsl-message">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                    <path id="Path_3" data-name="Path 3" d="M12,22A10,10,0,1,0,2,12,10,10,0,0,0,12,22Zm-1.5-5.009a1.5,1.5,0,0,1,3,0,1.5,1.5,0,1,1-3,0ZM11.172,6a.5.5,0,0,0-.5.522l.306,7a.5.5,0,0,0,.5.478h1.043a.5.5,0,0,0,.5-.478l.305-7a.5.5,0,0,0-.5-.522H11.172Z" transform="translate(-2 -2)" fill="#ff6363" fill-rule="evenodd"/>
                </svg>
                <span></span>
            </p>
            <div class="dsl-success" style="display: none;">ورود انجام شد</div>
            <div class="dsl-buttons">
                <input type="hidden" name="code" id="dsl-verify-code" value="">
                <input type="hidden" name="phone" value="">
                <input type="hidden" name="_wpnonce" value="">
                <input type="hidden" name="action" value="dsl_verify">
                <button class="dsl-btn dsl-btn-primary">
                    <svg width="36" height="36" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_d9Sa{transform-origin:center}.spinner_qQQY{animation:spinner_ZpfF 9s linear infinite}.spinner_pote{animation:spinner_ZpfF .75s linear infinite}@keyframes spinner_ZpfF{100%{transform:rotate(360deg)}}</style><path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,20a9,9,0,1,1,9-9A9,9,0,0,1,12,21Z"/><rect class="spinner_d9Sa spinner_qQQY" x="11" y="6" rx="1" width="2" height="7"/><rect class="spinner_d9Sa spinner_pote" x="11" y="11" rx="1" width="2" height="9"/></svg>
                    تأیید کد
                </button>
                <button type="button" class="dsl-btn dsl-btn-secondary" id="dsl-edit-number">
                    اصلاح شماره
                </button>
            </div>
        </form>
        
    </div>
</div>