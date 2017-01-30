$(document).ready(function () {

    if (typeof $().bxSlider == "function")
        $(".eSliderBx").bxSlider();

    $('#close_cookies_notification').click(function(e){
        $('#cookies_notification').hide();
    });

});
