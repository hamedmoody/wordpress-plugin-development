jQuery(document).ready( function( $ ){

    /**
     * https://github.com/CodeSeven/toastr
     */
    toastr.options.positionClass    = 'toast-top-center';
    toastr.options.progressBar      = true;
    toastr.options.rtl              = true;

    $('#login-form').submit(function(e){
        
        e.preventDefault();

        let data    = $(this).serialize();
        let btn     = $(this).find('button');
        let _this   = $(this);

        if( $(btn).hasClass('loading') ){
            return;
        }

        $.ajax({
            url     : panel.ajax_url,
            data    : data,
            type    : 'POST',
            beforeSend: function(){
                $(btn).addClass('loading');
            },
            complete    : function(){
                $(btn).removeClass('loading');
            },
            success     : function( result ){

                toastr.success( result.data.message, 'ورود موفق' );

                if( result.data.redirect !== undefined ){
                    window.location.href = result.data.redirect;
                }

            },
            error: function( xhr, status, http_error_description ){

                let message = xhr.responseJSON !== undefined ? xhr.responseJSON.data.message : false;

                if( ! message && http_error_description !== undefined ){
                    message = http_error_description;
                }

                toastr.error( message, 'خطا!' );

            },
        });


    });

    function check_password_strong( password ){

        let uppercase       = new RegExp( '[A-Z]' );
        let lowercase       = new RegExp( '[a-z]' );
        let numbers         = new RegExp( '[0-9]' );
        let specials        = new RegExp( '([!,@,#,,$,%,^])' );

        let uppercase_score     = password.match( uppercase ) ? 1 : 0;
        let lowercase_score     = password.match( lowercase ) ? 1 : 0;
        let numbers_score       = password.match( numbers ) ? 1 : 0;
        let specials_score      = password.match( specials ) ? 1 : 0;
        //let min_length_score    = password.length >= 8 ? 1 : 0;

        let score               = uppercase_score + lowercase_score + numbers_score + specials_score;

        if( password.length < 8 ){
            score = 0;
        }

        let strongs             = [
            'weak', 'normal', 'good', 'strong'
        ];

        $('.password-strong-meter')
        .removeClass('weak normal good strong')
        .addClass( strongs[score-1] );

        return score;


    }

    $('#signup-form #user_password').keyup(function(){
        
        let score = check_password_strong( $(this).val() );

        let email_valid = true;
        let user_valid  = true;
        
        $('#btn-signup').attr('disabled', email_valid && user_valid && score > 2 ? false : true );
        
    });

    $('#signup-form').submit(function(e){
        
        e.preventDefault();

        let data    = $(this).serialize();
        let btn     = $(this).find('button');
        let _this   = $(this);

        if( $(btn).hasClass('loading') ){
            return;
        }

        $.ajax({
            url     : panel.ajax_url,
            data    : data,
            type    : 'POST',
            beforeSend: function(){
                $(btn).addClass('loading');
                $('.form-group.error').removeClass('error');
            },
            complete    : function(){
                $(btn).removeClass('loading');
            },
            success     : function( result ){

                toastr.success( result.data.message, 'ثبت نام موفق' );

                if( result.data.redirect !== undefined ){
                    window.location.href = result.data.redirect;
                }

            },
            error: function( xhr, status, http_error_description ){

                let message = xhr.responseJSON !== undefined ? xhr.responseJSON.data.message : false;

                let field_errors =  xhr.responseJSON.data.fields;
                for( let i = 0; i < field_errors.length; i++ ){
                    let field_error = field_errors[i];
                    let field       = $(_this).find("input[name='" + field_error.name + "']");
                    $(field).next().text(field_error.error).parent().addClass('error');
                }

                if( ! message && http_error_description !== undefined ){
                    message = http_error_description;
                }

                toastr.error( message, 'خطا!' );

            },
        });


    });

    if( $('body').hasClass('panel-login') ){
        let urlParams       = new URLSearchParams( window.location.search );
        let is_logged_out   = urlParams.get('logged_out');
        if( is_logged_out ){
            toastr.success( 'شما با موفقیت خارج شدید' );
        }
    }

    $("input#avatar").change(function(e){
        
        let avatar      = e.target.files[0];
        let avatar_reader = new FileReader();

        avatar_reader.onload = function(){
            console.log( avatar_reader.result);
            $('.avatar-uploader img')
            .attr( 'src', avatar_reader.result )
            .attr( 'srcset', avatar_reader.result )
            ;
        }

        avatar_reader.readAsDataURL( avatar );

    });


    $('#edit-profile').submit(function(e){
        
        e.preventDefault();

        let data    = new FormData( document.querySelector('#edit-profile') );
        let btn     = $(this).find('button');
        let _this   = $(this);

        if( $(btn).hasClass('loading') ){
            return;
        }

        $.ajax({
            url             : panel.ajax_url,
            data            : data,
            type            : 'POST',
            contentType     : false,
            processData    : false,
            beforeSend      : function(){
                $(btn).addClass('loading');
                $('.form-group.error').removeClass('error');
            },
            complete    : function(){
                $(btn).removeClass('loading');
            },
            success     : function( result ){

                toastr.success( result.data.message );

            },
            error: function( xhr, status, http_error_description ){

                let message = xhr.responseJSON !== undefined ? xhr.responseJSON.data.message : false;

                if( ! message && http_error_description !== undefined ){
                    message = http_error_description;
                }

                toastr.error( message, 'خطا!' );

            },
        });


    });


    $('.eye-slash').click(function(){
        console.log('lkjlkjlk');
        $(this).parent().toggleClass('hide-password');
        let t = $("#new_password").attr('type') == 'password' ? 'text' : 'password';
        $("#new_password").attr('type', t );
    });

    $('#new_password').keyup(function(){
        
        let score       = check_password_strong( $(this).val() );
        
        $('#btn-change-pass').attr('disabled', score > 2 ? false : true );
        
    });

    $('#change-password').submit(function(e){
        
        e.preventDefault();

        let data    = $(this).serialize();
        let btn     = $(this).find('button');
        let _this   = $(this);

        if( $(btn).hasClass('loading') ){
            return;
        }

        $.ajax({
            url             : panel.ajax_url,
            data            : data,
            type            : 'POST',
            beforeSend      : function(){
                $(btn).addClass('loading');
            },
            complete    : function(){
                $(btn).removeClass('loading');
            },
            success     : function( result ){
                toastr.success( result.data.message );
            },
            error: function( xhr, status, http_error_description ){

                let message = xhr.responseJSON !== undefined ? xhr.responseJSON.data.message : false;

                if( ! message && http_error_description !== undefined ){
                    message = http_error_description;
                }

                toastr.error( message, 'خطا!' );

            },
        });


    });

    $('.main-menu li a').click(function(e){

        if( $(this).next().length ){
            e.preventDefault();
            $(this).next().slideToggle(500);
        }

    });

    $('#new-ticket').submit(function(e){
        
        e.preventDefault();

        let data    = $(this).serialize();
        let btn     = $(this).find('button');
        let _this   = $(this);

        if( $(btn).hasClass('loading') ){
            return;
        }

        $.ajax({
            url             : panel.ajax_url,
            data            : data,
            type            : 'POST',
            beforeSend      : function(){
                $(btn).addClass('loading');
            },
            complete    : function(){
                $(btn).removeClass('loading');
            },
            success     : function( result ){
                toastr.success( result.data.message );
                if( result.data.reload ){
                    //reload
                    window.location.reload();
                }else if( result.data.redirect ){
                    //redirect
                    window.location.href = result.data.redirect;
                }
            },
            error: function( xhr, status, http_error_description ){

                let message = xhr.responseJSON !== undefined ? xhr.responseJSON.data.message : false;

                if( ! message && http_error_description !== undefined ){
                    message = http_error_description;
                }

                toastr.error( message, 'خطا!' );

            },
        });


    });

    $('#close-ticket').click(function(e){

        e.preventDefault();

        if( ! confirm( 'برای بستن تیکت اطمینان دارید؟' ) ){
            return;
        }

        let data    = {
            action      : 'daneshjooyar_panel_change_ticket_status',
            ID          : $(this).data('id'),
            status      : 'close',
            _wpnonce    : $(this).data('nonce'),
        };

        let btn     = $(this);

        if( $(btn).hasClass('loading') ){
            return;
        }

        $.ajax({
            url             : panel.ajax_url,
            data            : data,
            type            : 'POST',
            beforeSend      : function(){
                $(btn).addClass('loading');
            },
            complete    : function(){
                $(btn).removeClass('loading');
            },
            success     : function( result ){
                toastr.success( result.data.message );
                window.location.reload();
            },
            error: function( xhr, status, http_error_description ){

                let message = xhr.responseJSON !== undefined ? xhr.responseJSON.data.message : false;

                if( ! message && http_error_description !== undefined ){
                    message = http_error_description;
                }

                toastr.error( message, 'خطا!' );

            },
        });

    });

    $('.ticket-reply').click(function(e){
        e.preventDefault();
        $('html, body').animate({
            scrollTop   : $('#content').offset().top - 100
        }, 500);
    });

    function byteConverter( bytes, decimals, only) {
        const K_UNIT = 1024;
        const SIZES = ["Bytes", "KB", "MB", "GB", "TB", "PB"];
    
        if(bytes== 0) return "0 Byte";
      
        if(only==="MB") return (bytes / (K_UNIT*K_UNIT)).toFixed(decimals) + " MB" ;
      
        let i = Math.floor(Math.log(bytes) / Math.log(K_UNIT));
        let resp = parseFloat((bytes / Math.pow(K_UNIT, i)).toFixed(decimals)) + " " + SIZES[i];
      
        return resp;

    }

    $('input#upload').change(function( e ){

        let file        = e.target.files[0];

        let size        = byteConverter( file.size, 2 );

        let form_data   = new FormData();
        form_data.append( 'action', 'daneshjooyar_panel_upload_file' );
        form_data.append( '_wpnonce', $(this).data('nonce') );
        form_data.append( 'attachemnt', file, file.name );

        let rand        = 'r' + Math.round( Math.random() * 10000 ) ;

        $.ajax({
            url             : panel.ajax_url,
            data            : form_data,
            type            : 'POST',
            cache           : false,
            contentType     : false,
            processData     : false,
            async           : true,
            beforeSend      : function(){
                
                let upload_progress = `<div class="upload uploading" id="${rand}">
                <div class="upload-row-top">
                  <div class="upload-file-name">
                    <svg class="upload-document" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                      <g id="document-text" transform="translate(-172 -188)">
                        <path id="Vector" d="M0,0H1" transform="translate(184 201)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        <path id="Vector-2" data-name="Vector" d="M0,0H2.45" transform="translate(179 201)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        <path id="Vector-3" data-name="Vector" d="M0,0H4" transform="translate(179 205)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        <path id="Vector-4" data-name="Vector" d="M0,7C0,2,2,0,7,0h5" transform="translate(174 190)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        <path id="Vector-5" data-name="Vector" d="M20,0V5c0,5-2,7-7,7H7c-5,0-7-2-7-7V2.98" transform="translate(174 198)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        <path id="Vector-6" data-name="Vector" d="M4,8C1,8,0,7,0,4V0L8,8" transform="translate(186 190)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        <path id="Vector-7" data-name="Vector" d="M0,0H24V24H0Z" transform="translate(172 188)" fill="none" opacity="0"/>
                      </g>
                    </svg>
                    <svg class="uploaded-document" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                      <g id="tick-square" transform="translate(-748 -252)">
                        <path id="Vector" d="M0,10.96V13c0,5,2,7,7,7h6c5,0,7-2,7-7V7c0-5-2-7-7-7H7C2,0,0,2,0,7" transform="translate(750 254)" fill="none" stroke="#2bd500" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        <path id="Vector-2" data-name="Vector" d="M0,1.13,1.12,0" transform="translate(763 261.25)" fill="none" stroke="#2bd500" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        <path id="Vector-3" data-name="Vector" d="M0,0,2.74,2.75,5.29.21" transform="translate(755.88 264)" fill="none" stroke="#2bd500" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        <path id="Vector-4" data-name="Vector" d="M0,0H24V24H0Z" transform="translate(748 252)" fill="none" opacity="0"/>
                      </g>
                    </svg>

                    <div class="file-name">
                      ${file.name}
                      <span>(${size})</span>
                    </div>
                    <p class="upload-percent">
                      0%
                    </p>
                  </div><!--.upload-file-name-->
                  <div class="upload-progress">
                    <div class="progress-current"></div>
                  </div><!--.upload-progress-->
                </div><!--.upload-row-top-->
                <svg class="remove-upload" xmlns="http://www.w3.org/2000/svg" width="27.84" height="27.84" viewBox="0 0 27.84 27.84">
                  <g id="close-square" transform="translate(-108 -316)">
                    <path id="Vector" d="M0,.974.974,0" transform="translate(124.229 326.637)" fill="none" stroke="#f03" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                    <path id="Vector-2" data-name="Vector" d="M0,3.19,3.19,0" transform="translate(118.637 330.013)" fill="none" stroke="#f03" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                    <path id="Vector-3" data-name="Vector" d="M6.566,6.566,0,0" transform="translate(118.637 326.637)" fill="none" stroke="#f03" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                    <path id="Vector-4" data-name="Vector" d="M0,12.714V15.08c0,5.8,2.32,8.12,8.12,8.12h6.96c5.8,0,8.12-2.32,8.12-8.12V8.12C23.2,2.32,20.88,0,15.08,0H8.12C2.32,0,0,2.32,0,8.12" transform="translate(110.32 318.32)" fill="none" stroke="#f03" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                    <path id="Vector-5" data-name="Vector" d="M0,0H27.84V27.84H0Z" transform="translate(108 316)" fill="none" opacity="0"/>
                  </g>
                </svg>
              </div><!--.upload-->`;

              $('.upload-files').append( upload_progress );

            },
            xhr         : function(){

                let my_xhr = $.ajaxSettings.xhr();
                if( my_xhr.upload ){

                    my_xhr.upload.addEventListener( 'progress', function(e){
                        
                        let position    = e.loaded || e.postion;
                        let total       = e.total;
                        
                        let percent     = Math.ceil( position / total * 100 );
                        
                        $(`#${rand} .upload-percent`).text( percent + '%' );
                        $(`#${rand} .progress-current`).css('width', percent + '%')                        ;

                    } );
                }

                return my_xhr;

            },
            complete    : function(){
                
            },
            success     : function( result ){

                $(`#${rand}`)
                .removeClass('uploading')
                .addClass('uploaded');

                toastr.success( result.data.message );
                $(`<input type="hidden" name="file[]" value="${result.data.url}"/>`).appendTo( `#${rand}` );

            },
            error: function( xhr, status, http_error_description ){

                let message = xhr.responseJSON !== undefined ? xhr.responseJSON.data.message : false;

                if( ! message && http_error_description !== undefined ){
                    message = http_error_description;
                }

                toastr.error( message, 'خطا!' );

            },
        });

    });

} );