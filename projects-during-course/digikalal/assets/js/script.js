jQuery(document).ready(function ($) {
    $(document).on('click', '.dk-thumbnails img', function () {
        $('.dk-thumbnail img').attr('src', $(this).attr('src'));
    })
    $(window).scroll(load_digikala_product);

    function load_digikala_product() {
        let offset = 300;
        let start_of_view = $(window).scrollTop() - offset;
        let end_of_view = $(window).scrollTop() + $(window).height() + offset;
        $('.dk-preloader:not(.active)').each(function (index) {
            let distance = $(this).offset().top + $(this).outerHeight() / 2;
            if (distance >= start_of_view && distance <= end_of_view) {
                $(this).addClass('active');
                let _this = $(this);
                $.ajax({
                    url: _dk.ajax_url,
                    data: {
                        action: 'wp_dk_get_product',
                        id: $(this).data('dk'),

                    },
                    success: function (result) {
                        $(_this).replaceWith(result.html);

                    }

                });
            }
        })
    }

    load_digikala_product();

})