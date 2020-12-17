/**
 * Creado por Xenon .
 * Autor: xnx
 * Date: 31/07/15
 * Time: 18:44
 */

var sliders = [];

$(document).ready(function () {


    $(".eFancyGallery").fancybox({
        openEffect: 'none',
        closeEffect: 'none',
        autoSize: false,
        width: '100%',
        height: 'auto',
        helpers: {
            overlay: {
                locked: false
            }
        }

    });

    $("#accept-cookies").click(function(e){
        console.log('click');
        e.preventDefault();
        let url = $(e.currentTarget).attr('href');
        // let csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            // data: {_csrf: csrfToken},
            success: function() {
                $("#cookies_notification").hide();
                location.reload();
            }
        });
    });

    // Keep the slider in a var so other themes can customize it

    $.each($('.eSliderBx'), function (k, v) {

        sliders.push($(v).bxSlider({
            useCSS: false,
            controls: false,
            auto: true,
            autoControls: false
        }))
    });

	$('#close_cookies_notification').click(function(e){
        $('#cookies_notification').hide();
    });

});
