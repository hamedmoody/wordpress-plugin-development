jQuery(document).ready(function( $ ){
    

    $('#dcs_course_has_discount').change(function(e){

        if( $(this).is(':checked') ){
            $(this).closest('table').addClass('dcs_has_discount');
        }else{
            $(this).closest('table').removeClass('dcs_has_discount');
        }

    });

    $('#dcs_demo_uploader').click(function(){

        if( typeof demo_uploader !== 'undefined' ){
            demo_uploader.open();
            return;
        }

        demo_uploader = wp.media({
            title   : dcs.i18n.demo_uploader_title,
            library : {
                type : ['video'],
            }
        });

        demo_uploader.on( 'select', function(){
            let selected = demo_uploader.state().get('selection');
            $('#dcs_course_demo').val( selected.first().toJSON().url ).change();
        } );

        demo_uploader.open();

    });

    $('#dcs_course_teacher').select2();

    if( $('#dcs_course_discount_expire').length ){
        let datetime    = $('#dcs_course_discount_expire').val();
        if( datetime.length > 5 ){
            let slices      = datetime.split( ' ' );
            let jalali   = moment( slices[0], 'YYYY-MM-DD').format( 'jYYYY/jMM/jDD' ) + ' ' + slices[1];
            $('.dcs_course_discount_expire_jalali').val( jalali );
        }
    }

    jalaliDatepicker.startWatch({
        time: true,
    });

    $('.dcs_course_discount_expire_jalali').change(function(){
        //1402/07/26 18:17:00
        let datetime    = $(this).val();
        let gregorian   = '';
        if( datetime.length > 5 ){
            let slices  = datetime.split( ' ' );
            gregorian   = moment( slices[0], 'jYYYY/jMM/jDD').format( 'YYYY-MM-DD' ) + ' ' + slices[1];
        }
        $('#dcs_course_discount_expire').val( gregorian );
    });

    $('.dcs_playlist_table tbody').sortable({
        handle  : '.dashicons-move',
        stop    : reindex_playlist
    });

    function check_no_items(){
        if( $('.dcs_playlist_table tbody tr:not(.dcs_no_item)').length ){
            $('.dcs_no_item').remove();
        }else{
            $('.dcs_playlist_table tbody').html( $('#tp_no_item').html() );
        }
    }
    
    function reindex_playlist(){
        $('.dcs_playlist_table tbody tr td:nth-child(2)').each(function( index ){
            $(this).text( index + 1 );
        });
    }

    $(document).on( 'click', '.dcs_playlist_table .dashicons-trash', function(){
        if( confirm( dcs.i18n.sure_delete ) ){
            $(this).closest('tr').addClass('deleting').slideUp( 1500, function(){
                $(this).remove();
                check_no_items();
                reindex_playlist();
            } );
        }
    } );

    $('.dcs_add_playlist_item').click(function(){
        let html = $('#tp_item').html();
        $(html).appendTo( '.dcs_playlist_table tbody' );
        check_no_items();
        reindex_playlist();
    });

    function calculate_video_data( video_url, on_calculate, on_error ){

        let video = $('<video/>', {
            id          : 'dcs_video',
            src         : video_url,
            type        : 'video/mp4',
            controls    : true,
            preload     : true,
        });

        $(video).get(0).load();

        $(video).on( 'loadedmetadata', function(){

            let duration    = parseInt( this.duration );
            let width       = parseInt( this.videoWidth );
            let height      = parseInt( this.videoHeight );
            
            $(this).remove();

            on_calculate( duration, width, height );

        } );

        $(video).on('error', function(){
            $(this).remove();
            on_error();
        });

    }

    function formatTime(seconds) {
        return [
            parseInt(seconds / 60 / 60),
            parseInt(seconds / 60 % 60),
            parseInt(seconds % 60)
        ]
            .join(":")
            .replace(/\b(\d)\b/g, "0$1")
    }

    $(document).on('change', '.dcs_playlist_table input[type="url"]', function(){

        let el_duration = $(this).closest('tr').find('.dcs_duration');
        let tr          = $(this).closest('tr');

        let url = $(this).val().trim();
        let ext = url.split('.').pop();
        if( ['mp4', 'm4v', 'webm'].indexOf( ext ) >= 0 ){
            $(tr).addClass('calculating');
            calculate_video_data( url, function( duration, width, height ){
                //console.log(width, duration, height);
                $(tr).find('.dcs_item_width').val( width );
                $(tr).find('.dcs_item_height').val( height );
                $(tr).find('.dcs_item_duration').val( duration );
                $(el_duration).text( formatTime( duration ) );
                $(tr).removeClass('calculating');
            },
            function(){

            } );
        }else{
            $(el_duration).text( '--:--' );
        }

    });

});