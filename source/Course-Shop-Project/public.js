jQuery(document).ready(function($){

    const player = $('.dcs-player video')[0];

    player.addEventListener( 'play', on_play );
    player.addEventListener( 'pause', on_pause );

    function on_play(){
        player.controls = 'controls';
        $('.dcs-player').addClass('playing');
    }

    function on_pause(){
        player.removeAttribute( 'controls' );
        $('.dcs-player').removeClass('playing');
        let url = player.src;
        $(`.dcs-item[data-url="${url}"]`).removeClass('playing');
    }

    $(".dcs-play").click(function( e ){
        e.preventDefault();
        player.play();
    });

    $('.dcs-item[data-url]').click(function(){
        let url = $(this).data('url');
        if( ['mp4', 'm4v', 'webm'].indexOf( url.split('.').pop() ) >= 0 ){
            player.pause();
            player.src = url;
            player.load();
            player.play();
            $('.dcs-item.playing').removeClass('playing');
            $(this).addClass('playing');
        }
    });

});