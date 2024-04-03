jQuery(document).ready(function($){
    
    $('#ranking').change(function(){
        $('#ranking2').val( $(this).val() );
    });

    $('#ranking2').change(function(){
        $('#ranking').val( $(this).val() );
    });

    $('#filter_rank2').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        $('#filter_rank').trigger('click');
    });

});