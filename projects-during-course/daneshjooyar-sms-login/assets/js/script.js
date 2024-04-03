jQuery(document).ready(function($){
    
    var countdown = 1000;
    function coutndown_handle(){

        if( countdown == 0 ){
            $('.dsl-resend').addClass('active');
        }

        countdown--;

        set_time( countdown );

    }
    setInterval( coutndown_handle, 1000 );

    function set_time( countdown ){

        let remain  = countdown >= 0 ? countdown : 0;

        let minute  = Math.floor( remain / 60 );
        minute = minute < 10 ? '0' + minute : minute;

        let second  = remain % 60;
        second = second < 10 ? '0' + second : second;

        let time    = `${minute}:${second}`;

        $('.dsl-countdown').text( time );

    }

    $(document).on('focus', '.dsl-codes input', function(e){
        $(this).select();
    });

    $(document).on('input', '.dsl-codes input', function(e){
        let code = $(this).val().trim();
        if( code.length ){
            if( $(this).next() ){
                $(this).next().focus();
            }
            if( $(this).index() >= $('.dsl-codes input').length - 1 ){
                $('#dsl-verify').submit();
            }
        }
    });

    $('#dsl-edit-number').click( toggle_form );

    function toggle_form(){
        $('.dsl-modal').toggleClass('verify');
    }

    function close_modal(){
        $('.dsl-modal-container').removeClass( 'open' );
    }

    function open_modal(e){
        e.preventDefault();
        $('.dsl-modal-container').addClass( 'open' );
    }

    $('.dsl-close').click( close_modal );

    $('#dsl-login').submit(function(e){

        e.preventDefault();

        let data        = $(this).serialize();
        let _this       = $(this);
        let _message    = $(this).find('.dsl-message');
        let _btn        = $(this).find('button');
        let _resend     = $('.dsl-resend');

        $.ajax({
            type    : 'POST',
            url     : dsl.ajax_url,
            data    : data,
            beforeSend: function(){
                $(_this).addClass('loading');
                $(_message).removeClass('active');
                $(_btn).attr('disabled', true);
                $(_resend).text('در حال ارسال...').removeClass('active');
            },
            success     : function( result ){
                console.log( result );
                if( result.success ){
                    countdown = result.data.duration;
                    set_time( countdown );
                    $(".dsl-login-result").text( result.data.message );
                    $('.dsl-modal').addClass('verify');
                    $(".dsl-codes input").eq(0).focus();
                    
                    $("#dsl-verify input[name='phone']").val( result.data.phone );
                    $("#dsl-verify input[name='_wpnonce']").val( result.data._wpnonce );

                }else{

                }
            },
            complete    : function(){
                $(_this).removeClass('loading');
                $(_btn).attr('disabled', false );
                $(_resend).text('ارسال مجدد');
            },
            error       : function( xhr ){
                let result = xhr.responseJSON;
                $(_message).addClass('active').find('span').text( result.data.message );
            },
        });

    });

    $(document).on('click', 'a.dsl-resend.active', function(e){
        e.preventDefault();
        if( countdown <= 0 ){
            $('#dsl-login').submit();
        }
    });

    $(document).on('submit', '#dsl-verify', function(e){

        e.preventDefault();

        let code        = '';
        $(this).find('.dsl-codes input').each(function(){
            code+= $(this).val();
        });
        $("#dsl-verify input[name='code']").val( code );

        let data        = $(this).serialize();
        let _this       = $(this);
        let _message    = $(this).find('.dsl-message');
        let _message_s  = $(this).find('.dsl-success');
        let _btn        = $(this).find('button');

        $.ajax({
            type    : 'POST',
            url     : dsl.ajax_url,
            data    : data,
            beforeSend: function(){
                $(_this).addClass('loading');
                $(_btn).attr('disabled', true);
            },
            success     : function( result ){
                console.log( result );
                if( result.success ){
                    $(_message_s).text(result.data.message).slideDown(500);
                    location.reload();
                }else{

                }
            },
            complete    : function(){
                $(_this).removeClass('loading');
                $(_btn).attr('disabled', false );
            },
            error       : function( xhr ){
                let result = xhr.responseJSON;
                $(_message).addClass('active').find('span').text( result.data.message );
            },
        });

    });

    function process_otp( otp ){
            
        let code = otp.code;

        let code_array = code.split('');
        for( let i = 0; i < code_array.length; i++ ){
            $('.dsl-codes input').eq(i).val(code_array[i]);
        }

        $('#dsl-verify-code').val( code );
            
        $('#dsl-verify').submit();

    }

    //process_otp({code: '98656'});

    if ('OTPCredential' in window) {

        const ac = new AbortController();
        $('#dsl-verify').submit(function(e){
            ac.abort();
        });

        navigator.credentials.get({
            otp     : {transport: ['sms']},
            signal  : ac.signal
        }).then( process_otp );

    }

    $(document).on('click', "a[href*='wp-login.php']", open_modal );

});