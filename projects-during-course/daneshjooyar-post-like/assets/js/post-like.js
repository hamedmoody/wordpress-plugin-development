jQuery(document).ready(function($){
    
    $('.like-post').click(function(){

        if( $(this).hasClass('loading') ){
            return;
        }

        let count   = $(this).find('.like-count');
        let btn      = $(this);
        let msg     = $(this).next();

        $.ajax({
            url     : dypl.ajax_url,
            type    : 'POST',
            timeout : 1000,
            data    : {
                action  : 'dypl_like',
                post_id : $(this).data('id'),
                like    : ! $(this).hasClass('post-liked'),
                _wpnonce : $(this).data('nonce')
            },
            beforeSend: function(){
                $(btn).addClass('loading');
                $(msg).removeClass('show error').text('');
            },
            complete: function(){
                $(btn).removeClass('loading');
            },
            success: function( result ){
                if( result.success ){
                    if( result.data.liked ){
                        $(btn).addClass('post-liked');
                    }else{
                        $(btn).removeClass('post-liked');
                    }
                    $(count).text( `(${result.data.count})` );
                }else{
                    
                }

                $(msg)
                .text( result.data.message )
                .addClass(result.success ? 'success' : 'error')
                .addClass('show')
                .delay(3000)
                .queue( function( next ){
                    $(this).removeClass('show error success')
                    next();
                } );

            },
            error: function( xhr, status, http_error_description ){

                let message = xhr.responseJSON !== undefined ? xhr.responseJSON.data.message : false;

                if( ! message && http_error_description !== undefined ){
                    message = http_error_description;
                }

                if( message !== undefined ){
                    $(msg)
                    .text( message )
                    .addClass( 'error')
                    .addClass('show')
                    .delay(3000)
                    .queue( function( next ){
                        $(this).removeClass('show error success')
                        next();
                    } );
                }
            },
        });

    });

});