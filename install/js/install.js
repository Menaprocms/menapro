/**
 * Creado por Xenon .
 * Autor: xnx
 * Date: 14/03/2016
 * Time: 19:58
 */

$(document).ready(function(){

    installer.license();

});
var installer={
    user:'',
    pass:'',
    db_name:'',
    db_user:'',
    db_pass:'',
    pass_strengh:0,
    getData:function(){
        var data=installer.getMenaproData();
        var db=installer.getDbData();
        $.extend(true, data,db);
        return data;
    },
    getMenaproData:function(){
        var data={
            'user':$('#user').val().trim(),
            'password':$('#password').val().trim(),
            'email':$('#email').val().trim(),
            'language':$('#lang_selection').val().trim()
        };
        return data;
    },
    getDbData:function(){
      var data={
          'db_name':$('#db_name').val().trim(),
          'db_user':$('#db_user').val().trim(),
          'db_password':$('#db_password').val().trim(),
          'db_prefix':$('#db_prefix').val().trim(),
          'db_server':$('#db_server').val().trim()
      };
      return data;
    },
    send:function(senderData){
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: baseDir + 'index.php?action='+ senderData.action,
            async: true,
            cache: false,
            headers: {"cache-control": "no-cache"},
            data: {
                data: senderData.data
                //menacsrf:csrfToken

            },
            beforeSend: function (a, b, c) {
                if(typeof (senderData.beforeSend)!= 'undefined'){
                    senderData.beforeSend(a, b, c);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if(typeof (senderData.error)!= 'undefined'){
                    senderData.error(jqXHR, textStatus, errorThrown);
                }
            },
            success: function (data) {
         
                if(typeof (senderData.success)!= 'undefined'){
                      senderData.success(data);
                }

            }
        });
    },
    showError:function(msg){
        $('#dynamic_error').remove();
        $('<div>',{
            id:'dynamic_error',
            class:'alert alert-danger install_error',
            html:msg
        }).appendTo($('.container'));
    },
    deleteInstall:function(){

        $('#delete_install').click(function (e) {

            var senderData = {
                action: 'removeinstall',
                success: function (data) {

                    if (typeof(data.delete) != 'undefined') {
                        if (!data.delete) {
                            $('.delete_folder').addClass('hidden');
                            $('.delete_folder_no').removeClass('hidden');
                            $('#delete_install').addClass('hidden');
                        }else{
                            $('.delete_folder_ok').removeClass('hidden');
                            $('#delete_install').addClass('hidden');
                            $('.delete_folder').addClass('hidden');
                        }
                    }

                }
            };

            installer.send(senderData);
        });
    },
    finalStep:function(data) {

                    if(data.success){
                        $('.install_error').addClass('hidden');
                        //$('.install_ok').removeClass('hidden');

                        if (typeof(data.delete) != 'undefined') {
                            if (!data.delete) {
                                $('.delete_folder').removeClass('hidden');
                                $('#delete_install').removeClass('hidden');
                            }
                        }
                        if (typeof(data.rename) != 'undefined') {
                            if (!data.rename) {
                                $('#back_link_text').html(baseDir + 'manager');
                                $('#back_link').attr('href', baseDir + 'manager');
                                $('.rename_folder').removeClass('hidden');
                                $('.rename_folder_adv').removeClass('hidden');
                            } else {
                                $('#back_link_text').html(baseDir + 'manager' + data.newname);
                                $('#back_link').attr('href', baseDir + 'manager' + data.newname);
                                $('.rename_folder').removeClass('hidden');
                            }
                        }
                        installer.deleteInstall();
                    }else{
                        $('.install_error').removeClass('hidden');
                        //$('.install_ok').addClass('hidden');
                    }

    },
    continueStep:function(){
        $('#continue_first_step').click(function (e) {
            $('.pass_error').addClass('hidden');
            var pass=$('#password').val().trim();
            var pass_conf=$('#password_confirm').val().trim();
            if(pass==pass_conf) {
                if(pass.length>=8 && installer.pass_strengh>1) {
                    /////CheckDB
                    $('.install_error').addClass('hidden');
                    var senderData = {
                        data: installer.getDbData(),
                        action: 'checkdb',
                        success: function (data) {
                            if (data.success) {
                               installer.continueStepSuccess();
                            }else{
                                $('.db_no').removeClass('hidden');
                            }
                        },
                        error:function(jqXHR, textStatus, errorThrown){
                            $('.db_no').removeClass('hidden');
                        }
                    };
                    installer.send(senderData);
                    ////CheckDB
                }else{
                    $('#error_size').removeClass('hidden');
                }
            }else{
                $('#error_confirm').removeClass('hidden');
            }

        });
        $('#check_db_connection').click(function (e) {
            $('.install_error').addClass('hidden');
            var senderData = {
                data: installer.getDbData(),
                action: 'checkdb',
                success: function (data) {

                    if (data.success) {
                        if (typeof(data.view) != 'undefined') {
                            $('.container').html(data.view);
                        }
                        $('.db_ok').removeClass('hidden');
                    }else{
                        $('.db_no').removeClass('hidden');
                    }
                },
                error:function(jqXHR, textStatus, errorThrown){
                    $('.db_no').removeClass('hidden');
                }
            };
            installer.send(senderData);
        });
    },
    continueStepSuccess:function(){

                var senderData = {
                    data: installer.getData(),
                    action: 'secondstep',
                    beforeSend:function(a, b, c){
                        $('#loader_install').removeClass('hidden');
                    },
                    success: function (data) {
                        $('.install_error').addClass('hidden');
                        $('#loader_install').addClass('hidden');
                        if (data.success) {

                            if (typeof(data.view) != 'undefined') {
                                $('.container').html(data.view);
                            }
                            installer.finalStep(data);
                        } else {
                            $('.install_no').removeClass('hidden');
                        }
                    }
                };
                installer.send(senderData);
                ///

    },
    license:function(){
        $('#start_installation').click(function(e){
            var data = {};
            var senderData={
                data:data,
                action:'license',
                success:function(data){
                    if(data.success) {
                        if (typeof(data.view) != 'undefined') {
                            $('.container').html(data.view);
                        }
                        //$('#license_terms').html(data.license_text);
                        installer.firstStep();
                    }else{
                        if(typeof data.errormsg!="undefined"){
                            installer.showError(data.errormsg);
                        }

                    }

                },
                error:function(jqXHR, textStatus, errorThrown){
                 
                }
            };
            installer.send(senderData);
        });
    },
    firstStep:function(){
        $('#accept_license').click(function(e){
            if($('#terms_accepted').is(':checked')) {
                //Don´t accept License Agreement
                var data = {};
                var senderData = {
                    data: data,
                    action: 'firststep',
                    success: function (data) {
                        $('#take_permissions').addClass('hidden');
                        $('.install_error').addClass('hidden');
                        if (data.success) {
                            if (typeof(data.view) != 'undefined') {
                                $('.container').html(data.view);
                            }
                            var options = {};
                            options.common = {
                                onKeyUp: function (evt, data) {
                                    if ($(evt.target).val().length >= 8) {
                                        $('.progress').removeClass('hidden');
                                        $('#pass_length_note').addClass('hidden');
                                    } else {
                                        $('.progress').addClass('hidden');
                                        $('#pass_length_note').removeClass('hidden');
                                    }
                                    installer.pass_strengh = data.verdictLevel;
                                }
                            };
                            options.ui = {showVerdictsInsideProgressBar: true};

                            $('#password').pwstrength(options);
                            $('.progress').addClass('hidden');
                            $('#lang_selection').select2();

                            installer.continueStep();
                        } else {

                            $.each(data.take_permissions, function (k, v) {
                                $('<li>', {
                                    class: 'take_permissions_file'
                                }).html(v).appendTo($('#take_permissions'));
                            });
                            $('#take_permissions').removeClass('hidden');
                            $('.install_error').removeClass('hidden');
                        }
                    }
                };
                installer.send(senderData);
            }else{
                //Don´t accept License Agreement
                installer.showError('Please, to continue accept License Agreement');
            }
        });

    }
}
