/**
 * Created by Silvia on 01/03/2016.
 */
$(document).ready(function () {

    $('#acf_send_button').click(function(e){
        var name=$('#acf_name').val().trim();
        var email=$('#acf_email').val().trim();
        var message=$('#acf_message').val().trim();
        var captcha=$('input[name=captcha]').val();
        var data={};
        $.extend(true, data, {'block':'advancedcontactform','action':'sendmail','name':name,'email':email,'message':message, 'captcha':captcha,'_csrf':csrfToken,'menacsrf':csrfToken});
        acfsendmail(data);
    });

});
function acfsendmail(data){

    $('.send_alert').addClass('hidden');
    $('input[name="acf_captcha"]').removeClass('error');

    if(data.name!='' && data.email!='' && data.message !=''){
        if($('#acf_accept_conditions').is(':checked')) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: baseDir + '/block/block.html',
                async: true,
                cache: false,
                headers: {"cache-control": "no-cache"},
                data: data,
                beforeSend: function (a, b, c) {

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#acf_send_fail').removeClass('hidden');
                },
                success: function (data) {
                    if (data.success) {
                        $('#acf_send_ok').removeClass('hidden');
                    } else {
                        if (typeof(data.captcha) != 'undefined' && !data.captcha) {
                            $('#acf_captcha_error').removeClass('hidden');
                            $('input[name="captcha"]').addClass('error').closest('div').find('img').click();

                        } else {
                            $('#acf_send_fail').removeClass('hidden');
                        }
                    }
                }
            });
        }else{
            console.log('no cheked');
            $('#acf_conditions_invalid').removeClass('hidden');
        }
    }else{
        $('#acf_send_invalid').removeClass('hidden');
    }

}
