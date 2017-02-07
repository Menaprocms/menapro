/**
 * Created by Silvia on 01/03/2016.
 */
$(document).ready(function () {

    $('#send_button').click(function(e){
        var name=$('#name').val().trim();
        var email=$('#email').val().trim();
        var message=$('#message').val().trim();
        var captcha=$('input[name=captcha]').val();
        var data={};
        $.extend(true, data, {'block':'contactform','action':'sendmail','name':name,'email':email,'message':message, 'captcha':captcha,'_csrf':csrfToken,'menacsrf':csrfToken});
        sendmail(data);
    });

});
function sendmail(data){

    $('.send_alert').addClass('hidden');
    $('input[name="captcha"]').removeClass('error');

    if(data.name!='' && data.email!='' && data.message !=''){
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: baseDir + '/block/block.html',
            async: true,
            cache: false,
            headers: {"cache-control": "no-cache"},
            data:data,
            beforeSend: function (a, b, c) {

            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#send_fail').removeClass('hidden');
            },
            success: function (data) {
                if(data.success){
                    $('#send_ok').removeClass('hidden');
                }else{
                    if(typeof(data.captcha)!='undefined' && !data.captcha){
                        $('#captcha_error').removeClass('hidden');
                        $('input[name="captcha"]').addClass('error').closest('div').find('img').click();

                    }else{
                        $('#send_fail').removeClass('hidden');
                    }
                }
            }
        });
    }else{
        $('#send_invalid').removeClass('hidden');
    }

}
